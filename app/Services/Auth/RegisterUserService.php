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
  private static User $user;
  private static Person $person;

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
      'gender_id' => 'required|int',
    ]);

    self::$user = User::create([
      'name' => $requestData['name'],
      'email' => $requestData['email'],
      'password' => Hash::make($requestData['password']),
      'service_name' => 'kautchcms'
    ]);

    if (!self::$user) {
      return null;
    }

    self::$person = Person::create([
      'name' => $requestData['name'],
      'user_id' => self::$user->id,
      'birthdate' => $requestData['birthdate'],
      'gender_id' => $requestData['gender_id'],
    ]);

    if (!self::$person) {
      self::$user->delete();
      return null;
    }

    return self::$user;
  }

  /**
   * Provide registration data and the user and the person will
   * be created linked to each other and so the user can login.
   * @param \Illuminate\Http\Request $request
   * @return User|null
   */
  public static function register_for_test(): ?User
  {
    $request = new Request([
      'name' => fake()->name(),
      'email' => fake()->email(),
      'password' => 'password',
      'birthdate' => fake()->date(),
      'gender_id' => fake()->numberBetween(1, 2),
    ]);

    $requestData = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|string|min:8',
      'birthdate' => 'required|date',
      'gender_id' => 'required|int',
    ]);

    self::$user = User::create([
      'name' => $requestData['name'],
      'email' => $requestData['email'],
      'password' => Hash::make($requestData['password']),
      'service_name' => 'kautchcms',
      'is_test' => true,
    ]);

    if (!self::$user) {
      return null;
    }

    self::$person = Person::create([
      'name' => $requestData['name'],
      'user_id' => self::$user->id,
      'birthdate' => $requestData['birthdate'],
      'gender_id' => $requestData['gender_id'],
      'is_test' => true,
    ]);

    if (!self::$person) {
      self::$user->delete();
      return null;
    }

    return self::$user;
  }
}
