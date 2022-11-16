<?php

namespace App\Imports;

use App\Models\BulkMessage;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
use App\Models\SenderName;
use App\Models\UserBulkAccount;
use App\Services\BulkMessageService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BulkMessagesImport implements ToCollection,WithHeadingRow
{
    protected $client_id;
    protected $brand_id;
    protected $campaign_id;
    protected $sender_id;
    protected $bulkMessageService;

    public function __construct($client_id, $brand_id,$campaign_id,$sender_id,BulkMessageService $bulkMessageService)
    {
        $this->client_id = $client_id;
        $this->brand_id = $brand_id;
        $this->campaign_id = $campaign_id;
        $this->sender_id = $sender_id;
        $this->bulkMessageService = $bulkMessageService;
    }
    // /**
    // * @param array $row
    // *
    // * @return \Illuminate\Database\Eloquent\Model|null
    // */
    // public function model(array $row)
    // {
    //     return new BulkMessage([
    //         "message" => $row['message'],
    //         "destination" => $row['phone'],
    //         "brand_id" => $this->brand_id??'',
    //         "client_id" => $this->client_id??'',
    //         "campaign_id" => $this->campaign_id??'',
    //         "sender_id" => $this->sender_id??'',
    //     ]);
    // }
    public function collection(Collection $rows)
    {
        //get Account Balance
        $accountBulkBalance = UserBulkAccount::whereclient_id($this->client_id)->value('bulk_balance');

        if ($rows->count() > $accountBulkBalance) {
            return redirect()->route('messages.index')->with('error','Insufficient Bulk Units! Kindly Topup Your Bulk Balance to Continue');
        }
        foreach ($rows as $row)
        {
            $senderName = SenderName::whereid($this->sender_id)->value('short_code');
            $message = $row['message'];
            $phoneNumber = $row['phone'];
            $this->bulkMessageService->sendBulk($senderName,$message,$phoneNumber);
            BulkMessage::create([
                "message" => $row['message'],
                "destination" => $row['phone'],
                "brand_id" => $this->brand_id??'',
                "client_id" => $this->client_id??'',
                "campaign_id" => $this->campaign_id??'',
                "sender_id" => $this->sender_id??'',
            ]);
            //Insert Data into Messages Outgoing Into smsservices table
            // DB::connection('mysql2')->DB::table('messages_outgoing')->insert([
            //     'destination' => $row['phone'],
            //     'message' => $row['message'],
            //     'proccessed'=> 2,
            //     'originator' => SenderName::whereid($this->sender_id)->value('sdpserviceid'),
            // ]);

        }
        //Update Bulk Balance if successfully imported

        UserBulkAccount::whereclient_id($this->client_id)->update([
                'bulk_balance' => $accountBulkBalance - $rows->count()
        ]);

        return redirect()->route('messages.index')->with('success','Messages Imported Successfully');
    }
    //Insert Data into Messages Outgoing Into smsservices table
    public function addMessage($message,$sender_id){
        return DB::connection('mysql2')->DB::table('messages_outgoing')->insert([
            'destination' => $message['phone'],
            'message' => $message['message'],
            'proccessed'=> 2,
            'originator' => SenderName::whereid($sender_id)->value('sdpserviceid'),
        ]);
    }
}
