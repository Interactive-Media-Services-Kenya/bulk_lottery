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
use App\Models\PhoneBook;
use App\Models\UserBulkAccount;
use Carbon\Carbon;
use App\Models\Contact;
use Yajra\DataTables\Facades\DataTables;
use DB;

class BulkMessageController extends Controller
{
    protected $bulkMessageService;
    public $clientID, $userID;

    public function __construct(BulkMessageService $bulkMessageService)
    {
        $this->bulkMessageService = $bulkMessageService;
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->clientID = auth()->user()->client_id;
            $this->userID = auth()->user()->id;
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //  $bulkMessages = BulkMessage::with(['senderName'])->whereclient_id($this->clientID)->get();
        //  dd($bulkMessages);
        if ($request->ajax()) {
            $query = BulkMessage::with(['senderName'])->whereclient_id($this->clientID);

            $table = Datatables::of($query);


            $table->editColumn('destination', function ($row) {
                return  substr($row->destination, 0, 5) . '*****' . substr($row->destination, -2) ?? '';
            });
            $table->editColumn('message', function ($row) {
                return \Str::of($row->message)->words(15, ' ...') ?? '';
            });
            $table->editColumn('senderName', function ($row) {
                return $row->senderName->short_code ?? 'No Name';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ?? '';
            });

            $table->rawColumns(['message', 'destination', 'senderName', 'created_at']);

            return $table->make(true);
        }
        return view('messages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::with(['brands'])->get();
        return view('messages.create', compact('clients'));
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
            'client_id' => 'required|integer',
        ]);
        //Pass Client ID and Brand IDs
        try {
            Excel::import(new BulkMessagesImport($request->client_id, $request->brand_id, $request->campaign_id, $request->sender_id, $this->bulkMessageService), $request->file);
            return redirect()->route('messages.index');
        } catch (\Throwable $th) {
            return back()->with('error', 'Messages Not Imported Successfully. Please Check on the Excel Data Imported');
            ///return back()->with('error',$th->getMessage());

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


    //PhoneBook QuickSend Message

    public function messagePhoneBook()
    {
        $phoneBooks = PhoneBook::with('contacts')->whereclient_id($this->clientID)->cursor();
        $senderNames = SenderName::whereclient_id($this->clientID)->get();
        return view('messages.message.create-phonebook', compact('phoneBooks', 'senderNames'));
    }

    public function storeMessagePhoneBook(Request $request)
    {
        $request->validate([
            'phonebook_id' => 'required|integer',
            'sender_id' => 'required|integer',
            'message' => 'required',
        ]);
        $phoneBook = PhoneBook::findOrFail($request->phonebook_id);
        $contactsCount = Contact::wherephone_book_id($request->phonebook_id)->count();
        //dd($contacts);
        if ($contactsCount == 0) {
            return back()->with('error', 'PhoneBook has No Contacts');
        }
        //Check For User Account Balance
        $accountBulkBalance = UserBulkAccount::whereclient_id($this->clientID)->value('bulk_balance');

        if ($contactsCount > $accountBulkBalance) {
            return back()->with('error', 'Insufficient Bulk Units! Kindly Topup Your Bulk Balance to Continue');
        }

        DB::beginTransaction();
        $message = filter_var($request->message, FILTER_SANITIZE_STRING);
        $senderName = SenderName::whereid($request->sender_id)->value('short_code');
        $contacts = Contact::wherephone_book_id($request->phonebook_id)->cursor();
        Contact::chunk(10000, function ($contacts) {
            $senderName = SenderName::whereid(request()->sender_id)->value('short_code');
            $message = filter_var(request()->message,FILTER_SANITIZE_STRING);
            $brandID = request()->brand_id;
            $campaignID = request()->campaign_id;
            foreach ($contacts as $contact) {
                $phone = $contact->phone;


                $bulkMessage = BulkMessage::create([
                    "message" => $message,
                    "destination" => $phone,
                    "brand_id" => $brandID ?? null,
                    "client_id" => $this->clientID ?? null,
                    "campaign_id" => $campaignID ?? null,
                    "sender_id" =>  $senderID?? null,
                ]);
             $this->bulkMessageService->sendBulk($senderName, $message, $phone, $this->clientID, $bulkMessage->id);
            }
        });
        DB::commit();

        return back()->with('success', 'Messages Sent Successfully');
    }

    public function createQuicksend()
    {
        $senderNames = SenderName::whereclient_id($this->clientID)->get();
        return view('messages.message.quicksend', compact('senderNames'));
    }

    public function storeQuickSend(Request $request)
    {
        $request->validate([
            'phone_numbers' => 'required',
            'message' => 'required',
        ]);

        //Split string to obtain individual numbers
        $phoneNumbers = $request->phone_numbers;
        $phones = collect(explode(',', $phoneNumbers));
        //Send SMS to each phone number
        $accountBulkBalance = UserBulkAccount::whereclient_id($this->clientID)->value('bulk_balance');

        if ($phones->count() > $accountBulkBalance) {
            return back()->with('error', 'Insufficient Bulk Units! Kindly Topup Your Bulk Balance to Continue');
        }
        $senderName = SenderName::whereid($request->sender_id)->value('short_code');
        $message = filter_var($request->message, FILTER_SANITIZE_STRING);
        $senderID = $request->sender_id;
        foreach ($phones as $phone) {
            $bulkMessage = BulkMessage::create([
                "message" => $message,
                "destination" => $phone,
                "brand_id" => $request->brand_id ?? null,
                "client_id" => $this->clientID ?? null,
                "campaign_id" => $request->campaign_id ?? null,
                "sender_id" => $senderID ?? null,
            ]);
            //Send Bulk Message
            $this->bulkMessageService->sendBulk($senderName, $message, $phone, $this->clientID, $bulkMessage->id);
            // DB::connection('mysql2')->DB::table('messages_outgoing')->insert([
            //     'destination' => $row['phone'],
            //     'message' => $row['message'],
            //     'proccessed'=> 2,
            //     'originator' => SenderName::whereid($this->sender_id)->value('sdpserviceid'),
            // ]);

        }
        //Update Bulk Balance if successfully imported

        UserBulkAccount::whereclient_id($this->clientID)->update([
            'bulk_balance' => $accountBulkBalance - $phones->count()
        ]);


        return back()->with('success', 'Messages Sent Check on Delivery Status on Messages Page');
    }
}
