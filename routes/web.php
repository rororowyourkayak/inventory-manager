<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\DBController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get("/",function(){return view("start"); });
Route::get("/signup",[SignupController::class, 'create'])->middleware('guest');
Route::post("/signup",[SignupController::class, 'store'])->middleware('guest');

Route::view("/signup_success", "/signup.signup_success");

Route::get("/home", function(){return view("auth_user_pages.home"); })->middleware('auth');
Route::get("/edit", function(){return view("auth_user_pages.edit"); })->middleware('auth');
Route::get("/account", function(){return view("auth_user_pages.account");})->middleware('auth');

Route::post("/session",[SessionController::class, 'store'])->middleware('guest');
Route::get("/login", [SessionController::class, 'create'])->middleware('guest');

Route::get("/logout", [SessionController::class, 'destroy'])->middleware('auth');

Route::post("/delete_item",[DBController::class,'deleteItems']);
Route::post("/add_item",[DBController::class,'addItems']);
Route::post("/update_item",[DBController::class,'updateItems']);

Route::get("/admin", [SessionController::class, 'adminCheck']);


Route::get("/forgot-password", function(){return view("session.forgot_password");})->middleware('guest');
Route::post('/forgot-password', [SessionController::class,'resetLink'])->middleware('guest');

Route::get('/reset-password/{token}', function ($token) {
    return view('session.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');
Route::post('/reset-password', [SessionController::class, 'resetPassword'])->middleware('guest'); 