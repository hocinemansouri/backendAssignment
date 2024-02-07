<?php

namespace App\Services;

use App\Services\Contracts\AuthenticationServiceInterface;
use Illuminate\Http\Request;

class AuthenticationService implements AuthenticationServiceInterface {
    
    public function returnUserIfAuthenticated($authApi=null,$authWeb=null){
        $user = null;
        if($authWeb) $user = $authWeb->get('user');
        else $user = $authApi->user();
        return $user;
    }

    public function getUserIfAuthenticated(Request $request){
        if($request->user()){
            return $this->returnUserIfAuthenticated($authApi=$request,$authWeb=null);
        }
        return $this->returnUserIfAuthenticated($authApi=null,$authWeb=$request->session());;
    }
}