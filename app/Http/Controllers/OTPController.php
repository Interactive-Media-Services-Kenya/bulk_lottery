<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\UserCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\SendSMSService;

//use App\Utility\SendSMS;

class OTPController extends Controller
{
    protected $sendSMSService;

    public function __construct(SendSMSService $sendSMSService)
    {
        $this->sendSMSService = $sendSMSService;
    }

    public function index()
    {
        return view('auth.otp.index');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'=>'required',
        ]);

        $find = UserCode::where('user_id', auth()->user()->id)
                        ->where('code', $request->code)
                        ->where('updated_at', '>=', now()->subMinutes(2))
                        ->first();

        if (!is_null($find)) {
            Session::put('user_otp', auth()->user()->id);
            return redirect()->route('home');
        }

        return back()->with('error', 'You entered wrong code.');
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function resend()
    {
            $user = User::where('id',Auth::id())->first();
            $code = $this->sendSMSService->generateCode($user);
            // ? Get the user phone number

            $receiverNumber = $user->phone;
            $message = "OTP login code is " . $code;
            // ? Send the code as message
            $this->sendSMSService->sendSMS($message,$receiverNumber);

        return back()->with('success', 'We sent you code on your mobile number.');
    }
}
