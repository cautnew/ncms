<?php

namespace Database\Seeders\Finance;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $user = User::findByEmail('cautnew@email.com')->first();
  }
}
