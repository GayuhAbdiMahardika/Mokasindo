<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\CompanyController;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WishlistController;
use App\Models\User;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WishlistController;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

Route::get('/', function () {
    return view('landing');
});

Route::prefix('etalase')->group(function () {
    Route::get('/filters', [VehicleController::class, 'filters']);
    Route::get('/vehicles', [VehicleController::class, 'index']);
    Route::get('/vehicles/{id}', [VehicleController::class, 'show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/wishlists', [WishlistController::class, 'index']);
    Route::post('/wishlists', [WishlistController::class, 'store']);
    Route::delete('/wishlists/{id}', [WishlistController::class, 'destroy']);
});

// Group Route Company
Route::controller(CompanyController::class)->group(function () {
    Route::get('/about', 'about')->name('company.about');
    Route::get('/faq', 'faq')->name('company.faq');
    Route::get('/contact', 'contact')->name('company.contact');
    Route::post('/contact', 'storeContact')->name('company.contact.store');

    Route::get('/careers', 'career')->name('company.career');
    Route::get('/careers/{id}', 'careerDetail')->name('company.career.show');
    Route::post('/careers/{id}/apply', 'storeCareerApplication')->name('company.career.store');

    Route::get('/terms', function () {
        $page = Page::findBySlug('terms');
        return view('pages.company.generic', compact('page'));
    })->name('company.terms');

    Route::get('/privacy', function () {
        $page = Page::findBySlug('privacy-policy');
        return view('pages.company.generic', compact('page'));
    })->name('company.privacy');

    Route::get('/how-it-works', function () {
        $page = Page::findBySlug('how-it-works');
        return view('pages.company.generic', compact('page'));
    })->name('company.how_it_works');

    Route::get('/cookie-policy', function () {
        $page = Page::findBySlug('cookie-policy');
        return view('pages.company.generic', compact('page'));
    })->name('company.cookie_policy');
});
// --- TES EVENT LISTENER REGISTERED ---
Route::get('/tes-register', function () {
    // Kita pakai WAKTU (time) biar emailnya unik terus setiap detik
    $unik = time();
    
    // Membuat User Baru Pura-pura
    $userBaru = User::create([
        'name' => "Member $unik",
        'email' => "member$unik@test.com",
        'password' => Hash::make('password123'),
        'telegram_chat_id' => '6179231520', // GANTI ID INI DENGAN ID TELEGRAM SIAPAPUN YANG MAU TES ambil di @userinfobot
        'role' => 'member'
    ]);

    // Memicu Event (Seolah-olah user baru saja daftar)
    // Ini akan memicu Listener untuk kirim Laporan ke Admin
    event(new Registered($userBaru));

    return "✅ User <b>{$userBaru->name}</b> berhasil didaftarkan! <br><br>" .
           "1. Cek HP Admin (Laporan Pendaftaran Masuk).<br>" .
           "2. Cek HP User (Ucapan Selamat Datang Masuk).";
});

// Instagram feed (example)
Route::get('/instagram-feed', [InstagramController::class, 'getMedia']);

// ====================================================
// 4. HELPER TESTING (Force Login)
// ====================================================
// Jalankan URL ini sekali di browser agar kamu login otomatis sebagai ID 1 (http://mokasindo.test/force-login)
Route::get('/force-login', function () {
    $user = \App\Models\User::find(1);
    
    if (!$user) {
        // Buat user dummy jika belum ada
        $user = \App\Models\User::create([
            'id' => 1,
            'name' => 'Tester User',
            'email' => 'tester@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    Auth::login($user);
    
    return "<h1>Berhasil Login!</h1> <p>Login sebagai: <b>" . $user->name . "</b></p><p>Silakan akses <a href='/wishlists'>/wishlists</a> atau <a href='/etalase/vehicles'>/etalase/vehicles</a></p>";
});

// Tampilkan form register perusahaan
Route::get('/register', function () {
    return view('pages.company.register');
})->name('register.form');

// Terima form register (sederhana)
Route::post('/register', function (Request $request) {
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:50',
        'password' => 'required|confirmed|min:6',
        'province_id' => 'required',
        'city_id' => 'required',
        'district_id' => 'required',
        'sub_district_id' => 'required',
        'postal_code' => 'required',
        'address' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return redirect('/register')->withErrors($validator)->withInput();
    }

    // TODO: simpan data user/ perusahaan sesuai kebutuhan aplikasi
    // Untuk sementara redirect kembali dengan pesan sukses
    return redirect('/register')->with('status', 'Registrasi berhasil (demo).');
})->name('company.register');

// Login: tampilkan form login
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/');
    }
    return view('pages.company.login');
})->name('login.form');

// Proses login
Route::post('/login', function (Request $request) {
    $data = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = ['email' => $data['email'], 'password' => $data['password']];
    $remember = $request->has('remember');

    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
})->name('login.process');
