<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User; 

class SignupController extends Controller
{
    public function create(){
        return view('signup.signup_form_page');
    }
    
    public function store(){
        $attributes = request()->validate([
            'name' => ['required','max:255'],
            'email' => ['required','email','max:255',Rule::unique('users','email')],
            'username' => ['required','max:255',Rule::unique('users','username')],
            'password' => ['required','min:8','max:255', 'regex:/^(?=.*[0-9])(?=.*[A-Z]).{8,}$/']
        ]);
        
        $attributes['password'] = bcrypt($attributes['password']);
        $user = User::create($attributes);

        return redirect("./signup_success");
    }

}
