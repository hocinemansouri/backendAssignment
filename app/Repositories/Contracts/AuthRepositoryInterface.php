<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    public function attemptLogin($credentials)
    {
        return Auth::attempt($credentials);
    }

    public function logout()
    {
        Auth::logout();
    }

    public function user()
    {
        return Auth::user();
    }

    // You can add more authentication-related methods as needed.
}