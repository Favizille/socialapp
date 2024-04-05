<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception; 

class FacebookController extends Controller
{
    public function facebookPage(){
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookRedirect(){
        try{
            $user = Socialite::driver('facebook')->user();

            $finduser = User::where('facebook_id', $user->id)->first();

            if($finduser){
                Auth::login($finduser);

                return redirect()->route('dashboard');
            }else {
                $newUser = User::updateOrCreate(['email' => $user->email], ['name' => $user->name, 'facebook_id' => $user->id, 
                'password' => encrypt('123456dummy')
            ]);

            Auth::login($newUser);

            return redirect()->route('dashboard');
            }
        } catch (Exception $e){
            dd($e->getMessage());
        }
    }
}
