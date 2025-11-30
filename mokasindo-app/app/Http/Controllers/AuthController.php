<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\SubDistrict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman register
     * (resources/views/pages/company/register.blade.php)
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        // Ambil semua provinsi
        $provinces = Province::orderBy('name')->get(['id', 'name']);

        // Default kosong untuk pertama kali buka page
        $cities       = collect();
        $districts    = collect();
        $subDistricts = collect();

        // Kalau habis gagal validasi, isi ulang dropdown berantai
        $oldProvince = old('province_id');
        if ($oldProvince) {
            $cities = City::where('province_id', $oldProvince)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        $oldCity = old('city_id');
        if ($oldCity) {
            $districts = District::where('city_id', $oldCity)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        $oldDistrict = old('district_id');
        if ($oldDistrict) {
            $subDistricts = SubDistrict::where('district_id', $oldDistrict)
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        return view('pages.company.register', compact(
            'provinces',
            'cities',
            'districts',
            'subDistricts'
        ));
    }

    /**
     * Proses register user baru
     */
    public function register(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone'           => ['required', 'string', 'max:20'],
            'password'        => ['required', 'confirmed', 'min:6'],

            'province_id'     => ['required', 'exists:provinces,id'],
            'city_id'         => ['required', 'exists:cities,id'],
            'district_id'     => ['required', 'exists:districts,id'],
            'sub_district_id' => ['required', 'exists:sub_districts,id'],

            'postal_code'     => ['nullable', 'string', 'max:10'],
            'address'         => ['nullable', 'string'],
        ]);

        // Buat user baru
        // password TIDAK perlu Hash::make() karena di User model sudah:
        // 'password' => 'hashed'
        $user = User::create([
            'name'            => $data['name'],
            'email'           => $data['email'],
            'phone'           => $data['phone'],
            'password'        => $data['password'],
            'province_id'     => $data['province_id'],
            'city_id'         => $data['city_id'],
            'district_id'     => $data['district_id'],
            'sub_district_id' => $data['sub_district_id'],
            'postal_code'     => $data['postal_code'] ?? null,
            'address'         => $data['address'] ?? null,
            // role, is_active, dll pakai default dari migration
        ]);

        // Setelah registrasi → arahkan ke halaman login
        return redirect()
            ->route('login.form')
            ->with('status', 'Registrasi berhasil. Silakan login dengan email dan password yang baru dibuat.');
    }

    /**
     * Tampilkan halaman login
     * (resources/views/pages/company/login.blade.php)
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('pages.company.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        // Jika gagal login
        return back()
            ->withErrors(['email' => 'Email atau password salah'])
            ->withInput($request->only('email'));
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('status', 'Anda telah logout.');
    }
}
