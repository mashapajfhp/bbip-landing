<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::post('/register-interest', [LeadController::class, 'store'])->name('leads.store')->middleware(['throttle:5,1']);
