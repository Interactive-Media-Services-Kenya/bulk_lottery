<?php

namespace App\Http\Controllers;

use App\Exports\ContactExport;
use App\Imports\ContactImport;
use App\Models\Blacklist;
use App\Models\Contact;
use App\Models\PhoneBook;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Contact::with(['phoneBook'])->whereclient_id($this->clientID);

            $table = Datatables::of($query);

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : 'Not Assigned';
            });
            $table->editColumn('phoneBook', function ($row) {
                return $row->phoneBook->name ? $row->phoneBook->name : '';
            });
            $table->editColumn('phone', function ($row) {
                return $row->phone ? substr($row->phone, 0, 5) . '*****' . substr($row->phone, -2) : '';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at : '';
            });
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                $view = 0;
                $edit = 0;
                $delete = 1;
                $routePart = 'contacts';

                return view('layouts.partials.utilities.datatablesActions', compact(
                    'view',
                    'edit',
                    'delete',
                    'routePart',
                    'row'
                ));
            });

            $table->rawColumns(['name', 'phoneBook', 'phone', 'created_at', 'actions']);

            return $table->make(true);
        }
        return view('contacts.index');
        // $contacts = Contact::whereclient_id($this->clientID)->get();

        // return view('contacts.index', compact('contacts'));
    }

    public function blacklists()
    {
        $contacts = Blacklist::whereclient_id($this->clientID)->get();

        return view('contacts.blacklists.index', compact('contacts'));
    }

    public function createBlacklists()
    {
        return view('contacts.blacklists.create');
    }

    public function storeBlacklists(Request $request)
    {
        $request->validate([
            'phone' => 'required|integer|digits:12',
        ]);

        $contact = Contact::wherephone($request->phone)->whereclient_id($this->clientID)->first();

        if ($contact) {
            Blacklist::create([
                'phone' => $request->phone,
                'client_id' => $this->clientID,
                'contact_id' => $contact->id,
            ]);
        } else {
            Blacklist::create([
                'phone' => $request->phone,
                'client_id' => $this->clientID ?? null,
            ]);
        }

        return redirect()->route('contacts.blacklists.index')->with('success', 'Contact Added to Blacklist');
    }

    public function destroyBlacklists($id)
    {
        $blacklist = Blacklist::findOrFail($id);

        $blacklist->delete();

        return redirect()->route('contacts.blacklists.index')->with('success', 'Contact Deleted from Blacklist');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $phoneBooks = PhoneBook::whereclient_id($this->clientID)->get();

        return view('contacts.create', compact('phoneBooks'));
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
            'name' => 'required|string|max:100',
            'phone' => 'required|integer|digits:12'
        ]);

        Contact::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'phone_book_id' => $request->phone_book_id ?? null,
            'email' => $request->email ?? null,
            'client_id' => $this->clientID ?? null,
            'user_id' => $this->userID ?? null,
        ]);


        return redirect()->route('contacts.index')->with('success', 'Contact created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        $phoneBooks = PhoneBook::whereclient_id($this->clientID)->get();
        return view('contacts.edit', compact('contact', 'phoneBooks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|integer|digits:12'
        ]);
        $contact = Contact::findOrFail($id);

        $contact->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'phone_book_id' => $request->phone_book_id ?? null,
            'email' => $request->email ?? null,
        ]);

        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Contact Deleted Successfully');
    }


    //Get PhoneBook Smple Import FIle
    public function getImportPhoneBook()
    {
        return Excel::download(new ContactExport, 'importPhoneBookContacts.xlsx');
    }

    public function createImportPhoneBook()
    {
        $phoneBooks = PhoneBook::whereclient_id($this->clientID)->get();

        return view('contacts.imports.create', compact('phoneBooks'));
    }

    public function storeImportPhoneBook(Request $request)
    {
        $this->validate($request, [
            'phone_book_id' => 'required|integer',
        ]);
        //Pass PhoneBookID
        try {
            Excel::import(new ContactImport($request->phone_book_id), $request->file);
            return redirect()->route('contacts.index');
        } catch (\Illuminate\Validation\ValidationException $th) {
            return back()->with('error', 'Check for Missing Phone Contacts Format in Document. Use Format 2547XXXXXXXX');
        } catch (\Exception $e) {
            return back()->with('error', 'Contacts Not Imported To PhoneBook. Check Your Document Formating => ' . $e);
        }
    }
}
