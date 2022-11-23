<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Admin IMS',
                'email' => 'info@ims.co.ke',
                'phone'=>'254713218312',
                'client_id'=> 1,
                'client_department_id' => 1,
                'title' => 'Admin/Software Developer',
                'first_login' => 0,
                'password'=> Hash::make('imscoke@2022'),
                'created_at' => \Carbon\Carbon::now(),
            ],

        ];
        User::insert($users);
        //$users = (array) $users; // Convert Users Object to array using type casting
        //Assign the User Admin Role
        $user = User::findOrFail(1);
        $user->assignRole('Admin');
        $user->syncPermissions(Permission::all());

    }
}
