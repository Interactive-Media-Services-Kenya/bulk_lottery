<?php

namespace App\Http\Controllers;

use App\Models\BulkMessage;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\UserBulkAccount;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $dashboardStats = $this->getDashboardStats();

        $latestStats = $this->getLatestDashboardStats();
        return view('home',compact('dashboardStats','latestStats'));
    }

    public function getDashboardStats(){
        $transactions = Transaction::count();
        $messages = BulkMessage::count();
        $uniqueNumbers = collect(BulkMessage::select('destination'))->unique()->count();
        $accountBalance = UserBulkAccount::whereclient_id(auth()->user()->client_id)->value('bulk_balance')??0.00;
        $totalTransactions = DB::table('transactions')->whereclient_id(auth()->user()->client_id)->sum('amount');
        $statistics = [
            'transactions' => $transactions,
            'messages' => $messages,
            'uniqueNumbers' => $uniqueNumbers,
            'accountBalance' => $accountBalance,
            'totalTransactions' => $totalTransactions,
        ];


        return $statistics;
    }

    public function getLatestDashboardStats(){
        $latestTransactions = Transaction::latest()->take(5)->get();
        $latestMessages = BulkMessage::latest()->take(5)->get();

        $latestStats = [
            'latestTransactions' => $latestTransactions,
            'latestMessages' => $latestMessages,
        ];
        return $latestStats;
    }
}
