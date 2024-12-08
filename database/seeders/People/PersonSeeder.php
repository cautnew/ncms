<?php

namespace Database\Seeders\People;

use App\Models\User;
use App\Models\User\Person;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
  private function createPerson(string $email, string $password, string $name, string $lastname, string $birthdate): void
  {
    User::create([
      'email' => $email,
      'password' => $password
    ]);
    $user = User::findByEmail($email)->first();

    Person::create([
      'user_id' => $user->id,
      'name' => $name,
      'lastname' => $lastname,
      'birthdate' => $birthdate
    ]);
  }

  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->createPerson('cautnew@email.com', 'password', 'Felipe', 'Martins', '1991-08-19');
    $this->createPerson('maria.aucerlaine@email.com', 'password', 'Maria Aucerlaine', 'Martins', '1965-11-29');
    $this->createPerson('talita@email.com', 'password', 'Talita', 'Ferraz', '1993-09-07');
    $this->createPerson('monica@email.com', 'password', 'Monica', 'Cavalcante', '1986-05-18');
    $this->createPerson('catharina@email.com', 'password', 'Catharina', 'Barbosa', '2003-10-10');
  }
}
