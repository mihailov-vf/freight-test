<?php

use App\Http\Controllers\Api\MetricsController;
use App\Http\Controllers\Api\QuoteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/quote', QuoteController::class);
Route::get('/metrics', MetricsController::class);

Route::fallback(function () {
    abort(404, 'API resource not found');
});
