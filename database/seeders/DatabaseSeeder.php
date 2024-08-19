<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'it@inyalakehotel.com',
            'password' => Hash::make('sayyes'),
            'created_at'=> Carbon::today(),
            'role'=> '1',

        ]);
        \DB::table('departments')->insert([
            'name' => 'IT',
            'email' => 'it@inyalakehotel.com',          
            'created_at'=> Carbon::today(),
           

        ]);
    }
}
