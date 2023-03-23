<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User; 
use App\Models\Item; 
use App\Models\Category; 

class AdminController extends Controller
{
    public function viewAdminPage(){
       
        $numUsers = DB::table('users')->count();
        $numItems = DB::table('items')->count();
        $allUsers = User::all(); 
         return view("auth_user_pages.admin_page",array(
            "numUsers" => DB::table('users')->count(),
            "numItems" => DB::table('items')->count(), 
            "allUsers" => $allUsers = User::all(),
            "categories" => Category::all(),
         ));
    }

    public function deleteUser(){

        $user = request()->validate(['name' => ['required','max:127']]);

        Item::where('user', $user["name"])->delete(); 
        User::where('username',$user["name"])->delete(); 

        return to_route('admin'); 
    }
    public function addCategory(){

    }
    public function updateCategory(){
        
    }
    public function deleteCategory(){
        
    }
}
