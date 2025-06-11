<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CheckoutApiController;

Route::middleware('api.key')->post('/checkout', [CheckoutApiController::class, 'store']);

Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});
