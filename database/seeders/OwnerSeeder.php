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
        //注意：１オーナーに対して１店舗自動作成される仕様なので、shopsと1:1になることを確認すること
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
        ]);
    }
}
