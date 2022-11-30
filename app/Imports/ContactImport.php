<?php

namespace App\Imports;

use App\Models\Contact;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ContactImport implements ToCollection,WithHeadingRow, WithChunkReading,WithBatchInserts
{
    public $clientID, $userID;
    protected $phoneBookID;

    public function __construct($phoneBookID)
    {
        $this->clientID = auth()->user()->client_id;
        $this->userID = auth()->user()->id;
        $this->phoneBookID = $phoneBookID;
    }
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        // Validator::make($rows->toArray(), [
        //     'phone' => 'required|digits:12',
        // ])->validate();

        foreach ($rows as $row) {
            Contact::insert([
                'name' => $row['name']??'No Name',
                'email' => $row['email']??'No Email',
                'phone' => $row['phone_number'],
                'user_id' => $this->userID??null,
                'phone_book_id'=> $this->phoneBookID??null,
                'client_id' => $this->clientID??null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    public function batchSize(): int
    {
        return 20000;
    }

    public function chunkSize(): int
    {
        return 20000;
    }
}
