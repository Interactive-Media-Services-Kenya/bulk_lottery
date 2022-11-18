<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DataFetchController;
use App\Http\Controllers\BulkMessageController;
use App\Http\Controllers\SenderNameController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PhoneBookController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TransactionCustomerController;
use App\Http\Controllers\UserController;
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
    Route::get('clients/departments/create',[ClientController::class, 'createDepartment'])->name('clients.departments.create');
    Route::get('clients/departments/index',[ClientController::class, 'departments'])->name('clients.departments.index');
    Route::get('clients/departments/{id}/edit',[ClientController::class, 'editDepartment'])->name('clients.departments.edit');
    Route::put('clients/departments/update/{id}',[ClientController::class, 'updateDepartment'])->name('clients.departments.update');
    Route::delete('clients/departments/delete/{id}',[ClientController::class, 'destroyDepartment'])->name('clients.departments.destroy');
    Route::post('clients/departments/store',[ClientController::class, 'storeDepartment'])->name('clients.departments.store');
    Route::resource('clients', ClientController::class);
    Route::resource('campaigns', CampaignController::class);
    Route::resource('brands', BrandController::class);
    //Data Fetch
    Route::post('fetch/brands',[DataFetchController::class, 'fetchBrands'])->name('fetch.brands');
    Route::post('fetch/campaigns',[DataFetchController::class, 'fetchCampaigns'])->name('fetch.campaigns');
    Route::post('fetch/sendernames',[DataFetchController::class, 'fetchSenderNames'])->name('fetch.sender-names');

    //Messages Routes
    Route::post('messages',[BulkMessageController::class,'store'])->name('messages.store');
    Route::post('messages/delete',[BulkMessageController::class, 'delete'])->name('messages.delete');
    Route::post('messages/update/{id}',[BulkMessageController::class, 'update'])->name('messages.update');
    Route::get('messages/{id}/edit',[BulkMessageController::class, 'edit'])->name('messages.edit');
    Route::get('export/get-bulk-messages-excel',[BulkMessageController::class, 'export'])->name('export.get-bulk-messages-excel');
    Route::get('messages',[BulkMessageController::class, 'index'])->name('messages.index');
    Route::get('messages/create',[BulkMessageController::class, 'create'])->name('messages.create');


    //PhoneBook Routes For Sending Messages
    Route::get('messages/phonebook/create',[BulkMessageController::class, 'createPhoneBook'])->name('messages.phonebooks.create');
    Route::post('messages/phonebook/create',[BulkMessageController::class, 'storePhoneBook'])->name('messages.phonebooks.store');
    //Quick Send
    Route::get('messages/message/quicksend/create',[BulkMessageController::class, 'createQuicksend'])->name('messages.message.quicksend');
    Route::post('messages/message/quicksend/store',[BulkMessageController::class, 'storeQuickSend'])->name('messages.message.quicksend.store');
    Route::get('messages/phonebook/message/create',[BulkMessageController::class, 'messagePhoneBook'])->name('messages.message.phonebook.create');
    Route::post('messages/phonebook/message/create',[BulkMessageController::class, 'storeMessagePhoneBook'])->name('messages.message.phonebook.store');

    //Sender Names
    Route::get('sendernames',[SenderNameController::class,'index'])->name('sendernames.index');
    Route::get('sendernames/create',[SenderNameController::class,'create'])->name('sendernames.create');
    Route::post('sendernames/store',[SenderNameController::class,'store'])->name('sendernames.store');
    Route::get('sendernames/{id}/edit',[SenderNameController::class,'edit'])->name('sendernames.edit');
    Route::post('sendernames/update/{id}',[SenderNameController::class,'update'])->name('sendernames.update');
    Route::delete('sendernames/destroy/{id}',[SenderNameController::class,'destroy'])->name('sendernames.destroy');


    //Transactions
    Route::get('transactions',[TransactionController::class,'index'])->name('transactions.index');
    Route::get('transactions/create',[TransactionController::class,'create'])->name('transactions.create');
    Route::post('transactions/store',[TransactionController::class,'store'])->name('transactions.store');

    //Customer Transactions
    Route::get('transactions/customers/index',[TransactionCustomerController::class,'index'])->name('transactions.customers.index');

    //Contacts & PhoneBook
    Route::get('contacts/blacklists/index',[ContactController::class,'blacklists'])->name('contacts.blacklists.index');
    Route::get('contacts/blacklists/create',[ContactController::class,'createBlacklists'])->name('contacts.blacklists.create');
    Route::post('contacts/blacklists/index',[ContactController::class,'storeBlacklists'])->name('contacts.blacklists.store');
    Route::delete('contacts/blacklists/destroy/{id}',[ContactController::class,'destroyBlacklists'])->name('contacts.blacklists.destroy');
    Route::resource('contacts', ContactController::class);
    Route::resource('phonebooks', PhoneBookController::class);

    //Users
    Route::resource('users', UserController::class);
});

