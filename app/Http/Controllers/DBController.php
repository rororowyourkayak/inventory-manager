<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;


class DBController extends Controller
{
   
   
   public function deleteItems(){
      $items = request()->all();
      foreach($items as $item){
         Item::where('id',$item)->delete();
      }
     return redirect("/edit");
   }

   public function addItems(){
       $items = request()->input();
       $items["user"]  = auth()->user()->username;
       Item::create($items);
      
      return redirect("/edit");
   }

   public function updateItems(){
      $items = request()->input();
      Item::where('id',$items["item_selector"])-> 
      update(["name" => $items["name"],
      "category" => $items["category"],
      "description" => $items["description"],
      "quantity" => $items["quantity"]]);
      return redirect("/edit");
   }
}
