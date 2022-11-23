<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\ClientDepartment;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendRegistration;
use Illuminate\Support\Facades\Mail;
use Session;

class UserController extends Controller
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
    public function index()
    {
        //Users for admin access
        $users = User::whereclient_id($this->clientID)->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Admin Access
        $clients = Client::all();
        $departments = ClientDepartment::whereclient_id($this->clientID)->get();
        $user = User::findOrFail($this->userID);
        $permissions = $user->permissions;

        return view('users.create', compact('clients', 'departments', 'permissions'));
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|unique:users|max:255',
            'phone' => 'required|integer|digits:12|unique:users',
            'title' => 'required|string|max:255',
        ]);

        $password = $this->generatePassword();

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email ?? null,
            'title' => $request->title ?? null,
            'client_id' => $this->clientID ?? null,
            'client_department_id' => $request->client_department_id ?? null,
            'password' => Hash::make($password)
        ]);

        $user->syncPermissions($request->permission_id);

        //Send Login Details to User
        $loginUrl = route('login');
        $message = 'Hello! Greatings from ' . env('APP_NAME') . '. You have been assigned an account on our platform. Kindly Use the following details to login to @' . $loginUrl . ' Email:' . $request->email . ' Password: ' . $password;
        $details = [
            'subject' => 'Registration Details',
            'body' => $message,
        ];

        Mail::to($request->email)->send(new SendRegistration($details));
        return redirect()->route('users.index')->with('success', 'User Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $departments = ClientDepartment::whereclient_id($this->clientID)->get();
        $user = User::findOrFail($id);
        $permissions = $user->permissions;
        return view('users.edit', compact('user', 'departments','permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone' => 'required|integer|digits:12',
            'title' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email ?? null,
            'title' => $request->title ?? null,
            'client_id' => $this->clientID ?? null,
            'client_department_id' => $request->client_department_id ?? null,
        ]);

        return redirect()->route('users.index')->with('success', 'Users Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return back()->with('success', 'User deleted successfully');
    }

    public function generatePassword()
    {
        $permitted_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890!@#$%^&*().';

        $password =  substr(str_shuffle($permitted_chars), 0, 8);

        return $password;
    }

    public function createPassword()
    {
        return view('users.create_password');
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8|string|confirmed'
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'password' => Hash::make($request->password),
            'first_login' => 0 //Change first time login value to 0
        ]);
        Session::put('user_first_login', auth()->user()->id);

        return redirect()->route('home')->with('success', 'Passwords updated successfully');
    }
}
