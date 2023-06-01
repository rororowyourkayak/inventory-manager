<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SessionController extends Controller
{
    /* login view  */
    public function create()
    {
        return view('session.login');
    }

    /* get login info from request, attempt login, if doesn't send back errors */
    public function store()
    {
        $attributes = request()->validate([
            'username' => ['required', 'max:127'],
            'password' => ['required', 'max:127'],
        ]);
        if (auth()->attempt($attributes)) {
            session()->regenerate();
            return redirect('home');
        }

        return back()->withErrors(["username" => "Your provided credentials were invalid."]);
    }

    /* logout user, send back to start page */
    public function destroy()
    {

        auth()->logout();
        return redirect('/');
    }

    /* load account page */
    public function loadAccountPage()
    {
        return view("auth_user_pages.account");
    }

    /* change username from user request */
    public function changeUsername()
    {
        $input = request()->validate([
            'name' => ['required', 'max:127', 'regex:/^[A-Za-z0-9]+$/', Rule::unique('users', 'username')],
            'user_id' => ['required', 'numeric', 'integer'],
        ]);

        try {
            if (User::where('id', $input["user_id"])->update(["username" => $input["name"]])) {
                return back()->with("successMessage", "Username updated successfully!");
            }
            return back()->withErrors();
        } catch (Exception $e) {
            return back()->with(["errorMessage" => $e->getMessage()]);
        }
    }
    
     /* change name from user request */
    public function changeName()
    {
        $input = request()->validate([
            'name' => ['required', 'max:127', 'regex:/^[A-Za-z]+\s*[A-Za-z]*\s*[A-Za-z]*$/', Rule::unique('users', 'name')],
            'user_id' => ['required', 'numeric', 'integer'],
        ]);
        try {

            if (User::where('id', $input["user_id"])->update(["name" => $input["name"]])) {
                return back()->with("successMessage", "Name updated successfully!");
            }
            return back()->withErrors();

        } catch (Exception $e) {
            return back()->with(["errorMessage" => $e->getMessage()]);
        }
    }

    /* change email, email must be unique and confirmed */
    public function changeEmail()
    {
        $input = request()->validate([
            'email' => ['required', 'max:255', 'email', Rule::unique('users', 'email'), 'confirmed'],
            'user_id' => ['required', 'numeric', 'integer'],
        ]);
        try {
            if (User::where('id', $input["user_id"])->update(["email" => $input["email"]])) {
                return back()->with("successMessage", "Email updated successfully!");
            }
            return back()->withErrors();

        } catch (Exception $e) {
            return back()->with(["errorMessage" => $e->getMessage()]);
        }
    }

    /* change password, use bcrpyt on password before storing */
    public function changePassword()
    {
        $input = request()->validate([
            'password' => ['required','min:8','max:127', 'regex:/^(?=.*[0-9])(?=.*[A-Z]).{8,}$/', 'confirmed'],
            'user_id' => ['required', 'numeric', 'integer'],
        ]);
        try {
            if (User::where('id', $input["user_id"])->update(["password" => bcrypt($input["password"])])) {
                return back()->with("successMessage", "Password updated successfully!");
            }
            return back()->withErrors();

        } catch (Exception $e) {
            return back()->with(["errorMessage" => $e->getMessage()]);
        }
    }

    /* send link for email reset */
    public function resetLink()
    {
        request()->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            request()->only('email')
        );

        return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
    }

    /* process password reset info */
    public function resetPassword()
    {

        request()->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[0-9])(?=.*[A-Z]).{8,}$/',
        ]);

        $status = Password::reset(
            request()->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
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
