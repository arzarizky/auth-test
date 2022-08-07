<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
// use Request


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
    // public function handleGoogleCallback(\Request $request)
    // {

    //     try {
    //         $user_google    = Socialite::driver('google')->user();
    //         $user           = User::where('email', $user_google->getEmail())->first();
    //         dd($user);

    //         //jika user ada maka langsung di redirect ke halaman home
    //         //jika user tidak ada maka simpan ke database
    //         //$user_google menyimpan data google account seperti email, foto, dsb

    //         if($user != null){
    //             Auth::login($user, true);
    //             \auth()->login($user, true);
    //             return redirect()->route('home');
    //         }else{
    //             $create = User::Create([
    //                 'email'             => $user_google->getEmail(),
    //                 'name'              => $user_google->getName(),
    //                 'avatar'            => $user_google->avatar(),
    //                 'password'          => 0,
    //                 'email_verified_at' => now()
    //             ]);

    //             Auth::login($create, true);
    //             auth()->login($create, true);
    //             return redirect()->route('home');
    //         }

    //     } catch (\Exception $e) {
    //         return redirect()->route('login');
    //     }
    // }

    public function handleGoogleCallback()
    {
        try {
            $user_google    = Socialite::driver('google')->user();
            $user           = User::where('email', $user_google->getEmail())->first();

            //jika user ada maka langsung di redirect ke halaman home
            //jika user tidak ada maka simpan ke database
            //$user_google menyimpan data google account seperti email, foto, dsb

            if($user != null){
                \auth()->login($user, true);
                return redirect()->route('home');
            }else{
                $create = User::Create([
                    'email'             => $user_google->getEmail(),
                    'name'              => $user_google->getName(),
                    'password'          => 0,
                    'email_verified_at' => now()
                ]);


                \auth()->login($create, true);
                return redirect()->route('home');
            }

        } catch (\Exception $e) {
            return redirect()->route('login');
        }


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
