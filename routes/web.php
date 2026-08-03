<?php

use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PortfolioPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicePageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public pages
Route::get('/', function () {
    return Inertia::render('Auth/Login');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServicePageController::class, 'index'])->name('services');
Route::get('/portfolio', [PortfolioPageController::class, 'index'])->name('portfolio');

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/order', [OrderController::class, 'index'])->name('order');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{order}/success', [OrderController::class, 'success'])->name('order.success');

// Post-login redirect target - sends admins to their dashboard, everyone else home
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->role === 'admin') {
        return redirect('/admin/overview');
    }

    return redirect('/home');
})->middleware('auth')->name('dashboard');

// Admin pages - auth + admin-role protected
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/overview', function () {
        return Inertia::render('Admin/Overview');
    })->name('overview');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{order}/notes', [AdminOrderController::class, 'updateNotes'])->name('orders.update-notes');
    Route::patch('/orders/{order}/accept', [AdminOrderController::class, 'accept'])->name('orders.accept');

    Route::get('/clients', [AdminClientController::class, 'index'])->name('clients');


    // Services - full CRUD
    Route::get('/services', [AdminServiceController::class, 'index'])->name('services');
    Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
    Route::put('/services/{service}', [AdminServiceController::class, 'update'])->name('services.update');
    Route::patch('/services/{service}/toggle', [AdminServiceController::class, 'toggle'])->name('services.toggle');
    Route::delete('/services/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');

    // Portfolio - full CRUD
    Route::get('/portfolio', [AdminPortfolioController::class, 'index'])->name('portfolio');
    Route::post('/portfolio', [AdminPortfolioController::class, 'store'])->name('portfolio.store');
    Route::put('/portfolio/{portfolioItem}', [AdminPortfolioController::class, 'update'])->name('portfolio.update');
    Route::delete('/portfolio/{portfolioItem}', [AdminPortfolioController::class, 'destroy'])->name('portfolio.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';