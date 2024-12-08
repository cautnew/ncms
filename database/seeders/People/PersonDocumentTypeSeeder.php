<?php

namespace Database\Seeders\People;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User\PersonDocumentType;

class PersonDocumentTypeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $registers = [
      [
        'name' => 'CNI',
        'description' => 'Cadastro Nacional de Identidade',
        'code' => 'CNI',
        'is_active' => true
      ],
      [
        'name' => 'CPF',
        'description' => 'Cadastro de Pessoa Física',
        'code' => 'CPF',
        'is_active' => true
      ],
      [
        'name' => 'Identidade',
        'description' => 'Registro Geral',
        'code' => 'RG',
        'is_active' => true
      ],
      [
        'name' => 'Passaporte',
        'description' => 'Número do Passaporte',
        'code' => 'PID',
        'is_active' => true
      ]
    ];

    foreach ($registers as $register) {
      PersonDocumentType::create($register);
    }
  }
}
