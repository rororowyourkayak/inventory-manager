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

Route::middleware(['guest'])->group(function(){

    Route::get("/",function(){return view("start"); })->name('/');
    Route::get("/signup",[SignupController::class, 'create']);
    Route::post("/signup",[SignupController::class, 'store']);
    Route::view("/signup_success", "/signup.signup_success");
    Route::post("/session",[SessionController::class, 'store']);
    Route::get("/login", [SessionController::class, 'create'])->name('login');
    Route::get("/forgot-password", function(){return view("session.forgot_password");});
    Route::post('/forgot-password', [SessionController::class,'resetLink']);

    Route::get('/reset-password/{token}', function ($token) {
        return view('session.reset_password', ['token' => $token]);
        })->name('password.reset');
    Route::post('/reset-password', [SessionController::class, 'resetPassword']); 
});

Route::middleware(['auth'])->group(function(){

    Route::get("/home", [DBController::class, 'viewHomePage']);
    Route::get("/edit", function(){return view("auth_user_pages.edit"); });
    
    Route::get("/stats", [DBController::class,'viewStatsPage']);
    Route::get("/logout", [SessionController::class, 'destroy']);
    Route::get("/add", [DBController::class, 'viewAddPage'])->name('add');
    Route::get("/update",  [DBController::class, 'viewUpdatePage'])->name('update');
    Route::get("/delete",  [DBController::class, 'viewDeletePage'])->name('delete');
    Route::get("/updateLoader", [DBController::class, 'loadItemForUpdatepage']); 
    Route::get("/items/{item}", [DBController::class, 'viewSingleItemPage']);
    Route::get("/callUPCitemDBAPI", [DBController::class, 'callUPCitemDBAPI']); 
    Route::post("/delete_item",[DBController::class,'deleteItems']);
    Route::post("/add_item",[DBController::class,'addItems']);
    Route::post("/update_item",[DBController::class,'updateItems']);
    Route::post("/delete_item_photo",[DBController::class,'deletePhotoFromItem']);

    Route::get("/account", [SessionController::class, 'loadAccountPage']);
    Route::post("/change_username",[SessionController::class,'changeUsername']);
    Route::post("/change_name",[SessionController::class,'changeName']);



}); 

Route::middleware(['admin'])->group(function(){
    Route::get("/admin", [AdminController::class, 'viewAdminPage'])->name('admin');
    Route::post("/admin_delete_user", [AdminController::class, 'deleteUser']); 
    Route::post("/add_category", [AdminController::class, 'addCategory']);
    Route::post("/update_category", [AdminController::class, 'updateCategory']); 
    Route::post("/delete_category", [AdminController::class, 'deleteCategory']); 
});

Route::get("/contact", [DBController::class, 'viewContactPage'])->name('contact'); 
Route::post("/processContact",[DBController::class, 'processContactInfo']); 
Route::get("/contactSuccess",function(){return view("contactSuccess");})->name('contactSuccess');














