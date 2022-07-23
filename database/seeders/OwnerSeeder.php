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
            [
                'name' => 'ownertest1',
                'email' => 'ownertest1@test.com',
                'password' => Hash::make('ownertest1'),
                'created_at' => '2022/07/20 11:11:11'
            ],
            [
                'name' => 'ownertest2',
                'email' => 'ownertest2@test.com',
                'password' => Hash::make('ownertest2'),
                'created_at' => '2022/07/20 11:11:11'
            ],
            [
                'name' => 'ownertest3',
                'email' => 'ownertest3@test.com',
                'password' => Hash::make('ownertest3'),
                'created_at' => '2022/07/20 11:11:11'
            ],
            [
                'name' => 'ownertest4',
                'email' => 'ownertest4@test.com',
                'password' => Hash::make('ownertest4'),
                'created_at' => '2022/07/20 11:11:11'
            ],
            [
                'name' => 'ownertest5',
                'email' => 'ownertest5@test.com',
                'password' => Hash::make('ownertest5'),
                'created_at' => '2022/07/20 11:11:11'
            ],
            [
                'name' => 'ownertest6',
                'email' => 'ownertest6@test.com',
                'password' => Hash::make('ownertest6'),
                'created_at' => '2022/07/20 11:11:11'
            ],
            [
                'name' => 'ownertest7',
                'email' => 'ownertest7@test.com',
                'password' => Hash::make('ownertest7'),
                'created_at' => '2022/07/20 11:11:11'
            ],
            [
                'name' => 'ownertest8',
                'email' => 'ownertest8@test.com',
                'password' => Hash::make('ownertest8'),
                'created_at' => '2022/07/20 11:11:11'
            ],
            [
                'name' => 'ownertest9',
                'email' => 'ownertest9@test.com',
                'password' => Hash::make('ownertest9'),
                'created_at' => '2022/07/20 11:11:11'
            ],
            [
                'name' => 'ownertest10',
                'email' => 'ownertest10@test.com',
                'password' => Hash::make('ownertest10'),
                'created_at' => '2022/07/20 11:11:11'
            ],
        ]);
    }
}
