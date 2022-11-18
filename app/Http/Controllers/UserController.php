<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\ClientDepartment;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendRegistration;
use Illuminate\Support\Facades\Mail;

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
        $users = User::all();
        return view('users.index',compact('users'));
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

        return view('users.create', compact('clients','departments'));
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
            'name'=>'required|string|max:255',
            'email'=>'required|string|unique:users|max:255',
            'phone'=>'required|integer|digits:12|unique:users',
            'title'=>'required|string|max:255',
        ]);

        $password = $this->generatePassword();

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email??null,
            'title' => $request->title?? null,
            'client_id' => $this->clientID??null,
            'client_department_id' => $request->client_department_id??null,
            'password'=> Hash::make($password)
        ]);

        //Send Login Details to User
        $loginUrl = route('login');
        $message = 'Hello! Greatings from '.env('APP_NAME'). '.
                    You have been assigned an account on our platform. Kindly Use the following details to login to @'.$loginUrl.'
                     Email:'.$request->email. ' Password: '.$password;
        $details =[
            'subject'=> 'Registration Details',
            'body'=> $message,
        ];

        Mail::to($request->email)->send(new SendRegistration($details));
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
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function generatePassword(){
        $permitted_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890!@#$%^&*().';

        $password =  substr(str_shuffle($permitted_chars), 0, 8);

        return $password;
    }
}
