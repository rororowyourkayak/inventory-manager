<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User; 
use App\Models\Item; 
use App\Models\Category; 
use Illuminate\Validation\Rule;


class AdminController extends Controller
{
    public function viewAdminPage(){
       
        
         return view("auth_user_pages.admin_page",array(
            "numUsers" => User::count(),
            "numItems" => Item::count(), 
            "allUsers" => User::all(),
            "categories" => Category::all(),
         ));
    }

    public function deleteUser(){

        $user = request()->validate(['user_id' => ['required','numeric']]);

        Item::where('user_id', $user["user_id"])->delete(); 
        User::where('id',$user["user_id"])->delete(); 
        Storage::deleteDirectory($user["name"]); 
        return back(); 
    }

    
    public function addCategory(){
        $category = request()->validate(['name' => ['required','max:127',Rule::unique('categories', 'category')]]);

        Category::create(['category' => $category['name']]); 
        return back(); 
    }
    public function updateCategory(){
        $category = request()->validate([
            'new' =>['required', 'max:127'],
            'cat' => ['required']
        ]);
      
        Category::where('category', $category["cat"])->update(['category'=> $category["new"]]);
        return back();
    }
    public function deleteCategory(){
        $category = request()->validate([
            'cat' => ['required'],
        ]);

        Category::where('category', $category["cat"])->delete();

        return back();

    }
}
