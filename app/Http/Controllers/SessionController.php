<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class SessionController extends Controller
{
    public function create(){
        return view('session.login');
    }

    public function store(){
        $attributes = request()->validate([
           'username' => ['required'],
           'password' => ['required']
        ]);
        if(auth()->attempt($attributes)){
            session()->regenerate();
            return redirect('home');
        }
        
        return back()->withErrors(["username"=>"Your provided credentials were invalid."]);
    }

    public function destroy(){

        auth()->logout();
        return redirect('/'); 
    }

    public function adminCheck(){
       
        $validAdmins = ["rforde",];//Array of valid admins
        if(auth()->guest()){
            return redirect("/");
        }
        else if(!in_array(auth()->user()->username,$validAdmins)){
            return redirect("/home");
        }
         return view("auth_user_pages.admin_page");
    }

public function resetLink(){
        request()->validate(['email' => 'required|email']);
 
    $status = Password::sendResetLink(
        request()->only('email')
    );
 
    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
}

public function resetPassword(){
    
        request()->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
     
        $status = Password::reset(
            request()->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );
     
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    
}
}

