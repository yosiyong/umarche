<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('owners')->insert([
            'name' => 'ownertest1',
            'email' => 'ownertest1@test.com',
            'password' => Hash::make('ownertest1'),
            'created_at' => '2022/07/20 11:11:11'
        ]);

        DB::table('owners')->insert([
            'name' => 'ownertest2',
            'email' => 'ownertest2@test.com',
            'password' => Hash::make('ownertest2'),
            'created_at' => '2022/07/20 11:11:11'
        ]);

        DB::table('owners')->insert([
            'name' => 'ownertest3',
            'email' => 'ownertest3@test.com',
            'password' => Hash::make('ownertest3'),
            'created_at' => '2022/07/20 11:11:11'
        ]);
    }
}
