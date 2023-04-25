<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\DBController;
use App\Http\Controllers\AdminController;



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


Route::get("/",function(){return view("start"); })->middleware('guest')->name('/');
Route::get("/signup",[SignupController::class, 'create'])->middleware('guest');
Route::post("/signup",[SignupController::class, 'store'])->middleware('guest');

Route::view("/signup_success", "/signup.signup_success")->middleware('guest');

Route::get("/home", [DBController::class, 'viewHomePage'])->middleware('auth');
Route::get("/edit", function(){return view("auth_user_pages.edit"); })->middleware('auth');
Route::get("/account", function(){return view("auth_user_pages.account");})->middleware('auth');
Route::get("/stats", [DBController::class,'viewStatsPage'])->middleware('auth');

Route::post("/session",[SessionController::class, 'store'])->middleware('guest');
Route::get("/login", [SessionController::class, 'create'])->middleware('guest')->name('login');

Route::get("/logout", [SessionController::class, 'destroy'])->middleware('auth');

Route::get("/add", [DBController::class, 'viewAddPage'])->middleware('auth')->name('add');
Route::get("/update",  [DBController::class, 'viewUpdatePage'])->middleware('auth')->name('update');
Route::get("/delete",  [DBController::class, 'viewDeletePage'])->middleware('auth')->name('delete');
Route::get("/updateLoader", [DBController::class, 'loadItemForUpdatepage'])->middleware('auth'); 


Route::get("/items/{item}", [DBController::class, 'viewSingleItemPage'])->middleware('auth');

Route::post("/delete_item",[DBController::class,'deleteItems'])->middleware('auth');
Route::post("/add_item",[DBController::class,'addItems'])->middleware('auth');
Route::post("/update_item",[DBController::class,'updateItems'])->middleware('auth');
Route::post("/delete_item_photo",[DBController::class,'deletePhotoFromItem'])->middleware('auth');

Route::get("/admin", [AdminController::class, 'viewAdminPage'])->middleware('admin')->name('admin');
Route::post("/admin_delete_user", [AdminController::class, 'deleteUser'])->middleware('admin'); 
Route::post("/add_category", [AdminController::class, 'addCategory'])->middleware('admin');
Route::post("/update_category", [AdminController::class, 'updateCategory'])->middleware('admin'); 
Route::post("/delete_category", [AdminController::class, 'deleteCategory'])->middleware('admin'); 

/* 
Route::get("/automateEntries", [AdminController::class, 'automate'])->middleware('admin');
 */

Route::get("/forgot-password", function(){return view("session.forgot_password");})->middleware('guest');
Route::post('/forgot-password', [SessionController::class,'resetLink'])->middleware('guest');

Route::get('/reset-password/{token}', function ($token) {
    return view('session.reset_password', ['token' => $token]);
})->middleware('guest')->name('password.reset');
Route::post('/reset-password', [SessionController::class, 'resetPassword'])->middleware('guest'); 