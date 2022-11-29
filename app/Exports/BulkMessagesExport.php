<?php

namespace App\Exports;

use App\Models\BulkMessage;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BulkMessagesExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('bulk_messages')
        ->select('originator','message','destination')->take(0)->get();
    }

    public function headings(): array
    {
        return [
            'phone_number (2547XXXXXXXX)',
            'message',
        ];
    }
}
