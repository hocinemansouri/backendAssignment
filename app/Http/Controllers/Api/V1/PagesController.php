<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    function signin(Request $request)
    {
        if ($request->user()) {
            return view('home');
        }
        return view('signin');
    }

    function updateprofile(Request $request)
    {
        if ($request->user()) {
            return view('home');
        }
        return view('updateprofile');
    }

    function signup()
    {
        return view('signup');
    }
}
