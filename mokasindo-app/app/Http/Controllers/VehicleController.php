<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\SubDistrict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\QuotaService;
use App\Exceptions\QuotaExceededException;

class VehicleController extends Controller
{
    public function __construct(private readonly QuotaService $quotaService)
    {
    }

    // 1. GET LIST MOBIL (Pencarian & Filter)
    public function index(Request $request)
    {
        // Start Query: Hanya ambil yang statusnya 'approved'
        $query = Vehicle::approved()
            ->with(['primaryImage', 'city', 'auction', 'province']); // Load relasi biar data lengkap

        // A. Search Keyword (Cari di Brand, Model, atau Deskripsi)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // B. Filter Kategori (Motor/Mobil)
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // C. Filter Harga Range
        if ($request->filled('min_price')) {
            $query->where('starting_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('starting_price', '<=', $request->max_price);
        }

        // D. Filter Lokasi (Kota)
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // E. Sorting (Urutan)
        $sort = $request->input('sort', 'latest');
        if ($sort === 'cheapest') {
            $query->orderBy('starting_price', 'asc');
        } elseif ($sort === 'expensive') {
            $query->orderBy('starting_price', 'desc');
        } else {
            $query->latest('approved_at'); // Default: Terbaru
        }

        // Eksekusi (12 item per halaman)
        return response()->json([
            'status' => 'success',
            'data' => $query->paginate(12)
        ]);
    }

    // 2. GET DETAIL SATU MOBIL
    public function show($id)
    {
        $vehicle = Vehicle::approved()
            ->with(['images', 'user', 'city', 'province', 'auction', 'district'])
            ->find($id);

        if (!$vehicle) {
            return response()->json(['message' => 'Kendaraan tidak ditemukan atau belum tayang'], 404);
        }

        // Tambah views count
        $vehicle->incrementViews();

        return response()->json([
            'status' => 'success',
            'data' => $vehicle
        ]);
    }

    // 3. GET DATA FILTERS (Untuk Dropdown di Frontend)
    public function filters()
    {
        // Ambil list Brand yang unik dari database
        $brands = Vehicle::approved()
                    ->select('brand')
                    ->distinct()
                    ->orderBy('brand')
                    ->pluck('brand');

        return response()->json([
            'brands' => $brands,
            'categories' => ['mobil', 'motor'], // Manual karena enum
        ]);
    }

    /**
     * 4. SHOW FORM CREATE KENDARAAN
     */
    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        $subDistricts = SubDistrict::orderBy('name')->get();

        return view('pages.vehicles.create', compact('provinces', 'cities', 'districts', 'subDistricts'));
    }

    /**
     * 5. STORE KENDARAAN BARU KE DATABASE
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:mobil,motor',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'mileage' => 'required|integer|min:0',
            'transmission' => 'required|in:manual,automatic,semi-automatic',
            'fuel_type' => 'required|in:bensin,diesel,listrik,hybrid',
            'color' => 'required|string|max:50',
            'starting_price' => 'required|numeric|min:0',
            'condition' => 'required|in:baru,bekas',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'sub_district_id' => 'nullable|exists:sub_districts,id',
            'address' => 'required|string',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $user = Auth::user();
            // Prevent users from bypassing role/override listing limits
            $this->quotaService->ensureCanCreateListing($user);

            DB::beginTransaction();

            // Create vehicle
            $vehicle = Vehicle::create([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'year' => $validated['year'],
                'mileage' => $validated['mileage'],
                'transmission' => $validated['transmission'],
                'fuel_type' => $validated['fuel_type'],
                'color' => $validated['color'],
                'starting_price' => $validated['starting_price'],
                'condition' => $validated['condition'],
                'province_id' => $validated['province_id'],
                'city_id' => $validated['city_id'],
                'district_id' => $validated['district_id'],
                'sub_district_id' => $validated['sub_district_id'],
                'address' => $validated['address'],
                'status' => 'pending', // Waiting for admin approval
            ]);

            // Upload and save images
            if ($request->hasFile('images')) {
                $order = 1;
                foreach ($request->file('images') as $image) {
                    $path = $image->store('vehicles', 'public');
                    
                    VehicleImage::create([
                        'vehicle_id' => $vehicle->id,
                        'image_path' => $path,
                        'is_primary' => $order === 1, // First image is primary
                        'order' => $order++
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('my-ads')->with('success', 'Kendaraan berhasil ditambahkan dan menunggu persetujuan admin!');

        } catch (QuotaExceededException $exception) {
            return back()
                ->withInput()
                ->withErrors(['quota' => $exception->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan kendaraan: ' . $e->getMessage());
        }
    }

    /**
     * 6. SHOW FORM EDIT KENDARAAN
     */
    public function edit($id)
    {
        $vehicle = Vehicle::with('images')->findOrFail($id);

        // Check authorization
        if ($vehicle->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $provinces = Province::orderBy('name')->get();
        $cities = City::where('province_id', $vehicle->province_id)->orderBy('name')->get();
        $districts = District::where('city_id', $vehicle->city_id)->orderBy('name')->get();
        $subDistricts = SubDistrict::where('district_id', $vehicle->district_id)->orderBy('name')->get();

        return view('pages.vehicles.edit', compact('vehicle', 'provinces', 'cities', 'districts', 'subDistricts'));
    }

    /**
     * 7. UPDATE KENDARAAN DI DATABASE
     */
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        // Check authorization
        if ($vehicle->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:mobil,motor',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'mileage' => 'required|integer|min:0',
            'transmission' => 'required|in:manual,automatic,semi-automatic',
            'fuel_type' => 'required|in:bensin,diesel,listrik,hybrid',
            'color' => 'required|string|max:50',
            'starting_price' => 'required|numeric|min:0',
            'condition' => 'required|in:baru,bekas',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'sub_district_id' => 'nullable|exists:sub_districts,id',
            'address' => 'required|string',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:vehicle_images,id'
        ]);

        try {
            DB::beginTransaction();

            // Update vehicle data
            $vehicle->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'year' => $validated['year'],
                'mileage' => $validated['mileage'],
                'transmission' => $validated['transmission'],
                'fuel_type' => $validated['fuel_type'],
                'color' => $validated['color'],
                'starting_price' => $validated['starting_price'],
                'condition' => $validated['condition'],
                'province_id' => $validated['province_id'],
                'city_id' => $validated['city_id'],
                'district_id' => $validated['district_id'],
                'sub_district_id' => $validated['sub_district_id'],
                'address' => $validated['address'],
            ]);

            // Delete selected images
            if ($request->has('delete_images')) {
                $imagesToDelete = VehicleImage::whereIn('id', $request->delete_images)
                    ->where('vehicle_id', $vehicle->id)
                    ->get();

                foreach ($imagesToDelete as $image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }

            // Upload new images
            if ($request->hasFile('images')) {
                $maxOrder = VehicleImage::where('vehicle_id', $vehicle->id)->max('order') ?? 0;
                $order = $maxOrder + 1;

                foreach ($request->file('images') as $image) {
                    $path = $image->store('vehicles', 'public');
                    
                    VehicleImage::create([
                        'vehicle_id' => $vehicle->id,
                        'image_path' => $path,
                        'is_primary' => false,
                        'order' => $order++
                    ]);
                }
            }

            // Ensure there's always a primary image
            if (!VehicleImage::where('vehicle_id', $vehicle->id)->where('is_primary', true)->exists()) {
                $firstImage = VehicleImage::where('vehicle_id', $vehicle->id)->orderBy('order')->first();
                if ($firstImage) {
                    $firstImage->update(['is_primary' => true]);
                }
            }

            DB::commit();

            return redirect()->route('my-ads')->with('success', 'Kendaraan berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mengupdate kendaraan: ' . $e->getMessage());
        }
    }

    /**
     * 8. DELETE KENDARAAN DARI DATABASE
     */
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        // Check authorization
        if ($vehicle->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            // Delete all vehicle images
            $images = VehicleImage::where('vehicle_id', $vehicle->id)->get();
            foreach ($images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            // Delete vehicle
            $vehicle->delete();

            DB::commit();

            return redirect()->route('my-ads')->with('success', 'Kendaraan berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus kendaraan: ' . $e->getMessage());
        }
    }
}