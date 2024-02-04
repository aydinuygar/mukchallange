<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TransactionController;



Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/user/{userId}', [UserController::class, 'getUserWithSubscriptionsAndTransactions']);


Route::post('/user/{id}/subscription', [SubscriptionController::class, 'store']);
Route::put('/user/{userId}/subscription/{subscriptionId}', [SubscriptionController::class, 'update']);
Route::delete('/user/{userId}/subscription/{subscriptionId}', [SubscriptionController::class, 'destroy']);






Route::post('/user/{userId}/transaction', [TransactionController::class, 'store']);




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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
