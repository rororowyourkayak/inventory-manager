<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        Storage::deleteDirectory($user["name"]); 
        return to_route('admin'); 
    }

    /* public function automate(){
        $cats = Category::all(); 
        $c = [];
        foreach($cats as $cat){
            array_push($c, $cat->category);
        }
        for($i = 0; $i < 500; $i++){
            $rand = rand(0,count($c)-1);
            Item::create([
                "user"=>"mjordan",
                "name"=>"testItem".($i+1),
                "category"=>$c[$rand],
                "description"=>"This is test item number ".($i+1)
            ]);
        }
        return to_route('/');
    } */
    public function addCategory(){

    }
    public function updateCategory(){
        
    }
    public function deleteCategory(){
        
    }
}
