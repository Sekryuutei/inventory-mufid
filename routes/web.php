<?php

use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('auth.login');
});

Auth::routes();

// Kelompokkan semua route yang memerlukan login
Route::middleware(['auth'])->group(function () {
    // Dashboard utama setelah login
    Route::get('/home', function () {
        // Default redirect ke inventory
        return redirect()->route('inventory.index');
    })->name('home');

    // --- FITUR INVENTARIS (Bisa diakses semua role yang login) ---
    Route::resource('inventory', InventoryTransactionController::class)->except(['show']);
    Route::get('/inventory/{id}/confirm-delete', [InventoryTransactionController::class, 'confirmDelete'])->name('inventory.confirm-delete');
    Route::get('/inventory/{id}/edit', [InventoryTransactionController::class, 'edit'])->name('inventory.edit');
    
    // --- FITUR KELOLA PRODUK (Hanya Admin & Manager) ---
    Route::middleware(['check.role:admin,manager'])->group(function () {
        Route::resource('products', ProductController::class);
    });

    // --- FITUR KELOLA PENGGUNA (Hanya Admin) ---
    Route::middleware(['check.role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/users/{id}/confirm-delete', [UserController::class, 'confirmDelete'])->name('users.confirm-delete');
        Route::post('/users/{user}/change-role', [UserController::class, 'changeRole'])->name('users.change-role');
    });
});