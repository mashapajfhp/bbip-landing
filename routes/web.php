<?php

use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::post('/register-interest', [LeadController::class, 'store'])->name('leads.store')->middleware(['throttle:5,1']);
