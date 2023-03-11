<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Category; 


class DBController extends Controller
{
   public function viewHomePage(){
      $data = Item::where('user',auth()->user()->username)->get(); 
      $itemsExist = DB::table('items')->where('user', [auth()->user()->username])->exists(); 
      return view("auth_user_pages.home", compact('data', 'itemsExist'));
   }
   public function viewAddPage(){
      $categories = Category::all(); 

      return view("auth_user_pages.add_items", compact('categories'));
   }

   public function viewUpdatePage(){
      $categories = Category::all(); 
   
      return view("auth_user_pages.update_items", compact('categories'));
   }

   public function viewDeletePage(){

      $data = Item::where('user',auth()->user()->username)->get(); 
      $itemsExist = DB::table('items')->where('user', [auth()->user()->username])->exists(); 
      return view("auth_user_pages.delete_items", compact('data', 'itemsExist'));
      
   }

   
   
   public function deleteItems(){
      $items = request()->all();
      foreach($items as $item){
         Item::where('id',$item)->delete();
      }
     return redirect("/delete");
   }

   public function addItems(){
       
       //$items = request()->input();

         $items = request() -> validate([
            'name' => ['required', 'max:127'],
            'category' => ['required', 'max:127'],
            'description' => ['max:511'],
            'quantity' => ['required', 'numeric', 'integer']
         ]);
       $items["user"]  = auth()->user()->username;
       Item::create($items);
      
      return redirect("/add");
   }

   public function updateItems(){
      $items = request() -> validate([
         'item_selector'=>['required', 'numeric', 'integer'],
         'name' => ['required', 'max:127'],
         'category' => ['required', 'max:127'],
         'description' => ['max:511'],
         'quantity' => ['required', 'numeric', 'integer']
      ]);
      Item::where('id',$items["item_selector"])-> 
      update(["name" => $items["name"],
      "category" => $items["category"],
      "description" => $items["description"],
      "quantity" => $items["quantity"]]);
      return redirect("/update");
   }

   public function loadItemForUpdatePage(){
      $request = request() -> input();
      
      $item = Item::where('id', $request['id'])->first();
      $properties = array("name" => $item -> name, 
      "category"=>$item->category, 
      "description"=>$item->description, 
      "quantity"=>$item->quantity); 
      echo json_encode($properties);

   }
}
