<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class ContactExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('contacts')
        ->select('name','phone','email')->take(0)->get();
    }

    public function headings(): array
    {
        return [
            'name',
            'phone',
            'email',
        ];
    }
}
