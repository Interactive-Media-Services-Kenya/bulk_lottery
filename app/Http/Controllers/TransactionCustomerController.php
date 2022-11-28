<?php

namespace App\Http\Controllers;

use App\Models\TransactionCustomer;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class TransactionCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role_or_permission:Admin|transaction_customers_management']);
    }
    public function index(){
        $transactions = TransactionCustomer::orderBy('created_at', 'desc')->get();
        $transactionsCustomer = $this->getCustomerTransactionStats();
        return view('transactions.customers.index',compact('transactions','transactionsCustomer'));
    }
    public function getCustomerTransactionStats()
    {
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i:s');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i:s');
        $month = Carbon::parse(Carbon::now()->today())->month;
        $year = $now->year;

        $totalTransactions = DB::table('transaction_customers')->sum('amount');
        $transactionsToday = DB::table('transaction_customers')->whereDate('created_at', Carbon::now()->today())->sum('amount');
        $transactionsWeek = DB::table('transaction_customers')->whereBetween('created_at',[$weekStartDate,$weekEndDate])->sum('amount');
        $transactionsMonth = DB::table('transaction_customers')->whereMonth('created_at',$month)->sum('amount');
        $transactionsYear = DB::table('transaction_customers')->whereYear('created_at',$year)->sum('amount');

        $statistics = [
            'totalTransactions'=> $totalTransactions,
            'transactionsToday'=> $transactionsToday,
            'transactionsWeek' => $transactionsWeek,
            'transactionsMonth'=> $transactionsMonth,
            'transactionsYear'=> $transactionsYear
        ];

        return $statistics;
    }
}
