<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// Homepage (public, shows login/register)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [TicketController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('tickets', TicketController::class)->except(['show']);
    Route::post('/assign-tickets', [TicketController::class, 'assignToMe'])->name('tickets.assignToMe');

    Route::get('/tickets/statistics', [TicketController::class, 'statistics'])->name('tickets.statistics');
});

require __DIR__.'/auth.php';
