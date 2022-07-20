<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'name' => 'admintest1',
            'email' => 'admintest1@test.com',
            'password' => Hash::make('admintest1'),
            'created_at' => '2022/07/20 11:11:11'
        ]);
    }
}
