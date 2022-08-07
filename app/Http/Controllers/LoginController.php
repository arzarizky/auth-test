<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Fortify\Fortify;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoginController extends Controller
{

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Google login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Google callback
    public function handleGoogleCallback(\Request $request)
    {
        $user = Socialite::driver('google')->user();

        $this->_registerOrLoginUser($user);

        // Return home after login
        return redirect()->route('home');
        // try {
        //     $user_google    = Socialite::driver('google')->user();
        //     // dd($user_google);
        //     $user           = User::where('email', $user_google->getEmail())->first();


        //     //jika user ada maka langsung di redirect ke halaman home
        //     //jika user tidak ada maka simpan ke database
        //     //$user_google menyimpan data google account seperti email, foto, dsb (cek hasil dd)

        //     if($user != null){
        //         Auth::login($user, true);
        //         // \auth()->login($user, true);
        //         return redirect()->route('home');
        //     }else{
        //         $create = User::Create([
        //             'provider_id'       => $user_google->getid(),
        //             'email'             => $user_google->getEmail(),
        //             'name'              => $user_google->getName(),
        //             'avatar'            => $user_google->getAvatar(),
        //             'password'          => 0,
        //         ]);

        //         Auth::login($create, true);
        //         // auth()->login($create, true);
        //         return redirect()->route('home');
        //     }

        // } catch (\Exception $e) {
        //     return redirect()->route('login');
        // }
    }


    // Facebook login
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    // Facebook callback
    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();

        $this->_registerOrLoginUser($user);

        // Return home after login
        return redirect()->route('home');
    }

    // Github login
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    // Github callback
    public function handleGithubCallback()
    {
        $user = Socialite::driver('github')->user();

        $this->_registerOrLoginUser($user);
        // ser::where('username','John') -> first();
        // $verifikasiEmail = User::where('email_verified_at', null)->first();
        if (User::where('email_verified_at', null)) {
            Fortify::verifyEmailView(function () {
                return view('auth.verify-email');
            });
        }

        // Return home after login
        return redirect()->route('home');
    }

    protected function _registerOrLoginUser($data)
    {
        $user = User::where('email', '=', $data->email)->first();
        if (!$user) {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->provider_id = $data->id;
            $user->avatar = $data->avatar;
            $user->save();
        }

        Auth::login($user);
    }
}
