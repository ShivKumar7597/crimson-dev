<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrentRmsInformatonController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OpportunitiesController;

Route::get('/', function () {
//dd("inside");
    return view('welcome');
});

// Dashboard

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return Inertia\Inertia::render('Dashboard');
// })->name('dashboard');


// Current RMS Setting
Route::put('/user/current-rms-information', [CurrentRmsInformatonController::class, 'update'])
    ->name('current-rms-information.update')
    ->middleware('auth:sanctum');

// Products
Route::get('/dashboard', [ProductController::class, 'index'])
    ->name('dashboard')
    ->middleware(['auth:sanctum', 'verified', 'remember']);

Route::put('products/{product}', [ProductController::class, 'update'])
    ->name('products.update')
    ->middleware('auth:sanctum');

Route::get('/sync-products', [ProductController::class, 'syncProducts'])
    ->name('products.sync')
    ->middleware(['auth:sanctum', 'verified']);

// Memebr Controller
Route::get('/members', [MemberController::class, 'index'])
    ->name('members')
    ->middleware(['auth:sanctum', 'verified', 'remember']);

Route::get('/sync-members', [MemberController::class, 'syncMembers'])
    ->name('members.sync')
    ->middleware(['auth:sanctum', 'verified']);

// Opportunities Controller
Route::get('/opportunities', [OpportunitiesController::class, 'index'])
    ->name('opportunities')
    ->middleware(['auth:sanctum', 'verified', 'remember']);

Route::get('/sync-opportunities', [OpportunitiesController::class, 'syncOpportunities'])
    ->name('opportunities.sync')
    ->middleware(['auth:sanctum', 'verified']);



