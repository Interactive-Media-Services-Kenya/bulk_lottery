<?php

namespace App\Http\Controllers;

use App\Models\BulkResponse;
use Illuminate\Http\Request;
use App\Models\BulkMessage;
use App\Models\BulkStatus;
use DB;
use Yajra\DataTables\Facades\DataTables;

class BulkResponseController extends Controller
{
    public $clientID;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->clientID = auth()->user()->client_id;
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        try {
            DB::connection('mysql2')->getPdo();
        } catch (\Exception $e) {
            abort(500);
        }
        if ($request->ajax()) {
            $query = BulkMessage::with(['senderName','bulkResponse.bulkStatus'])->whereclient_id($this->clientID);

            $table = Datatables::of($query);


            $table->editColumn('destination', function ($row) {
                return  substr($row->destination, 0, 5) . '*****' . substr($row->destination, -2)?? '';
            });
            $table->editColumn('message', function ($row) {
                return \Str::of($row->message)->words(15, ' ...') ?? '' ;
            });
            $table->editColumn('senderName', function ($row) {
                return $row->senderName->short_code ?? 'No Name';
            });
            $table->editColumn('status', function ($row) {
                // if (empty($row->bulkResponse->bulkStatus)) {
                //     return  '<td class="text-center"><a href="#" class="btn btn-sm btn-info">No Status</a></td>';
                // }
                $status = DB::connection('mysql2')->table('dlr_bulk')->wherecorrelator($row->bulkResponse->correlator)->value('deliverystatus');

                if($status == 'DeliveredToTerminal'){
                    return '<td class="text-center"><a href="#" class="btn btn-sm btn-success">Success</a></td>';
                }elseif ($status == 'SenderName Blacklisted'||$status == 'DeliveryImpossible'||$status == 'AbsentSubscriber'||$status == 'InvalidMsisdn') {
                    return '<td class="text-center"><a href="#" class="btn btn-sm btn-danger">Failed</a></td>';
                }else{
                    return '<td class="text-center"><a href="#" class="btn btn-sm btn-warning">Pending</a></td>';
                }
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ?? '';
            });

            $table->rawColumns(['message','destination','status','senderName','created_at']);

            return $table->make(true);
        }



        return view('messages.message.delivery.index');
    }
    public function indexData(Request $request)
    {
        try {
            DB::connection('mysql2')->getPdo();
        } catch (\Exception $e) {
            abort(500);
        }
        if ($request->ajax()) {
            $query = BulkMessage::with(['senderName','bulkResponse.bulkStatus'])->whereclient_id($this->clientID);

            $table = Datatables::of($query);


            $table->editColumn('destination', function ($row) {
                return  substr($row->destination, 0, 5) . '*****' . substr($row->destination, -2)?? '';
            });
            $table->editColumn('message', function ($row) {
                return \Str::of($row->message)->words(15, ' ...') ?? '' ;
            });
            $table->editColumn('senderName', function ($row) {
                return $row->senderName->short_code ?? 'No Name';
            });
            $table->editColumn('status', function ($row) {
                // if (empty($row->bulkResponse->bulkStatus)) {
                //     return  '<td class="text-center"><a href="#" class="btn btn-sm btn-info">No Status</a></td>';
                // }
                $status = DB::connection('mysql2')->table('dlr_bulk')->wherecorrelator($row->bulkResponse->correlator)->value('deliverystatus');

                if($status == 'DeliveredToTerminal'){
                    return '<td class="text-center"><a href="#" class="btn btn-sm btn-success">Success</a></td>';
                }elseif ($status == 'SenderName Blacklisted'||$status == 'DeliveryImpossible'||$status == 'AbsentSubscriber'||$status == 'InvalidMsisdn') {
                    return '<td class="text-center"><a href="#" class="btn btn-sm btn-danger">Failed</a></td>';
                }else{
                    return '<td class="text-center"><a href="#" class="btn btn-sm btn-warning">Pending</a></td>';
                }
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ?? '';
            });

            $table->rawColumns(['message','destination','status','senderName','created_at']);

            return $table->make(true);
        }

    }
}
