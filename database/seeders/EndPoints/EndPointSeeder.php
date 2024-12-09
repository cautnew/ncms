<?php

namespace Database\Seeders\EndPoints;

use App\Models\EndPoints\EndPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EndPointSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    EndPoint::factory(10)->create();
  }
}
