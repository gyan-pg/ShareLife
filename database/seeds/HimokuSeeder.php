<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class himokuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // 固定
      DB::table('himokus')->insert(['type' => 'kotei', 'name' => '家賃']);
      DB::table('himokus')->insert(['type' => 'kotei', 'name' => '電気']);
      DB::table('himokus')->insert(['type' => 'kotei', 'name' => '水道']);
      DB::table('himokus')->insert(['type' => 'kotei', 'name' => 'ガス']);
      DB::table('himokus')->insert(['type' => 'kotei', 'name' => 'その他']);

      // 変動
      DB::table('himokus')->insert(['type' => 'hendo', 'name' => '食費']);
      DB::table('himokus')->insert(['type' => 'hendo', 'name' => '交際費']);
      DB::table('himokus')->insert(['type' => 'hendo', 'name' => '外食']);
      DB::table('himokus')->insert(['type' => 'hendo', 'name' => 'その他']);
    }
}