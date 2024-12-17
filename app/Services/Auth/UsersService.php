<?php

namespace App\Services\Auth;

use App\Models\Users\Person;
use App\Models\Users\User;

class UsersService
{
  /**
   * Create a new user.
   */
  public function createUser(array $data)
  {
    // Validate the data
    $validatedData = $this->validateUserData($data);

    // Create the user
    $user = new User();
    $user->email = $validatedData['email'];
    $user->password = bcrypt($validatedData['password']);
    $user->save();

    // Create the person
    $person = new Person();
    $person->name = $validatedData['name'];
    $person->user_id = $user->id;
    $person->save();

    return $user;
  }

  protected function validateUserData(array $data)
  {
    // Add validation logic here
    return $data;
  }
}
