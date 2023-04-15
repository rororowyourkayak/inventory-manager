<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;
use App\Models\User;
use App\Models\Category; 

class DBController extends Controller
{
    

   public function viewHomePage(){
      $data = Item::where('user',auth()->user()->username)->get(); 
      $itemsExist = DB::table('items')->where('user', [auth()->user()->username])->exists(); 
      return view("auth_user_pages.home", compact('data', 'itemsExist'));
   }
   public function viewAddPage(){
      
      return view("auth_user_pages.add_items", array(
         'categories'=> Category::all()));
   }

   public function viewUpdatePage(){
      return view("auth_user_pages.update_items", array(
         'categories'=>Category::all(),
         'data'=>Item::where('user',auth()->user()->username)->get(),
      ));
   }

   public function viewDeletePage(){

      return view("auth_user_pages.delete_items", array(
         'data'=>Item::where('user',auth()->user()->username)->get(), 
         'itemsExist'=>DB::table('items')->where('user', [auth()->user()->username])->exists()));
      
   }

   
   
   public function deleteItems(){
      $items = request()->all();
      foreach($items as $item){
         Item::where('id',$item)->delete();
         foreach(DB::table('files')->where('item_id', $item)->get() as $photo){
            Storage::disk('public')->delete($photo -> filename); 
         }
         DB::table('files')->where('item_id', $item) -> delete(); 
      }      
     return to_route("delete");
   }

   public function addItems(){
       
       //$items = request()->input();

         $items = request() -> validate([
            'name' => ['required', 'max:127', Rule::unique('items', 'name')],
            'category' => ['required', 'max:127'],
            'description' => ['max:511'],
            'quantity' => ['required', 'numeric', 'integer'],
            'file' => ['max:2048'],
         ]);
       $items["user"]  = auth()->user()->username;

       $newItem = Item::create($items);

       if(request()->hasFile('file')){
       foreach (request()->file as $photo) {
         $filename = $photo->store(auth()->user()->username, 'public');
         DB::table('files')->insert([
            'item_id' => $newItem->id,
         'filename' => $filename,
         'original_name'=>$photo->getClientOriginalName(),
        ]);
      }
   }
      
      return to_route("add"); 
   }

   public function updateItems(){
      
      
      $items = request() -> validate([
         'item_selector'=>['required', 'numeric', 'integer'],
         'name' => ['required', 'max:127'],
         'category' => ['required', 'max:127'],
         'description' => ['max:511'],
         'quantity' => ['required', 'numeric', 'integer'],
         
      ]);


      Item::where('id',$items["item_selector"])-> 
      update(["name" => $items["name"],
      "category" => $items["category"],
      "description" => $items["description"],
      "quantity" => $items["quantity"]]);
      return to_route('update');
   }

   public function loadItemForUpdatePage(){

      $request = request() -> input();
      
      $item = Item::where('id', $request['id'])->first();
      $properties = array("name" => $item -> name, 
      "category"=>$item->category, 
      "description"=>$item->description, 
      "quantity"=>$item->quantity);

      echo json_encode($properties);
     // return response()->json($properties);
   

   }

   public function viewSingleItemPage(Item $item){ 
         
         return view('auth_user_pages.item', array(
            'item' => $item, 
            'photos' => DB::table('files')->where('item_id', $item->id)->get(),
            'photoCount' => DB::table('files')->where('item_id', $item->id)->count(),

         ));
      
   }
}
