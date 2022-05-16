<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestUserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('users')->insert(['id' => 100001, 'name' => 'guest1', 'email' => 'guest1@guest__1.com', 'password' => bcrypt('password')]);
    DB::table('users')->insert(['id' => 100002, 'name' => 'guest2', 'email' => 'guest2@guest__2.com', 'password' => bcrypt('password')]);
    DB::table('teams')->insert(['user1_id' => 100001, 'user2_id' => 100002, 'status' => 'approve']);
  }
}