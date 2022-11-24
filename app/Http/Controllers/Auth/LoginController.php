<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Services\SendSMSService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $sendSMSService;

    public function __construct(SendSMSService $sendSMSService)
    {
        $this->middleware('guest')->except('logout');
        $this->sendSMSService = $sendSMSService;
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user_id = auth()->user()->id;
            $user = User::where('id', $user_id)->first();
            $code = $this->sendSMSService->generateCode($user);
            // ? Get the user phone number
            $receiverNumber = $user->phone;
            $message = "OTP login code is " . $code;
            // ? Send the code as message
            $this->sendSMSService->sendSMS($message,$receiverNumber);
            return redirect()->route('otp.index');
        }

        return redirect("login")->withErrors('Opps! You have entered invalid credentials');
    }
}
