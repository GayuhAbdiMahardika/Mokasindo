<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleSearchController extends Controller
{
    /**
     * Search vehicles with filters and location-based search
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:255',
            'category' => 'nullable|in:motor,mobil',
            'province' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'sub_district' => 'nullable|string|max:100',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:500',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'year_from' => 'nullable|integer|min:1900|max:2100',
            'year_to' => 'nullable|integer|min:1900|max:2100',
            'sort' => 'nullable|in:price_asc,price_desc,distance,newest',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = Vehicle::approved()
            ->with(['primaryImage', 'auction']);

        // Keyword search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Location filters
        if ($request->filled('province')) {
            $query->where('province', 'like', "%{$request->province}%");
        }
        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }
        if ($request->filled('district')) {
            $query->where('district', 'like', "%{$request->district}%");
        }
        if ($request->filled('sub_district')) {
            $query->where('sub_district', 'like', "%{$request->sub_district}%");
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('starting_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('starting_price', '<=', $request->max_price);
        }

        // Year range filter
        if ($request->filled('year_from')) {
            $query->where('year', '>=', $request->year_from);
        }
        if ($request->filled('year_to')) {
            $query->where('year', '<=', $request->year_to);
        }

        // Radius-based search using Haversine formula
        $hasDistance = false;
        if ($request->filled('lat') && $request->filled('lng')) {
            $lat = $request->lat;
            $lng = $request->lng;
            $radius = $request->input('radius', 50);

            $query->nearby($lat, $lng, $radius);
            $hasDistance = true;
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('starting_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('starting_price', 'desc');
                break;
            case 'distance':
                if ($hasDistance) {
                    $query->orderBy('distance_km', 'asc');
                } else {
                    $query->latest('approved_at');
                }
                break;
            case 'newest':
            default:
                $query->latest('approved_at');
                break;
        }

        $perPage = $request->input('per_page', 20);
        $results = $query->paginate($perPage);

        // Transform results
        $data = $results->through(function ($vehicle) use ($hasDistance) {
            $item = [
                'id' => $vehicle->id,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
                'price' => $vehicle->starting_price,
                'category' => $vehicle->category,
                'condition' => $vehicle->condition,
                'mileage' => $vehicle->mileage,
                'transmission' => $vehicle->transmission,
                'fuel_type' => $vehicle->fuel_type,
                'latitude' => $vehicle->latitude,
                'longitude' => $vehicle->longitude,
                'location' => [
                    'province' => $vehicle->province,
                    'city' => $vehicle->city,
                    'district' => $vehicle->district,
                    'sub_district' => $vehicle->sub_district,
                ],
                'images' => $vehicle->primaryImage ? [$vehicle->primaryImage] : [],
                'auction' => $vehicle->auction,
            ];

            if ($hasDistance && isset($vehicle->distance_km)) {
                $item['distance_km'] = round($vehicle->distance_km, 2);
            }

            return $item;
        });

        return response()->json([
            'status' => 'success',
            'data' => $data->items(),
            'meta' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
            ]
        ]);
    }

    /**
     * Get nearby vehicles
     */
    public function nearby(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:500',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $lat = $request->lat;
        $lng = $request->lng;
        $radius = $request->input('radius', 50);
        $limit = $request->input('limit', 20);

        $vehicles = Vehicle::approved()
            ->with(['primaryImage'])
            ->nearby($lat, $lng, $radius)
            ->limit($limit)
            ->get();

        $data = $vehicles->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
                'price' => $vehicle->starting_price,
                'latitude' => $vehicle->latitude,
                'longitude' => $vehicle->longitude,
                'distance_km' => round($vehicle->distance_km, 2),
                'location' => [
                    'province' => $vehicle->province,
                    'city' => $vehicle->city,
                ],
                'image' => $vehicle->primaryImage?->image_path,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Get vehicle location for map display
     */
    public function showOnMap($id)
    {
        $vehicle = Vehicle::approved()
            ->with(['images'])
            ->find($id);

        if (!$vehicle) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vehicle not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $vehicle->id,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
                'price' => $vehicle->starting_price,
                'latitude' => $vehicle->latitude,
                'longitude' => $vehicle->longitude,
                'full_address' => $vehicle->full_address,
                'location' => [
                    'province' => $vehicle->province,
                    'city' => $vehicle->city,
                    'district' => $vehicle->district,
                    'sub_district' => $vehicle->sub_district,
                    'postal_code' => $vehicle->postal_code,
                ],
                'images' => $vehicle->images,
            ]
        ]);
    }
}
