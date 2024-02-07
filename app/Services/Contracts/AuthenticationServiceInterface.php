<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface AuthenticationServiceInterface
{
    public function returnUserIfAuthenticated($authApi=null,$authWeb=null);
    public function getUserIfAuthenticated(Request $request);
}
