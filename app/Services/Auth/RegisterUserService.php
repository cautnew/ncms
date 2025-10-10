<?php

namespace App\Services\Auth;

use App\Models\Users\Person;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password)
    ]);

    if (!$user) {
      return null;
    }

    $person = Person::create([
      'name' => $request->name,
      'user_id' => $user->id,
      'birthdate' => $request->birthdate,
      'gender_id' => $request->gender_id,
      //'created_by' => Auth::id() ?? $user->id
    ]);

    if (!$person) {
      $user->delete();
      return null;
    }

    return $user;
  }
}
