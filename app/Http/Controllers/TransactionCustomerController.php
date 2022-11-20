<?php

namespace App\Http\Controllers;

use App\Models\TransactionCustomer;
use Illuminate\Http\Request;

class TransactionCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Admin', 'permission:transaction_customers_management']);
    }
    public function index(){
        $transactions = TransactionCustomer::all();

        return view('transactions.customers.index',compact('transactions'));
    }
}
