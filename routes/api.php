<?php

use App\Http\Controllers\Api\GetApiDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('transactions/callback/43049ffdidd/', [TransactionController::class,'callback'])->name('transactions.callback.43049ffdidd');
Route::post('transactions/callback/430498943reid3400/', [TransactionController::class,'callbackCustomers'])->name('transactions.callback.430498943reid3400');
Route::post('amount/{quantity}',[GetApiDataController::class,'getPrize'])->name('get.amount');
