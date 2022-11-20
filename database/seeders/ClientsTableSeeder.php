<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = [
            [
                'name' => 'Interactive Media Services (IMS)',
                'email' => 'info@ims.co.ke',
                'phone' => '254709084000',
                'address' => 'Valley View Office Park',
                'zip' => '00100',
                'city' => 'Nairobi',
                'state' => 'Nairobi', //County
                'country' => 'KENYA',
                'status' => 1,
            ]
        ];
        Client::insert($clients);
    }
}
