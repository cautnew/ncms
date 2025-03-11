<?php

namespace App\Http\Controllers\NCMS\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Services\NCMS\People\UserServices;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Http\Requests\NCMS\Auth\AuthRequest;
use App\Models\NCMS\People\Person;
use App\Models\NCMS\People\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Container\Attributes\Auth as AttributesAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('ncms.home');
        }
        return view('ncms.pages.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->put('user-id', Auth::id());
            $request->session()->put('person-id', Person::findByUserId(Auth::id())->first()->id);
            $request->session()->regenerate();

            return redirect()->route('ncms.home');
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials are incorrect.',
        ]);
    }

    public function create()
    {
        return view('ncms.pages.register');
    }

    public function store(AuthRequest $request)
    {
        $request->validated();

        try {
            $userService = new UserServices();
            $userService->createUser($request->all());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the user');
        }

        return redirect()->route('ncms.auth.login')->with('success', 'User created successfully');
    }
}
