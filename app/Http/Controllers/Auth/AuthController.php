<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  public function login(LoginRequest $request)
  {
    $credentials = $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    try {
      $login = Auth::attempt($credentials, $request->remember);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'User could not login.',
        'error' => $e->getMessage(),
        'error-code' => $e->getCode(),
      ], 500);
    }

    if (! $login) {
      return response()->json(['message' => 'Invalid credentials.'], 401);
    }

    $user = User::where('email', $request->email)->first();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json(['token' => $token]);
  }

  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/')->with('success', 'You have been logged out.');
  }
}
