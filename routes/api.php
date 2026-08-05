<?php

declare(strict_types=1);

use App\Http\Controllers\Api\LeadController;
use Illuminate\Support\Facades\Route;

Route::get('/leads', [LeadController::class, 'index'])->name('api.leads.index');
Route::post('/leads', [LeadController::class, 'store'])->name('api.leads.store');
Route::get('/leads/dashboard', [LeadController::class, 'dashboard'])->name('api.leads.dashboard');
