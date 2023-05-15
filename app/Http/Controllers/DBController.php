<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;
use App\Models\User;
use App\Models\Category; 

class DBController extends Controller
{
    

   public function viewHomePage(){
      

      return view("auth_user_pages.home", array(
         'data' =>Item::where('user',auth()->user()->username)->get(),
          'itemsExist'=>DB::table('items')->where('user', [auth()->user()->username])->exists(),
         
         ));
   }
   public function viewStatsPage(){
      $categoryCounts = []; 
      foreach(Category::all() as $cat){
         $categoryCounts[$cat->category] = Item::where('user', auth()->user()->username)->where('category',$cat->category)->count();
      }
      return view("auth_user_pages.stats",array(
         'categories'=>$categoryCounts, 
         'itemCount' => Item::where('user', auth()->user()->username)->count(),
      ));
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
      $items = request()->input(); 
      $item = $items["delete"]; 
      
         $upc = Item::where('id',$item)->first()->upc;
         Item::where('id',$item)->delete();
         foreach(DB::table('files')->where('item_id', $item)->get() as $photo){
            Storage::disk('public')->delete($photo -> filename); 
         }
         DB::table('files')->where('item_id', $item) -> delete();    
     return back()->with("successMessage", $upc. " was deleted successfully.");
   }

   public function addItems(){
       
       //$items = request()->input();

         $items = request() -> validate([
            'upc' => ['required', 'regex:/\d{12}/'],
            'category' => ['required', 'max:127'],
            'description' => ['max:511'],
            'quantity' => ['required', 'numeric', 'integer', 'gte:1'],
            'file' => ['max:2048'],
         ]);
       $items["user"]  = auth()->user()->username;
      
       $alreadyExists = false; 
       if($checkItem = Item::where('upc', $items["upc"])->where('user', $items['user'])->first()){
            $checkItem -> increment('quantity', $items['quantity']); 
            $alreadyExists = true; 
       }
       else{
         Item::create($items); 
       }
        // dd($newItem);
       if(request()->hasFile('file')){
       foreach (request()->file as $photo) {
         $filename = $photo->store(auth()->user()->username, 'public');
         DB::table('files')->insert([
            'item_id' => $newItem->upc,
         'filename' => $filename,
         'original_name'=>$photo->getClientOriginalName(), 
        ]);
      }
   }
      if($alreadyExists){
         $message = "UPC already exists in inventory. #".$checkItem->upc .
         " quantity incremented by ". $items['quantity'].".\nNew quantity is ".$checkItem->quantity."."; 
         return back()->with('existsMessage', $message);
         }
      else{
         $message = "Item added successfully.";
         return back()->with('successMessage', $message); 
      }
      
   }

   public function updateItems(){
      
      
      $items = request() -> validate([
         'item_selector'=>['required', 'numeric'],
         'category' => ['required', 'max:127'],
         'description' => ['max:511'],
         'quantity' => ['required', 'numeric', 'integer','gte:1'],
         'file' => ['max:2048'],
         
      ]);


      Item::where('id',$items["item_selector"])-> 
      update(["category" => $items["category"],
      "description" => $items["description"],
      "quantity" => $items["quantity"]]);

      if(request()->hasFile('file')){
         foreach (request()->file as $photo) {
           $filename = $photo->store(auth()->user()->username, 'public');
           DB::table('files')->insert([
              'item_id' => $items["item_selector"],
           'filename' => $filename,
           'original_name'=>$photo->getClientOriginalName(),
          ]);
        }}


      return back()->with("successMessage", "Item updated successfully.");
   }

   public function loadItemForUpdatePage(){

     // $request = request() -> input();
      $request = request() -> validate([
         'id'=>['required', 'numeric']
      ]);
      $item = Item::where('id', $request['id'])->first();
      $photos = DB::table('files')->where('item_id',$request['id'])->get();

      $properties = array(
      "category"=>$item->category, 
      "description"=>$item->description, 
      "quantity"=>$item->quantity,
      "photos"=>$photos
      );

      //echo json_encode($properties);
      //dd( response()->json($properties));
    return response()->json($properties);
   

   }

   public function deletePhotoFromItem(){
      $request = request() -> input();
      $file =  $request["delete"];
   
      Storage::disk('public')->delete($file); 
      DB::table('files')->where('filename', $file) -> delete(); 
      return back();
   }

   public function viewSingleItemPage(Item $item){
         return view('auth_user_pages.item', array(
            'item' => $item, 
            'photos' => DB::table('files')->where('item_id', $item->id)->get(),
            'photoCount' => DB::table('files')->where('item_id', $item->id)->count(),

         ));
      
   }

   public function callUPCitemDBAPI(){
      $upc = request()->validate(['upc'=>['required','regex:/\d{12}/']]);
      $base_url = "https://api.upcitemdb.com/prod/trial/lookup?upc="; 

      /* To make API call work, proper certificate needed to be configured with test server, 
      cacert.pem file retrieved from https://curl.se/docs/caextract.html
      set curl.cainfo= in php.ini to path of that file*/
    
      $response = Http::withOptions(['verify'=>false])->get($base_url.$upc["upc"]);
      $response = json_decode($response); //receiving a json response 
      

      if($response->code == "OK"){
         return response()->json($response); 
      }
      else{

         return response()->json(["errorMessage" =>"UPC is not available for price check."]); 
      } 
     /* return response()->json($response);  */
   }

   public function viewContactPage(){
   
      return view('contact'); 
   }

   public function processContactInfo(){
         return true; 
   }
}

