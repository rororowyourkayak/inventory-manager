<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\User; 

class SignupController extends Controller
{
    /* load signup view */
    public function create(){
        return view('signup.signup_form_page');
    }
    
    /* get user info from request, if valid make new user
    use bcrypt for password hash
    */
    public function store(){
        $attributes = request()->validate([
            'name' => ['required','max:127'],
            'email' => ['required','email','max:255',Rule::unique('users','email')],
            'username' => ['required','max:127',Rule::unique('users','username')],
            'password' => ['required','min:8','max:127', 'regex:/^(?=.*[0-9])(?=.*[A-Z]).{8,}$/', 'confirmed'],
            'g-recaptcha-response' => ['required','captcha'],
        ]);
        
        $attributes['password'] = bcrypt($attributes['password']);
        $user = User::create($attributes);

        return redirect("./signup_success");
    }

}
