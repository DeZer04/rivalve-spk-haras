<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
               'name'=>'Supervisor User',
               'email'=>'spv@itsolutionstuff.com',
               'role'=>1,
               'password'=> bcrypt('123456'),
            ],
            [
               'name'=>'hrd User',
               'email'=>'hrd@itsolutionstuff.com',
               'role'=> 2,
               'password'=> bcrypt('123456'),
            ],
            [
               'name'=>'karyawan',
               'email'=>'karyawan@itsolutionstuff.com',
               'role'=>0,
               'password'=> bcrypt('123456'),
            ],
        ];

        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
