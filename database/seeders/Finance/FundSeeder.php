<?php

namespace Database\Seeders\Finance;

use App\Models\Finance\Fund;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Users\User;
use Illuminate\Database\Seeder;

class FundSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $user = User::findByEmail('cautnew@email.com')->first();
    Fund::create([
      'user_id' => $user->id,
      'name' => 'Itau',
      'description' => 'Itaú account',
      'balance' => 2000.00
    ]);
    Fund::create([
      'user_id' => $user->id,
      'name' => 'Iti',
      'description' => 'Iti account',
      'balance' => 1500.00
    ]);
    Fund::create([
      'user_id' => $user->id,
      'name' => 'Nubank',
      'description' => 'Nubank account',
      'balance' => 2800.00
    ]);

    $user = User::findByEmail('monica@email.com')->first();
    Fund::create([
      'user_id' => $user->id,
      'name' => 'Itau',
      'description' => 'Itaú account',
      'balance' => 1000.00
    ]);
    Fund::create([
      'user_id' => $user->id,
      'name' => 'Iti',
      'description' => 'Iti account',
      'balance' => 3500.00
    ]);
    Fund::create([
      'user_id' => $user->id,
      'name' => 'Nubank',
      'description' => 'Nubank account',
      'balance' => 5800.00
    ]);
  }
}
