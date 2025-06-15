<?php
use Illuminate\Http\Request;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\OrderPdfController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeatmapController;
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
    });

    // Rutas de órdenes protegidas
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::put('/{id}', [OrderController::class, 'update']);
        Route::delete('/{id}', [OrderController::class, 'destroy']);
    });

    // Rutas de actividades protegidas
    
});

// Ruta pública para crear orden por user_id
Route::post('orders/by-user-id', [OrderController::class, 'storeByUserId']);
Route::get('orders/{id}/send-pdf', [OrderPdfController::class, 'sendOrderPdf']);
Route::get('orders/{id}/download-pdf', [OrderPdfController::class, 'downloadOrderPdf']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('/{id}', 
    
    [EventController::class, 'show']);
    Route::post('/bulkstore', [EventController::class, 'bulkStore']);
    Route::post('/', [EventController::class, 'store']);
    Route::put('/{id}', [EventController::class, 'update']);
    Route::delete('/{id}', [EventController::class, 'destroy']);
});

Route::prefix('tickets')->group(function () {
    Route::get('/', [TicketController::class, 'index']);
    Route::get('/{id}', [TicketController::class, 'show']);
    Route::get('/event/{id}', [TicketController::class, 'getTicketsByEventId']);
    Route::post('/', [TicketController::class, 'store']);
    Route::put('/{id}', [TicketController::class, 'update']);
    Route::delete('/{id}', [TicketController::class, 'destroy']);
    Route::post('/bulkstore', [TicketController::class, 'bulkStore']);

});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('actions')->group(function () {
    Route::get('/', [ActionController::class, 'index']);
    Route::post('/', [ActionController::class, 'store']);
    Route::get('/{id}', [ActionController::class, 'show']);
    Route::put('/{id}', [ActionController::class, 'update']);
    Route::post('/batch-destroy', [ActionController::class, 'batchDestroy']);
});
Route::prefix('seatmaps')->group(function () {
    Route::get('/', [SeatmapController::class, 'index']);
    Route::get('/{id}', [SeatmapController::class, 'show']);
    Route::post('/', [SeatmapController::class, 'store']);
    Route::get('/event/{id}', [SeatmapController::class, 'getSeatMapByEventId']);
});
Route::post('/products/transfer', [ProductController::class, 'transfer']);
Route::post('/customers', [CustomerController::class, 'store']);
Route::post('/products/bulkstore', [ProductController::class, 'bulkStore']);
Route::post('/invoices', [InvoiceController::class, 'store']);
Route::post('/customer-by-email', [CustomerController::class, 'getByEmailWithInvoices']);
Route::get('invoices/customer/{customer_id}', [InvoiceController::class, 'getByCustomerId']);