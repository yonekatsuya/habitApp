<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use Log;

class SocialController extends Controller
{
    public function redirectToFacebookProvider()
    {
        Log::debug('facebook');
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookProviderCallback()
    {
        try{
            $user = Socialite::driver('facebook')->user();

            Log::debug('success');
            dd($user);

        //     if($user){
        //         dd($user);
        //         // OAuth Two Providers
        //         $token = $user->token;
        //         $refreshToken = $user->refreshToken; // not always provided
        //         $expiresIn = $user->expiresIn;

        //         // All Providers
        //         $user->getId();
        //         $user->getNickname();
        //         $user->getName();
        //         $user->getEmail();
        //         $user->getAvatar();

            // }
        }catch(Exception $e){
            Log::debug('error');
            // return redirect("/");
        }

        // $user->token;
    }
}