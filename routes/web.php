<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\DBController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;





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



Route::middleware(['auth'])->controller(DBController::class)->group(function(){

    
    Route::get("/home", 'viewHomePage');
    Route::get("/stats",'viewStatsPage');
    
    Route::get("/add", 'viewAddPage')->name('add');
    Route::get("/update",  'viewUpdatePage')->name('update');
    Route::get("/delete",  'viewDeletePage')->name('delete');
    Route::get("/updateLoader", 'loadItemForUpdatepage'); 

    Route::get("/items/{item}", 'viewSingleItemPage');
    
    Route::get("/callUPCitemDBAPI", 'callUPCitemDBAPI');

    Route::post("/delete_single_item",'deleteSingleItem');
    Route::post("/delete_multiple_items", 'deleteMultipleItems');

    Route::post("/add_item",'addItems');
    Route::post("/update_item",'updateItems');
    Route::post("/delete_item_photo",'deletePhotoFromItem');

    Route::post('/check_item_exists',[DBController::class, 'checkIfAddedItemExists']); 
    Route::post('/increment_item_quantity',[DBController::class, 'incrementItemFromRequest']);

    Route::get('/redirect_to_update_page/{upc}',[DBController::class, 'redirectToUpdate']);
    
}); 

Route::middleware(['admin'])->group(function(){
    Route::get("/admin", [AdminController::class, 'viewAdminPage'])->name('admin');
    Route::post("/admin_delete_user", [AdminController::class, 'deleteUser']); 
    Route::post("/add_category", [AdminController::class, 'addCategory']);
    Route::post("/update_category", [AdminController::class, 'updateCategory']); 
    Route::post("/delete_category", [AdminController::class, 'deleteCategory']); 
});

Route::middleware(['auth'])->controller(SessionController::class)->group(function(){

    Route::get("/account",  'loadAccountPage');
    Route::post("/change_username",'changeUsername');
    Route::post("/change_name",'changeName');
    Route::post('/change_email','changeEmail');
    Route::post('/change_password','changePassword');

    Route::get("/logout", 'destroy');

});

Route::middleware(['auth'])->group(function(){

    Route::get("/items/{upc}", function($upc){
        return DBController::viewSingleItemPage($upc);
    });

    Route::get('/exportItemXLSX',[ExportController::class, 'exportAsXLSX']);
    Route::get('/exportItemCSV',[ExportController::class, 'exportAsCSV']);

    Route::get('/import',[ImportController::class, 'viewImportPage']);
    Route::post('/import',[ImportController::class, 'importItemCSV']);

});



Route::get("/contact", [DBController::class,'viewContactPage'])->name('contact'); 
Route::post("/processContact",[DBController::class,'processContactInfo']); 
Route::get("/contactSuccess",function(){return view("contactSuccess");})->name('contactSuccess');

Route::get('/check_valid_upc',[DBController::class, 'checkIfUPCValid']);
Route::get('/categories',[DBController::class,'viewCategoriesPage']);


/* Old routes that may come in handy */


/* 
This is the route for the old edit page, which the page itslef would need to be redone if used again
Route::get("/edit", function(){return view("auth_user_pages.edit"); });

*/









