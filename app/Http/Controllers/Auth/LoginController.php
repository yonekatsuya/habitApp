<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::TOP;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectGoogle()
    {
        Log::debug('google');
        // Google へのリダイレクト
        // $googleUser = Socialite::driver('google')->redirect();
        // Log::debug($googleUser);
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        Log::debug('success');
        // Google 認証後の処理
        // あとで処理を追加しますが、とりあえず dd() で取得するユーザー情報を確認
        $user = Socialite::driver('google')->stateless()->user();
        dd($user);
    }

    public function redirectFacebook()
    {
        Log::debug('facebook');
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookProviderCallback()
    {
        $user = Socialite::driver('facebook')->user();

        Log::debug('success');
        dd($user);
    }

    public function redirectTwitter() {
        Log::debug('twitter');
        return Socialite::driver('twitter')->redirect();
    }

    public function handleTwitterCallback() {
        Log::debug('success');
        $user = Socialite::driver('twitter')->user();
        dd($user);
    }
}
