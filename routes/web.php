<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Guest / All Users
Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/event/{slug}', [EventController::class, 'show'])->name('event.show');

// Custom Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Checkout & Tickets
Route::middleware(['auth'])->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout.show');
    Route::post('/checkout/purchase', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/payment/{order_number}', [CheckoutController::class, 'showPayment'])->name('checkout.payment');
    Route::post('/checkout/pay/{order_number}', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::get('/checkout/ticket/{ticket_code}', [CheckoutController::class, 'showTicket'])->name('checkout.ticket');
    Route::get('/my-tickets', [CheckoutController::class, 'myTickets'])->name('customer.tickets');
    
    // Customer Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin Panel (Protected by Auth & Admin Middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/events', [AdminController::class, 'events'])->name('events.index');
    Route::get('/events/create', [AdminController::class, 'createEvent'])->name('events.create');
    Route::post('/events', [AdminController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{event}/edit', [AdminController::class, 'editEvent'])->name('events.edit');
    Route::put('/events/{event}', [AdminController::class, 'updateEvent'])->name('events.update');
    Route::delete('/events/{event}', [AdminController::class, 'deleteEvent'])->name('events.destroy');
    Route::post('/tickets/check-in', [AdminController::class, 'checkIn'])->name('tickets.checkin');

    // Orders Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::delete('/orders/{order}', [AdminController::class, 'deleteOrder'])->name('orders.destroy');

    // Ticket Categories Management
    Route::post('/events/{event}/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

    // Customers Management
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers.index');

    // Event Status Toggle
    Route::patch('/events/{event}/toggle-status', [AdminController::class, 'toggleEventStatus'])->name('events.toggle-status');
});
