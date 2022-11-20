<?php

namespace Database\Seeders;

use App\Models\SenderName;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SenderNamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $senderNames = [
            [
                'short_code' => 'IMS',
                'sdpaccesscode' => '700801',
                'sdpserviceid' => '6013872000008698',
                'spid' => '601387',
                'serviceprovider' => 'SAFARICOM',
                'client_id' => 1,

            ]
        ];

        SenderName::insert($senderNames);
    }
}
