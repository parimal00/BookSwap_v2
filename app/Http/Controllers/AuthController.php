<?php

namespace App\Http\Controllers;
use Laravel\Socialite\Facades\Socialite;
//use Socialite;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function social($provider) {
        $auth = Socialite::driver('google')->stateless()->user();
        $email = $auth->getEmail();
        $avatar = $auth->getAvatar();
        return "jack is sexy";
    //return response()->json(compact('token', 'avatar'));

        //return "jack is sexy";
    }
}
