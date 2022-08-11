<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //注意：１オーナーに対して１店舗自動作成される仕様なので、ownersと1:1になることを確認すること
        DB::table('shops')->insert([
            [
                'owner_id' => 1,
                'name' => 'ショップ名1',
                'information' => 'ショップ情報1',
                'filename' => 'sample1.jpg',
                'is_selling' => true
            ],
            [
                'owner_id' => 2,
                'name' => 'ショップ名2',
                'information' => 'ショップ情報2',
                'filename' => 'sample2.jpg',
                'is_selling' => true
            ],
        ]);
    }
}
