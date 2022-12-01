<?php

namespace App\Http\Controllers;

use App\Models\TransactionCustomer;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class TransactionCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role_or_permission:Admin|transaction_customers_management']);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = TransactionCustomer::orderBy('created_at', 'desc');

            $table = Datatables::of($query);


            $table->editColumn('reference', function ($row) {
                return $row->reference ? $row->reference : 'No Reference';
            });

            $table->editColumn('msisdn', function ($row) {
                return $row->msisdn ? substr($row->msisdn, 0, 5) . '*****' . substr($row->msisdn, -2) : '';
            });
            $table->editColumn('mpesa_sender', function ($row) {
                return $row->mpesa_sender ? $row->mpesa_sender : '';
            });
            $table->editColumn('mpesa_account', function ($row) {
                return $row->mpesa_account ? $row->mpesa_account : '';
            });
            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at : '';
            });

            $table->rawColumns(['reference', 'msisdn', 'mpesa_sender', 'mpesa_account', 'amount', 'created_at']);

            return $table->make(true);
        }
        $transactionsCustomer = $this->getCustomerTransactionStats();
        return view('transactions.customers.index', compact( 'transactionsCustomer'));
    }
    public function today(Request $request)
    {
        if ($request->ajax()) {
            $today =  Carbon::now()->today();
            $query = TransactionCustomer::whereDate('created_at', $today)->orderBy('created_at', 'desc');

            $table = Datatables::of($query);


            $table->editColumn('reference', function ($row) {
                return $row->reference ? $row->reference : 'No Reference';
            });

            $table->editColumn('msisdn', function ($row) {
                return $row->msisdn ? substr($row->msisdn, 0, 5) . '*****' . substr($row->msisdn, -2) : '';
            });
            $table->editColumn('mpesa_sender', function ($row) {
                return $row->mpesa_sender ? $row->mpesa_sender : '';
            });
            $table->editColumn('mpesa_account', function ($row) {
                return $row->mpesa_account ? $row->mpesa_account : '';
            });
            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at : '';
            });

            $table->rawColumns(['reference', 'msisdn', 'mpesa_sender', 'mpesa_account', 'amount', 'created_at']);

            return $table->make(true);
        }
        $transactionsCustomer = $this->getCustomerTransactionStats();
        return view('transactions.customers.today', compact( 'transactionsCustomer'));
    }
    public function week(Request $request)
    {
        if ($request->ajax()) {
            $now = Carbon::now();
            $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i:s');
            $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i:s');
            $month = Carbon::parse(Carbon::now()->today())->month;
            $query = TransactionCustomer::whereBetween('created_at', [$weekStartDate, $weekEndDate])->orderBy('created_at', 'desc');

            $table = Datatables::of($query);


            $table->editColumn('reference', function ($row) {
                return $row->reference ? $row->reference : 'No Reference';
            });

            $table->editColumn('msisdn', function ($row) {
                return $row->msisdn ? substr($row->msisdn, 0, 5) . '*****' . substr($row->msisdn, -2) : '';
            });
            $table->editColumn('mpesa_sender', function ($row) {
                return $row->mpesa_sender ? $row->mpesa_sender : '';
            });
            $table->editColumn('mpesa_account', function ($row) {
                return $row->mpesa_account ? $row->mpesa_account : '';
            });
            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at : '';
            });

            $table->rawColumns(['reference', 'msisdn', 'mpesa_sender', 'mpesa_account', 'amount', 'created_at']);

            return $table->make(true);
        }
        $transactionsCustomer = $this->getCustomerTransactionStats();
        return view('transactions.customers.week', compact('transactionsCustomer'));
    }
    public function month(Request $request)
    {
        if ($request->ajax()) {
            $month = Carbon::parse(Carbon::now()->today())->month;
            $query = TransactionCustomer::whereMonth('created_at', $month)->orderBy('created_at', 'desc');

            $table = Datatables::of($query);


            $table->editColumn('reference', function ($row) {
                return $row->reference ? $row->reference : 'No Reference';
            });

            $table->editColumn('msisdn', function ($row) {
                return $row->msisdn ? substr($row->msisdn, 0, 5) . '*****' . substr($row->msisdn, -2) : '';
            });
            $table->editColumn('mpesa_sender', function ($row) {
                return $row->mpesa_sender ? $row->mpesa_sender : '';
            });
            $table->editColumn('mpesa_account', function ($row) {
                return $row->mpesa_account ? $row->mpesa_account : '';
            });
            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at : '';
            });

            $table->rawColumns(['reference', 'msisdn', 'mpesa_sender', 'mpesa_account', 'amount', 'created_at']);

            return $table->make(true);
        }
        $transactionsCustomer = $this->getCustomerTransactionStats();
        return view('transactions.customers.month', compact( 'transactionsCustomer'));
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
        $transactionsWeek = DB::table('transaction_customers')->whereBetween('created_at', [$weekStartDate, $weekEndDate])->sum('amount');
        $transactionsMonth = DB::table('transaction_customers')->whereMonth('created_at', $month)->sum('amount');
        $transactionsYear = DB::table('transaction_customers')->whereYear('created_at', $year)->sum('amount');

        $statistics = [
            'totalTransactions' => $totalTransactions,
            'transactionsToday' => $transactionsToday,
            'transactionsWeek' => $transactionsWeek,
            'transactionsMonth' => $transactionsMonth,
            'transactionsYear' => $transactionsYear
        ];

        return $statistics;
    }
}
