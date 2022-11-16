<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DataFetchController;
use App\Http\Controllers\BulkMessageController;
use App\Http\Controllers\SenderNameController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (!\Auth::check()) {
       return view('auth.login');
    }else{
        return redirect()->route('home');
    }
});

Auth::routes();

Route::get('otp', [App\Http\Controllers\OTPController::class,'index'])->name('otp.index');
Route::post('otp', [App\Http\Controllers\OTPController::class,'store'])->name('otp.post');
Route::get('otp/reset', [App\Http\Controllers\OTPController::class,'resend'])->name('otp.resend');

Route::group(['middleware' => ['auth:web','otp']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('clients', ClientController::class);
    Route::resource('campaigns', CampaignController::class);
    Route::resource('brands', BrandController::class);
    Route::post('fetch/brands',[DataFetchController::class, 'fetchBrands'])->name('fetch.brands');
    Route::post('fetch/campaigns',[DataFetchController::class, 'fetchCampaigns'])->name('fetch.campaigns');
    Route::post('fetch/sendernames',[DataFetchController::class, 'fetchSenderNames'])->name('fetch.sender-names');
    Route::get('export/get-bulk-messages-excel',[BulkMessageController::class, 'export'])->name('export.get-bulk-messages-excel');
    Route::get('messages',[BulkMessageController::class, 'index'])->name('messages.index');
    Route::get('messages/create',[BulkMessageController::class, 'create'])->name('messages.create');
    Route::post('messages',[BulkMessageController::class,'store'])->name('messages.store');
    Route::post('messages/delete',[BulkMessageController::class, 'delete'])->name('messages.delete');
    Route::post('messages/update/{id}',[BulkMessageController::class, 'update'])->name('messages.update');
    Route::get('messages/{id}/edit',[BulkMessageController::class, 'edit'])->name('messages.edit');
    Route::get('sendernames',[SenderNameController::class,'index'])->name('sendernames.index');
    Route::get('sendernames/create',[SenderNameController::class,'create'])->name('sendernames.create');
    Route::post('sendernames/store',[SenderNameController::class,'store'])->name('sendernames.store');
    Route::get('sendernames/{id}/edit',[SenderNameController::class,'edit'])->name('sendernames.edit');
    Route::post('sendernames/update/{id}',[SenderNameController::class,'update'])->name('sendernames.update');
    Route::delete('sendernames/destroy/{id}',[SenderNameController::class,'destroy'])->name('sendernames.destroy');
    Route::get('transactions',[TransactionController::class,'index'])->name('transactions.index');
    Route::get('transactions/create',[TransactionController::class,'create'])->name('transactions.create');
    Route::post('transactions/store',[TransactionController::class,'store'])->name('transactions.store');

});

