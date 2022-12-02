<?php

namespace App\Http\Controllers;

use App\Models\PhoneBook;
use Illuminate\Http\Request;
use App\Models\Contact;
use Yajra\DataTables\Facades\DataTables;

class PhoneBookController extends Controller
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

        $phoneBooks = PhoneBook::withCount(['contacts','client'])->whereclient_id($this->clientID)->cursor();
        //dd($phoneBooks);
        if ($request->ajax()) {
            $query = PhoneBook::withCount(['contacts','client'])->whereclient_id($this->clientID);

            $table = Datatables::of($query);

            $table->editColumn('name', function ($row) {
                return $row->name ?? 'Not Assigned';
            });
            $table->editColumn('contacts', function ($row) {
                return $row->contacts_count ?? '';
            });
            $table->editColumn('client', function ($row) {
                return $row->client->name ?? '';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ?? '';
            });
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                //Set the values to 1 to be viewable on display
                $view = 1;
                $edit = 1;
                $delete = 1;
                $routePart = 'phonebooks';

                return view('layouts.partials.utilities.datatablesActions', compact(
                    'view',
                    'edit',
                    'delete',
                    'routePart',
                    'row'
                ));
            });

            $table->rawColumns(['name','contacts', 'client', 'created_at', 'actions']);

            return $table->make(true);
        }
        return view('phonebooks.index', compact('phoneBooks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('phonebooks.create');
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
            'name' => 'required|string|max:50',
        ]);

        PhoneBook::create([
            'name' => $request->name,
            'client_id' => $this->clientID ?? null,
            'user_id' => $this->userID ?? null,
        ]);

        return redirect()->route('phonebooks.index')->with('success', 'Phonebooks created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PhoneBook  $phoneBook
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $phoneBook = PhoneBook::findOrFail($id);
        if ($request->ajax()) {
            $query = Contact::wherephone_book_id($id);

            $table = Datatables::of($query);


            $table->editColumn('name', function ($row) {
                return $row->name ?? 'No Name';
            });
            $table->editColumn('phone', function ($row) {
                return  substr($row->phone, 0, 5) . '*****' . substr($row->phone, -2)?? '';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ?? '';
            });
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                //Set the values to 1 to be viewable on display
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

            $table->rawColumns(['name','phone','created_at', 'actions']);

            return $table->make(true);
        }

        return view('phonebooks.show', compact('phoneBook'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PhoneBook  $phoneBook
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $phoneBook = PhoneBook::findOrFail($id);
        return view('phonebooks.edit', compact('phoneBook'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PhoneBook  $phoneBook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:70',
        ]);
        $phoneBook = PhoneBook::findOrFail($id);
        //Check if phone book has contacts then update by removing the phoneBook attached to each associated Contacts
        if ($phoneBook->contacts()->count() > 0) {
            $phoneBook->contacts->map(function ($contact) {
                return $contact->update([
                    'phone_book_id' => null
                ]);
            });
        } else {
            $phoneBook->update([
                'name' => $request->name,
            ]);

            return redirect()->route('phonebooks.index')->with('success', 'Phonebook updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PhoneBook  $phoneBook
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $phoneBook = PhoneBook::findOrFail($id);
        //Check if phone book has contacts then update by removing the phoneBook attached to each associated Contacts
        if ($phoneBook->contacts()->count() > 0) {
            $phoneBook->contacts->map(function ($contact) {
                return $contact->update([
                    'phone_book_id' => null
                ]);
            });
        }

        $phoneBook->delete();

        return redirect()->route('phonebooks.index')->with('success', 'PhoneBook has been deleted');
    }
}
