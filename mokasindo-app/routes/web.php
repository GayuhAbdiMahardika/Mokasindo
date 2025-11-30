<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ShowcaseController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WishlistController;
use App\Models\Page;

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

Route::controller(ShowcaseController::class)->group(function (){
    Route::get('/showcase', 'index')->name('showcase.index');
    Route::get('/showcase/(id-produk)', 'showcase')->name('showcase.showcase'); //nanti atur ini
});

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
