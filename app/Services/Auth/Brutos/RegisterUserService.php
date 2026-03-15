<?php

namespace App\Services\Auth\Brutos;

use App\Models\Users\Person;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class with a method to create a user and the person linked
 * to it so the user can login.
 */
class RegisterUserService
{
  /**
   * Provide registration data and the user and the person will
   * be created linked to each other and so the user can login.
   * @param \Illuminate\Http\Request $request
   * @return User|null
   */
  public static function register(Request $request): ?User
  {
    $requestData = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|string|min:8',
      'birthdate' => 'required|date',
    ]);

    $user = User::create([
      'name' => $requestData['name'],
      'email' => $requestData['email'],
      'password' => Hash::make($requestData['password']),
      'sevice_name' => 'brutosapp'
    ]);

    if (!$user) {
      return null;
    }

    $person = Person::create([
      'name' => $requestData['name'],
      'user_id' => $user->id,
      'birthdate' => $requestData['birthdate'],
      'gender_id' => $requestData['gender_id'],
    ]);

    if (!$person) {
      $user->delete();
      return null;
    }

    return $user;
  }
}
