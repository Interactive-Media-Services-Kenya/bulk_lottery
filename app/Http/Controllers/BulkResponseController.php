<?php

namespace App\Http\Controllers;

use App\Models\BulkResponse;
use Illuminate\Http\Request;
use App\Models\BulkMessage;
use App\Models\BulkStatus;

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
    public function index()
    {
        $bulkMessages = BulkMessage::whereclient_id($this->clientID)->get();
        // $corelators = BulkResponse::select('correlator')->whereclient_id($this->clientID)->get();
        // $bulkResponses = BulkStatus::whereIn('correlator', $corelators)->get();

        return view('messages.message.delivery.index', compact('bulkMessages'));
    }
}
