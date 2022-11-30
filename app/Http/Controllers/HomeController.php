<?php

namespace App\Http\Controllers;

use App\Models\BulkMessage;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\UserBulkAccount;
use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $clientID, $userID;

    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->clientID = auth()->user()->client_id;
            $this->userID = auth()->user()->id;
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //dd(123);
        $dashboardStats = $this->getDashboardStats();
        $latestStats = $this->getLatestDashboardStats();
        $transactionsCustomer = $this->getCustomerTransactionStats();

        return view('home', compact('dashboardStats', 'latestStats','transactionsCustomer'));
    }

    public function getDashboardStats()
    {
        $transactions = DB::table('transactions')->whereclient_id($this->clientID)->count();
        $messages = DB::table('bulk_messages')->whereclient_id($this->clientID)->count();
        $uniqueNumbers = collect(Contact::select('phone')->whereclient_id($this->clientID)->cursor())->count();
        $accountBalance = UserBulkAccount::whereclient_id($this->clientID)->value('bulk_balance') ?? 0.00;
        $totalTransactions = DB::table('transactions')->whereclient_id($this->clientID)->sum('amount');
        $statistics = [
            'transactions' => $transactions,
            'messages' => $messages,
            'uniqueNumbers' => $uniqueNumbers,
            'accountBalance' => $accountBalance,
            'totalTransactions' => $totalTransactions,
        ];


        return $statistics;
    }

    public function getCustomerTransactionStats()
    {
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i:s');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i:s');
        $month = $now->month;
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

    public function getLatestDashboardStats()
    {
        $latestTransactions = Transaction::latest()->take(5)->get();
        $latestMessages = BulkMessage::latest()->take(5)->get();

        $latestStats = [
            'latestTransactions' => $latestTransactions,
            'latestMessages' => $latestMessages,
        ];
        return $latestStats;
    }
}
