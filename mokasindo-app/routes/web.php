<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;       // Tambahan dari GitHub
use Illuminate\Auth\Events\Registered;     // Tambahan dari GitHub
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;
use App\Models\User;
use App\Services\TelegramService;          // Tambahan dari GitHub

// Controllers
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyAdController;
use App\Http\Controllers\MyBidController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WilayahController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
});

// Group Etalase
Route::prefix('etalase')->group(function () {
    Route::get('/filters', [VehicleController::class, 'filters']);
    Route::get('/vehicles', [VehicleController::class, 'index']);
    Route::get('/vehicles/{id}', [VehicleController::class, 'show']);
});

// Group Member Area (Auth Required)
Route::middleware('auth')->group(function () {
    // 1. Wishlists
    Route::get('/wishlists', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlists', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlists/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // 2. Edit Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // 3. Ganti Password
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // 4. Iklan Saya
    Route::get('/my-ads', [MyAdController::class, 'index'])->name('my.ads');

    // 5. Hasil Bid / Lelang
    Route::get('/my-bids', [MyBidController::class, 'index'])->name('my.bids');
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
// (Bagian ini dikembalikan dari kode GitHub agar fitur tes notifikasi jalan)
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
    $user = User::find(1);
    
    if (!$user) {
        // Buat user dummy jika belum ada
        $user = User::create([
            'id' => 1,
            'name' => 'Tester User',
            'email' => 'tester@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    Auth::login($user);
    
    return "<h1>Berhasil Login!</h1> 
            <p>Login sebagai: <b>" . $user->name . "</b></p>
            <p>Menu Cepat: 
                <a href='/profile'>[Ke Profil]</a> | 
                <a href='/wishlists'>[Ke Wishlists]</a> | 
                <a href='/etalase/vehicles'>[Ke Etalase]</a>
            </p>";
});


// REGISTER
Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->name('register.form');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.process');

Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login.form');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// WILAYAH ROUTES
Route::get('/wilayah/cities', [WilayahController::class, 'cities'])
    ->name('wilayah.cities');

Route::get('/wilayah/districts', [WilayahController::class, 'districts'])
    ->name('wilayah.districts');

Route::get('/wilayah/sub-districts', [WilayahController::class, 'subDistricts'])
    ->name('wilayah.subdistricts');

