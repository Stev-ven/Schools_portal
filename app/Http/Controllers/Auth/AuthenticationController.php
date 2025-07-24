<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{

    public function SignupPage()
    {
        return view('userAuth.signup');
    }

    public function resetPassword(){
        return view('livewire.auth.resetpassword');
    }

    public function dashboard(){
        return view('dashboard.dashboard');
    }


    public function logout(Request $request){

        //Auth::logout();

        $request->session()->forget('api_response');
        //$request->session()->invalidate();

        //$request->session()->regenerateToken();

        return redirect('/login');
    }

}
