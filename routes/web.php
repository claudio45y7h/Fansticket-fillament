<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Models\Order;
use App\Models\User;

// Vista web del correo de bienvenida
Route::get('/mail/welcome/{user}', function ($userId) {
    $user = User::findOrFail($userId);
    $webviewUrl = route('mail.welcome', ['user' => $user->id]);
    return view('emails.welcome', compact('user', 'webviewUrl'));
})->name('mail.welcome');

// Vista web del correo de confirmación de compra
Route::get('/mail/order-confirmation/{order}', function ($orderId) {
    $order = Order::with('tickets')->findOrFail($orderId);
    $viewInBrowserUrl = route('mail.order_confirmation', ['order' => $order->id]);
    return view('emails.order_confirmation', compact('order', 'viewInBrowserUrl'));
})->name('mail.order_confirmation');

Route::get('/orders/{order}/download-pdf', [App\Http\Controllers\OrderController::class, 'downloadOrderPDF'])
    ->name('orders.download-pdf')
    ->middleware(['auth']);

Route::get('/', function () {
    return view('welcome');
});
