<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Client;
use App\Models\UserBulkAccount;
use Illuminate\Support\Facades\Auth;
use App\Services\PrizeCalculatorService;
use DB;
use App\Models\User;
use App\Models\TransactionCustomer;

class TransactionController extends Controller
{

    public function __construct(PrizeCalculatorService $prizeCalculatorService)
    {
        $this->prizeCalculatorService = $prizeCalculatorService;
    }
    public function index()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->get();

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        // $transactions = Transaction::all();
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'phone' => 'required|integer|digits:12',
            'quantity' => 'required|integer',
        ]);
        $quantity = $request->quantity;
        $user = Auth::id();

        // $mpesa = new \Safaricom\Mpesa\Mpesa();
        // $BusinessShortCode = 174379;
        // $LipaNaMpesaPasskey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
        // $TransactionType = 'CustomerPayBillOnline';
        // $Amount = $this->prizeCalculatorService->getPrize($quantity);
        // $PartyA = $request->phone;
        // $PartyB = 174379;
        // $PhoneNumber = $request->phone;
        // $CallBackURL = route('transactions.callback.43049ffdidd',[$user,$quantity]); //Also need to pass Quantity Bought [$quantity]

        // //$CallBackURL = "https://takemyitclass.com/test/callback.php?user_id=$user&amount=$Amount";
        // $AccountReference = 'IMS EABL PAYBILL';
        // $TransactionDesc = 'Payment Bulk';
        // $Remarks = 'Bulk Lottery Payment';

        // $stkPushSimulation = $mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);





        $auth_url='https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';//replace sandbox with api for live
        $stk_push_url='https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';//replace sandbox with api for live
        $stk_push_url='https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';//replace sandbox with api for live
        $consumer_key='GGfOz4bN2uoXsTV8IZFNYRdIm3KkVkbK';
        $consumer_secret='bomyaTZXg0Iwbwzk';
        $credentials=base64_encode($consumer_key.':'.$consumer_secret);

        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$auth_url);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Authorization: Basic '.$credentials));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);

        $curl_response=curl_exec($ch);
        $access_token=json_decode($curl_response)->access_token;

        curl_setopt($ch,CURLOPT_URL,$stk_push_url);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type:application/json','Authorization:Bearer '.$access_token));

        $timestamp=date('YmdHis');
        $passkey='bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
        $shortCode=174379;
        $password=base64_encode($shortCode.$passkey.$timestamp);

        $curl_post_data=[
            'BusinessShortCode'      =>174379,
            'Password'               =>$password,
            'Timestamp'              =>$timestamp,
            'TransactionType'        =>'CustomerPayBillOnline',
            'Amount'                 =>$this->prizeCalculatorService->getPrize($quantity),
            'PartyA'                 =>$request->phone,
            'PartyB'                 =>174379,
            'PhoneNumber'            =>$request->phone,
            //'CallBackURL'            =>"https://takemyitclass.com/test/callback.php?user=$user&quantity=$quantity", //Also need to pass Quantity Bought [$quantity],?user='.$user.'&quantity='.$quantity
            'CallBackURL'            =>route('transactions.callback.43049ffdidd')."?user=$user&quantity=$quantity", //Also need to pass Quantity Bought [$quantity],?user='.$user.'&quantity='.$quantity
            'AccountReference'       =>'IMS EABL PAYBILL',
            'TransactionDesc'        =>'Bulk Lottery'

        ];

        $data_string=json_encode($curl_post_data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data_string);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $curl_res=curl_exec($ch);
        print_r($curl_res);

        if($curl_res){
            echo "<script>alert('Unlock your phone and enter your Mpesa Pin to finish the Transaction <br> Thank You');
                       window.location.href='/transactions';</script>";
        }
        return redirect()->route('transactions.index')->with('success', 'Please Complete Transaction In the Phone Number Provided');
    }

    public function callback(Request $request)
    {
        logger('Endpoint Hit');
        $stkCallbackResponse = $request->getContent();
        $data = json_decode($stkCallbackResponse);

        $user = $_GET['user'];
        $quantity = $_GET['quantity'];

            $result_desc = $data->Body->stkCallback->ResultDesc;
            $result_code = $data->Body->stkCallback->ResultCode;
            $merchant_request_id = $data->Body->stkCallback->MerchantRequestID;
            $checkout_request_id = $data->Body->stkCallback->CheckoutRequestID;
            $amount = $data->Body->stkCallback->CallbackMetadata->Item[0]->Value;
            $mpesa_receipt_number = $data->Body->stkCallback->CallbackMetadata->Item[1]->Value;
            $transaction_date = $data->Body->stkCallback->CallbackMetadata->Item[3]->Value;
            $phone_number = $data->Body->stkCallback->CallbackMetadata->Item[4]->Value;
            if ($result_code == 0){
                //Add Transaction
                Transaction::create([
                    'result_desc' => $result_desc,
                    'user_id' => $user,
                    'client_id' => User::where('id',$user)->value('client_id')??null,
                    'msisdn' => $phone_number,
                    'transaction_date' => $transaction_date,
                    'reference' => $mpesa_receipt_number,
                    'amount' => $amount,
                    'merchant_request_id' => $merchant_request_id,
                    'result_code' => $result_code,
                    'checkout_request_id' => $checkout_request_id
                ]);

                //Add Quantity
                $client_id = User::where('id',$user)->value('client_id');
                if ($client_id) {
                    //Check if user has balance
                    $userQuantity = UserBulkAccount::where('client_id',$client_id)->value('bulk_balance');
                    if ($userQuantity>0) {
                        UserBulkAccount::updateOrCreate([
                            'client_id' => $client_id
                        ],
                        [
                            'bulk_balance' => $userQuantity + $quantity
                        ]);
                    }else{
                        UserBulkAccount::updateOrCreate([
                            'client_id' => $client_id
                        ],
                        [
                            'bulk_balance' =>$quantity
                        ]);
                    }
                }
            }else{
                return exit;
            }
    }

    public function callbackCustomers(Request $request){
        logger('Endpoint Hit');
        // {"mpesa_trx_time":"20221121130531","mpesa_code":"QKL8D9FVIC_3","mpesa_amt":"50","mpesa_msisdn":"254727414475","mpesa_trx_date":"2022-11-21","":"24768431","mpesa_sender":"KELVIN ZIANA OTIENO"}
        $stkCallbackResponse = $request->getContent();
        info($stkCallbackResponse);
        // $data = json_decode($stkCallbackResponse);

            // $result_desc = $data->Body->stkCallback->ResultDesc;
            // $result_code = $data->Body->stkCallback->ResultCode;
            // $merchant_request_id = $data->Body->stkCallback->MerchantRequestID;
            // $checkout_request_id = $data->Body->stkCallback->CheckoutRequestID;
            $transaction_date = $stkCallbackResponse["mpesa_trx_time"];
            $phone_number = $stkCallbackResponse["mpesa_msisdn"];
            $amount = $stkCallbackResponse["mpesa_amt"];
            $mpesa_receipt_number = $stkCallbackResponse["mpesa_code"];

            $mpesa_transaction_date = $stkCallbackResponse["mpesa_trx_date"];
            $mpesa_acc =$stkCallbackResponse["mpesa_acc"];
            $mpesa_sender = $stkCallbackResponse["mpesa_sender"];

            //Save Transaction Data to Database
            TransactionCustomer::create([
                'mpesa_trx_time' => $transaction_date,
                'msisdn' => $phone_number,
                'transaction_date' => $transaction_date,
                'reference' => $mpesa_receipt_number,
                'amount' => $amount,
                'mpesa_transaction_date' => $mpesa_transaction_date,
                'mpesa_account' => $mpesa_acc,
                'mpesa_sender' => $mpesa_sender,
            ]);

            return response()->json([
                'status' => 200,
                'message'=> 'Payment Details Added Successfully'
            ]);
    }
}
