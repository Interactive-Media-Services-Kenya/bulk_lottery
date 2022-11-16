<?php

namespace App\Http\Controllers;

use App\Exports\BulkMessagesExport;
use App\Imports\BulkMessagesImport;
use App\Models\BulkMessage;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\BulkMessageService;
use App\Models\Client;
use App\Models\SenderName;

class BulkMessageController extends Controller
{
    protected $bulkMessageService;

    public function __construct(BulkMessageService $bulkMessageService)
    {
        $this->bulkMessageService = $bulkMessageService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bulkMessages = BulkMessage::all();
        return view('messages.index', compact('bulkMessages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::with(['brands'])->get();
        return view('messages.create',compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
                                'client_id' =>'required|integer',
        ]);
        //Pass Client ID and Brand IDs
        try {
            Excel::import(new BulkMessagesImport($request->client_id,$request->brand_id,$request->campaign_id,$request->sender_id,$this->bulkMessageService), $request->file);
            return redirect()->route('messages.index');
        } catch (\Throwable $th) {
           // return back()->with('error','Messages Not Imported Successfully. Please Check on the Excel Data Imported');
            return back()->with('error',$th->getMessage());

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BulkMessage  $bulkMessage
     * @return \Illuminate\Http\Response
     */
    public function show(BulkMessage $bulkMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BulkMessage  $bulkMessage
     * @return \Illuminate\Http\Response
     */
    public function edit(BulkMessage $bulkMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BulkMessage  $bulkMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BulkMessage $bulkMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BulkMessage  $bulkMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(BulkMessage $bulkMessage)
    {
        //
    }

    public function export()
    {
        return Excel::download(new BulkMessagesExport, 'bulk_messages.xlsx');
    }

}
