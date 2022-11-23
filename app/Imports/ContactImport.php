<?php

namespace App\Imports;

use App\Models\Contact;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;

class ContactImport implements ToCollection,WithHeadingRow
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
        foreach ($rows as $row) {
            Contact::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'user_id' => $this->userID??null,
                'phone_book_id'=> $this->phoneBookID??null,
                'client_id' => $this->clientID??null,
            ]);
        }
    }
}
