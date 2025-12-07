<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\VehicleSearchController;
use App\Http\Controllers\TelegramController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Location API
Route::prefix('locations')->group(function () {
    Route::get('/provinces', [LocationController::class, 'provinces']);
    Route::get('/cities/{province_id}', [LocationController::class, 'cities']);
    Route::get('/districts/{city_id}', [LocationController::class, 'districts']);
    Route::get('/sub-districts/{district_id}', [LocationController::class, 'subDistricts']);
    Route::post('/reverse-geocode', [LocationController::class, 'reverseGeocode']);
});

// Vehicle Search API
Route::prefix('vehicles')->group(function () {
    Route::get('/search', [VehicleSearchController::class, 'search']);
    Route::get('/nearby', [VehicleSearchController::class, 'nearby']);
    Route::get('/{id}/map', [VehicleSearchController::class, 'showOnMap']);
});

// Telegram webhook (no auth, hit by Telegram servers)
Route::post('/telegram/webhook', [TelegramController::class, 'webhook'])->name('telegram.webhook');
