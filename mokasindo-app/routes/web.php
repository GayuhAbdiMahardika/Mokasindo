<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Models\Page;

Route::get('/', function () {
    return view('landing');
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
