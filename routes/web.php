<?php

declare(strict_types=1);

use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/leads');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
Route::get('/leads/duplicates', [LeadController::class, 'duplicates'])->name('leads.duplicates');
Route::post('/leads/import', [LeadController::class, 'import'])->name('leads.import');
Route::post('/leads/check-duplicate', [LeadController::class, 'checkDuplicate'])->name('leads.check-duplicate');
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
