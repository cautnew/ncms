<?php

namespace App\Http\Middleware;

use App\Models\User\Person;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
  /**
   * The root template that is loaded on the first page visit.
   *
   * @var string
   */
  protected $rootView = 'app';

  /**
   * Determine the current asset version.
   */
  public function version(Request $request): string|null
  {
    return parent::version($request);
  }

  /**
   * Define the props that are shared by default.
   *
   * @return array<string, mixed>
   */
  public function share(Request $request): array
  {
    $sharedResponse = [
      'auth' => [
        'user' => null,
        'person' => null
      ],
    ];

    $user = $request->user();

    if (!$user) {
      return $sharedResponse;
    }

    $authResponse = &$sharedResponse['auth'];
    $authResponse['user'] = $user->only('email');

    $person = Person::findByUserId($request->user()->id)->first();

    if ($person) {
      $authResponse['person'] = $person->only('name') ?? null;
    }

    if (session('isJustLoggedIn')) {
      $authResponse['isJustLoggedIn'] = true;
    }

    $sharedResponse['flash'] = [
      'message' => fn() => $request->session()->get('message'),
      'success' => fn() => $request->session()->get('success'),
      'error' => fn() => $request->session()->get('error'),
    ];

    $sharedResponse = array_merge($sharedResponse, parent::share($request));

    return $sharedResponse;
  }
}
