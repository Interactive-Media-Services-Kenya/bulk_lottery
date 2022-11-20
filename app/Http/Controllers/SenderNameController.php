<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SenderName;
use App\Models\Client;

class SenderNameController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Admin', 'permission:sender_name_management']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sendernames = SenderName::all();
        return view('sendernames.index', compact('sendernames'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();

        return view('sendernames.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'short_code' => 'required|max:30',
        ]);

        $senderName = SenderName::create([
            'short_code' => $request->short_code,
            'sdpaccesscode' => $request->sdpaccesscode,
            'sdpserviceid' => $request->sdpserviceid,
            'spid' => $request->spid,
            'serviceprovider' => $request->serviceprovider,
            'client_id' => $request->client_id,
        ]);

        return redirect()->route('sendernames.index')->with('success', 'Sender Name ' . $senderName->short_code . ' Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SenderName $senderName)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SenderName $senderName)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SenderName $senderName)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($senderNameid)
    {
        $senderName = SenderName::findOrFail($senderNameid);
        $senderName->delete();

        return back()->with('success', 'Sender Name has been successfully deleted.');
    }
}
