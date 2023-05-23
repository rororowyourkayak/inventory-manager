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
use Mail;
use App\Mail\mailer; 
use Illuminate\Support\Facades\Auth;

class DBController extends Controller
{
    

   public function viewHomePage(){
      

      return view("auth_user_pages.home", array(
         'data' =>Item::where('user_id',auth()->user()->id)->get(),
          'itemsExist'=>DB::table('items')->where('user_id', [auth()->user()->id])->exists(),
         
         ));
   }
   public function viewStatsPage(){
      $categoryCounts = []; 
      foreach(Category::all() as $cat){
         $categoryCounts[$cat->category] = Item::where('user_id', auth()->user()->id)->where('category',$cat->category)->count();
      }
      return view("auth_user_pages.stats",array(
         'categories'=>$categoryCounts, 
         'itemCount' => Item::where('user_id', auth()->user()->id)->count(),
      ));
   }
   public function viewAddPage(){
      
      return view("auth_user_pages.add_items", array(
         'categories'=> Category::all()));
   }

   public function viewUpdatePage(){
      return view("auth_user_pages.update_items", array(
         'categories'=>Category::all(),
         'data'=>Item::where('user_id',auth()->user()->id)->get(),
         
      ));
   }

   public function viewDeletePage(){

      return view("auth_user_pages.delete_items", array(
         'data'=>Item::where('user_id',auth()->user()->id)->get(), 
         'itemsExist'=>DB::table('items')->where('user_id', [auth()->id()])->exists()));
      
   }

   /* function to delete individual items and their associated files */
   public function deleteItem($upc, $user_id){

      Item::where('upc',$upc)->where('user_id',$user_id)->delete();

      foreach(DB::table('files')->where('upc',$upc)->where('user_id',$user_id)->get() as $photo){
            Storage::disk('public')->delete($photo -> filename); 
      }
      DB::table('files')->where('upc',$upc)->where('user_id',$user_id)-> delete();   

      return true; 
   }

   public function deleteSingleItem(){
      $items = request()->validate([
         'upc' => ['required', 'regex:/\d{12}/'],
      ]); 

    /*   if($this->deleteItem($items['upc'], auth()->id())){
         return back()->with("successMessage", $items['upc']. " was deleted successfully.");
      }
      else{
         return back()->with("errorMessage", "Delete was not successful.");
      } */

      if($this->deleteItem($items['upc'], auth()->id())){
         return response()->json(['success'=>$items['upc']. " was deleted successfully."]);
      }
      else{
         return response()->json(['error'=>$items['upc']. " was not deleted successfully."]);
      }
    
   }

   public function deleteMultipleItems(){

        $upcs = request()->get('upc'); 
         $validUPCs = []; 
        foreach($upcs as $upc){
            if(preg_match('/\d{12}/', $upc)){
               array_push($validUPCs, $upc); 
            }
        }
        
        $user_id = auth()->id(); 

        $deletedCounter = 0; 
        foreach($validUPCs as $upcToDelete){

            if($this -> deleteItem($upcToDelete, $user_id)) { $deletedCounter++; }

        }
      //  dd($request->get('upc'));
       
       return response()->json(['success'=>$deletedCounter . " item(s) deleted successfully."]);

   }

   public function addItems(){
       
       //$items = request()->input();

         $items = request() -> validate([
            'upc' => ['required', 'regex:/\d{12}/', 'size:12'],
            'category' => ['required', 'max:127'],
            'description' => ['max:511'],
            'quantity' => ['required', 'numeric', 'integer', 'gte:1'],
            'file' => ['max:2048'],
         ]);
       $items["user_id"]  = auth()->id();
   
      
       $alreadyExists = false; 
       if($checkItem = Item::where('upc', $items["upc"])->where('user_id', $items['user_id'])->first()){
            //$checkItem -> increment('quantity', $items['quantity']); 
            $alreadyExists = true; 
       }
       else{
        $newItem = Item::create($items); 
       

       if(request()->hasFile('file')){
       foreach (request()->file as $photo) {
         $filename = $photo->store(auth()->user()->username, 'public');
         DB::table('files')->insert([
            'user_id' => $newItem->user_id,
            'upc' => $newItem -> upc,
         'filename' => $filename,
         'original_name'=>$photo->getClientOriginalName(), 
        ]);
      }
   }
}
   if($newItem){
      return response()->json(["success" => "UPC# ". $newItem -> upc . " created successfully."]);
   }
   else{
      return response()->json(["error" => "UPC# ". $newItem -> upc . " created successfully."]);
   }
      
      
   }

   public function incrementItemFromRequest(){

      $data = request()->validate([
         'upc'=>['required', 'regex:/\d{12}/', 'size:12'],
         'quantity' => ['required', 'numeric', 'integer', 'gte:1'],
      ]);

      /* if($item = Item::where('upc', $data["upc"])->where('user_id', auth()->id())->first()){
         $item -> increment('quantity', $data['upc']);
      } */
      $item = Item::where('upc', $data["upc"])->where('user_id', auth()->id())->first();
      Item::where('upc', $data["upc"])->where('user_id', auth()->id())->update(["quantity" => $item->quantity + $data['quantity']]);

     // $item ->update(['quantity', $item->quantity + $data['quantity']]);

      return response()->json(['success'=> 'UPC# ' . $data["upc"] . " was incremented successfully!"]); 

   }
   public function updateItems(){
      
      
      $items = request() -> validate([
         'item_selector'=>['required', 'numeric'],
         'category' => ['required', 'max:127'],
         'description' => ['max:511'],
         'quantity' => ['required', 'numeric', 'integer','gte:1'],
         'file' => ['max:2048'],
         
      ]);


      Item::where('upc',$items["item_selector"])->where('user_id', auth()->id())-> 
      update(["category" => $items["category"],
      "description" => $items["description"],
      "quantity" => $items["quantity"]]);

      if(request()->hasFile('file')){
         foreach (request()->file as $photo) {
           $filename = $photo->store(auth()->user()->username, 'public');
           DB::table('files')->insert([
              'user_id' => auth()->id(),
              'upc' => $items["item_selector"],
           'filename' => $filename,
           'original_name'=>$photo->getClientOriginalName(),
          ]);
        }}


      return back()->with("successMessage", "Item updated successfully.");
   }

   public function loadItemForUpdatePage(){

     // $request = request() -> input();
      $request = request() -> validate([
         'upc'=>['required']
      ]);
      $item = Item::where('upc', $request['upc'])->where('user_id', auth()->id())->first();
      $photos = DB::table('files')->where('upc', $request['upc'])->where('user_id', auth()->id())->get();

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

   public function viewSingleItemPage($upc){
      $item = Item::where('upc', $upc)->where('user_id', auth()->id())->first(); 
         return view('auth_user_pages.item', array(
            'item' => $item, 
            'photos' => DB::table('files')->where('upc', $upc)->where('user_id', auth()->id())->get(),
            'photoCount' => DB::table('files')->where('upc', $upc)->where('user_id', auth()->id())->count(),

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
         $mailInfo = request()->validate([
            'name' => ['required', 'max:255',],
            'email' => ['required', 'max:300', 'email'],
            'subject' =>['required'],
            'message' => ['required', 'max:10000'],
            'g-recaptcha-response' => ['required','captcha']
         ]);

         if(Mail::to('inventory@example.com')->send(new mailer($mailInfo))){
            return to_route("contactSuccess");
         }
         else{
            return back()->withErrors(); 
         }
         
   }

   public function checkIfItemExists($upc, $user_id){

      return Item::where('upc',$upc)->where('user_id', $user_id)->exists(); 

   }

   public function checkIfAddedItemExists(){

      $validated = request()->validate([
         'upc' => ['required', 'regex:/\d{12}/', 'size:12'],
      ]);

      $exists = $this->checkIfItemExists($validated['upc'], auth()->id());

      if($exists){
         $response = ['exists' => 'Item already exists.'];
      }
      else{ 
         $response = ['new' => 'Item does not yet exist.'];
      }

      return response()->json($response);
   }
}

