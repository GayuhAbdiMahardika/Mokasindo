<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;       // Tambahan dari GitHub
use Illuminate\Auth\Events\Registered;     // Tambahan dari GitHub
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;
use App\Models\User;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyAdController;
use App\Http\Controllers\MyBidController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AiAgentController;
use App\Http\Controllers\SettingController;

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

Route::controller(ShowcaseController::class)->group(function (){
    Route::get('/showcase', 'index')->name('showcase.index');
    Route::get('/showcase/(id-produk)', 'showcase')->name('showcase.showcase'); //nanti atur ini
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

Route::get('/fb/post-form', [FacebookController::class, 'showForm']);
Route::post('/fb/post-url', [FacebookController::class, 'postImageUrl']);
Route::post('/fb/post-upload', [FacebookController::class, 'postImageUpload']);

// ====================================================
// FORCE LOGIN OWNER (Testing Owner Dashboard)
// ====================================================

Route::get('/force-login-owner', function () {
    $user = \App\Models\User::where('email', 'owner@mokasindo.com')->first();
    
    if (!$user) {
        // Buat user owner jika belum ada
        $user = \App\Models\User::create([
            'name' => 'Owner Mokasindo',
            'email' => 'owner@mokasindo.com',
            'password' => bcrypt('password123'),
            'role' => 'owner',
            'phone' => '+6281234567890',
            'is_active' => true,
            'verified_at' => now(),
        ]);
    }

    Auth::login($user);
    
    return "<h1>Berhasil Login sebagai Owner!</h1> 
            <p>Login sebagai: <b>" . $user->name . "</b> (Role: " . $user->role . ")</p>
            <div style='margin: 20px 0;'>
                <p><strong>Akses Dashboard Owner:</strong></p>
                <ul>
                    <li><a href='/owner/overview' style='color: blue; font-size: 16px;'>→ Overview</a></li>
                    <li><a href='/owner/reports' style='color: green; font-size: 16px;'>→ Laporan & Analitik</a></li>
                </ul>
            </div>
            <p><small>Note: Gunakan link ini khusus untuk testing dashboard owner</small></p>";
});

// Owner Routes (Dashboard & AJAX)
Route::prefix('owner')->middleware('auth')->group(function () {
    Route::get('/overview', [OverviewController::class, 'index'])->name('owner.overview');
    Route::get('/reports', [ReportController::class, 'index'])->name('owner.reports');
    // Overview AJAX Routes:
    Route::get('/overview/quick-stats', [OverviewController::class, 'getQuickStats']);
    Route::get('/overview/weekly-chart', [OverviewController::class, 'getWeeklyChart']);
    Route::get('/overview/ai-metrics', [AiAgentController::class, 'getRealPerformanceMetrics']);
    Route::get('/overview/ai-recommendations', [AiAgentController::class, 'getRealRecommendations']); 
    Route::get('/overview/ai-alerts', [AiAgentController::class, 'getRealAlerts']);
    // Reports AJAX Routes:
    Route::get('/reports/chart-data', [ReportController::class, 'getChartData']);
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel']);
    // Settings (Admin / Owner)
    Route::get('/settings', [SettingController::class, 'edit'])
        ->name('owner.settings.edit');
    Route::post('/settings', [SettingController::class, 'update'])
        ->name('owner.settings.update');
});