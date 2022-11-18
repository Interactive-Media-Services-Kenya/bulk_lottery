<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientDepartment;
use Illuminate\Http\Request;

class ClientController extends Controller
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
    public function index(){
        $clients = Client::all();
        return view('clients.index',compact('clients'));
    }
    public function create(){
        return view('clients.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' =>'required|string|max:255',
            'email' =>'required|string|email|unique:clients,email',
            'phone'=> 'required|integer|digits:12',
        ]);

        $client = Client::create($request->all());

        return redirect()->route('clients.index')->with('success','Client Added Successfully!');
    }

    public function show(Client $client){
        return view('clients.show',compact('client'));
    }
    public function edit(Client $client){
        return view('clients.edit',compact('client'));
    }
    public function update(Request $request, Client $client){
        $request->validate([
            'name' =>'required|string|max:255',
            'email' =>'required|string|email',
            'phone'=> 'required|integer|digits:12',
        ]);

        $client->update($request->all());
        return redirect()->route('clients.index')->with('success','Client Updated Successfully!');;
    }
    public function destroy(Client $client){
        $client->delete();
        return redirect()->route('clients.index')->with('success','Client Deleted Successfully!');
    }

    public function departments(){
        $departments = ClientDepartment::whereclient_id($this->clientID)->get();
        return view('clients.departments.index',compact('departments'));
    }

    public function createDepartment(){
        return view('clients.departments.create');
    }

    public function storeDepartment(Request $request){
        $request->validate([
            'name'=>'required|string|max:255',
        ]);

        ClientDepartment::create([
            'name'=> $request->name,
            'client_id'=>$this->clientID
        ]);

        return back()->with('success', 'Department Added Successfully');
    }

    public function editDepartment(Request $request,$id){

        $department = ClientDepartment::findOrFail($id);

        return view('clients.departments.edit',compact('department'));
    }

    public function updateDepartment(Request $request,$id){
        $request->validate([
            'name'=>'required|string|max:255',
        ]);
        $department = ClientDepartment::findOrFail($id);
        $department->update([
            'name'=> $request->name
        ]);
        return redirect()->route('clients.departments.index')->with('success','Department Updated Successfully');
    }

    public function destroyDepartment($id){
        $department = ClientDepartment::findOrFail($id);
        if($department->users()->count()>0){
            $department->users->map(function($user){
                return $user->update([
                    'client_department_id'=> null,
                ]);
            });
        }
        $department->delete();

        return back()->with('success', 'Departments Deleted successfully');
    }
}
