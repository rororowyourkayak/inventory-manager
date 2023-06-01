<?php

namespace App\Http\Controllers;

use App\Mail\mailer;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use App\Rules\UniqueItem;
use App\Rules\UPC;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Mail;

class DBController extends Controller
{

    /* Pass items and if they exist to home view */
    public function viewHomePage()
    {

        return view("auth_user_pages.home", array(
            'data' => Item::where('user_id', auth()->user()->id)->orderBy('updated_at', 'ASC')->get(),
            'itemsExist' => DB::table('items')->where('user_id', [auth()->user()->id])->exists(),
        ));
    }

    /* Pass the number of items in each category, total items to stats view */
    public function viewStatsPage()
    {
        $categoryCounts = [];
        foreach (Category::all() as $cat) {
            $categoryCounts[$cat->category] = Item::where('user_id', auth()->user()->id)->where('category', $cat->category)->count();
        }
        return view("auth_user_pages.stats", array(
            'categories' => $categoryCounts,
            'itemCount' => Item::where('user_id', auth()->user()->id)->count(),
        ));
    }

    /* Pass the categories available to add view */
    public function viewAddPage()
    {

        return view("auth_user_pages.add_items", array(
            'categories' => Category::all(),
        ));
    }

    /* update view - pass categories and item upc & description  */
    public function viewUpdatePage()
    {
        return view("auth_user_pages.update_items", array(
            'categories' => Category::all(),
            'data' => Item::where('user_id', auth()->user()->id)->select('upc', 'description')->get(),

        ));
    }

    /* delete view - pass items and their existence */
    public function viewDeletePage()
    {

        return view("auth_user_pages.delete_items", array(
            'data' => Item::where('user_id', auth()->user()->id)->get(),
            'itemsExist' => DB::table('items')->where('user_id', [auth()->id()])->exists()));

    }

    /* function to delete individual items and their associated photos
    takes in item upc and the user's id as the parameters
     */
    public function deleteItem($upc, $user_id)
    {

        Item::where('upc', $upc)->where('user_id', $user_id)->delete();

        foreach (DB::table('photos')->where('upc', $upc)->where('user_id', $user_id)->get() as $photo) {
            Storage::disk('public')->delete($photo->filename);
        }
        DB::table('photos')->where('upc', $upc)->where('user_id', $user_id)->delete();

        return true;
    }

    /* grabs single upc from request, calls deleteItem on that upc for the user
    return response with message containing upc that was deleted
     */
    public function deleteSingleItem()
    {

        $validator = Validator::make(request()->input(), [
            'upc' => ['required', 'regex:/\d{12}/'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $items = $validator->validated();

        try {
            if ($this->deleteItem($items['upc'], auth()->id())) {
                return response()->json(['success' => $items['upc'] . " was deleted successfully."]);
            } else {
                return response()->json(['error' => $items['upc'] . " was not deleted successfully."]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

    }

    /* grabs upcs from request, checks that upcs are 12 digit numbers, if so put in array to delete
    for each upc in array to delete, call deleteItem, maintain a counter of how many were deleted,
    return response message with number deleted
     */
    public function deleteMultipleItems()
    {

        $upcs = request()->get('upc');

        $user_id = auth()->id();

        $deletedCounter = 0;
        foreach ($upcs as $upcToDelete) {

            try {
                if ($this->deleteItem($upcToDelete, $user_id)) {$deletedCounter++;}
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['errorUPC' => 'UPC# ' . $upcToDelete . 'failed to delete.']);
            }

        }
        //  dd($request->get('upc'));

        return response()->json(['success' => $deletedCounter . " item(s) deleted successfully."]);

    }

    /* gets item info from request, check if the items already exists, if not add itwm and its associated photos,
    return response with item upc back to the user
     */
    public function addItems()
    {

        //$items = request()->input();
        $validator = Validator::make(request()->all(), [
            'upc' => ['required', 'regex:/\d{12}/', 'size:12', new UPC, 'bail', new UniqueItem],
            'category' => ['required', 'max:127'],
            'description' => ['max:511'],
            'quantity' => ['required', 'numeric', 'integer', 'gte:1'],
            'file' => ['max: 2048'],
            'file.*' => ['image', 'mimes:jpg,png,jpeg'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $items = $validator->validated();

        $items["user_id"] = auth()->id();

        try {

            $newItem = Item::create($items);

            if (request()->hasFile('file')) {
                foreach (request()->file as $photo) {
                    $filename = $photo->store(auth()->user()->username, 'public');
                    DB::table('photos')->insert([
                        'user_id' => $newItem->user_id,
                        'upc' => $newItem->upc,
                        'filename' => $filename,
                        'original_name' => $photo->getClientOriginalName(),
                    ]);
                }
            }

            if ($newItem) {
                return response()->json(["success" => "UPC# " . $newItem->upc . " created successfully."]);
            } else {
                return response()->json(["errors" => "UPC# " . $newItem->upc . "not created successfully."]);

            }
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['errors' => $e->getMessage]);
        }

    }

    /* this is called if item exists and user wants to increment, adds to item and returns message with upc on success */
    public function incrementItemFromRequest()
    {

        $data = request()->validate([
            'upc' => ['required', 'regex:/\d{12}/', 'size:12'],
            'quantity' => ['required', 'numeric', 'integer', 'gte:1'],
        ]);

        /* if($item = Item::where('upc', $data["upc"])->where('user_id', auth()->id())->first()){
        $item -> increment('quantity', $data['upc']);
        } */
        try {
            $item = Item::where('upc', $data["upc"])->where('user_id', auth()->id())->first();
            Item::where('upc', $data["upc"])->where('user_id', auth()->id())->update(["quantity" => $item->quantity + $data['quantity']]);

            // $item ->update(['quantity', $item->quantity + $data['quantity']]);

            return response()->json(['success' => 'UPC# ' . $data["upc"] . " was incremented successfully!"]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

    }

    public function redirectToUpdate($upc)
    {
        if ($this->checkIfItemExists($upc, auth()->id())) {
            return to_route('update', ['upc' => $upc])->with(['redirectMessage' => 'You have been redirected to this page because UPC#'.$upc .' already exists in inventory.']);
        } else {
            abort(404);
        }
    }

    /* get item info from request, if validated do a direct update to the item, return success message */
    public function updateItems()
    {
        $validated = Validator::make(request()->all(), [
            'item_selector' => ['required', 'numeric'],
            'category' => ['required', 'max:127'],
            'description' => ['max:511'],
            'quantity' => ['required', 'numeric', 'integer', 'gte:1'],
            'file.*' => ['max:2048', 'image', 'mimes:jpg,png,jpeg'],
        ]);
        /*   $items = request()->validate([
        'item_selector' => ['required', 'numeric'],
        'category' => ['required', 'max:127'],
        'description' => ['max:511'],
        'quantity' => ['required', 'numeric', 'integer', 'gte:1'],
        'file' => ['max:2048'],

        ]); */

        $items = $validated->validated();
        if ($validated->fails()) {
            return back()->with(["errorMessage", $validated->errors()]);
        }

        try {
            Item::where('upc', $items["item_selector"])->where('user_id', auth()->id())->
                update(["category" => $items["category"],
                "description" => $items["description"],
                "quantity" => $items["quantity"]]);

            if (request()->hasFile('file')) {
                foreach (request()->file as $photo) {
                    $filename = $photo->store(auth()->user()->username, 'public');
                    DB::table('photos')->insert([
                        'user_id' => auth()->id(),
                        'upc' => $items["item_selector"],
                        'filename' => $filename,
                        'original_name' => $photo->getClientOriginalName(),
                    ]);
                }}

            return to_route('update', ['upc' => $items["item_selector"]])->with("successMessage", "Item updated successfully.");
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with(["errorMessage", $e->getMessage()]);
        }

    }

    /* get upc from the request, send back the item's data for that upc */
    public function loadItemForUpdatePage()
    {

        // $request = request() -> input();
        $request = request()->validate([
            'upc' => ['required'],
        ]);
        $item = Item::where('upc', $request['upc'])->where('user_id', auth()->id())->first();
        $photos = DB::table('photos')->where('upc', $request['upc'])->where('user_id', auth()->id())->get();

        $properties = array(
            "category" => $item->category,
            "description" => $item->description,
            "quantity" => $item->quantity,
            "photos" => $photos,
        );

        //echo json_encode($properties);
        //dd( response()->json($properties));
        return response()->json($properties);

    }

    /* get filename to delete from request, delete that file from DB */
    public function deletePhotoFromItem()
    {
        $request = request()->input();
        $file = $request["delete"];
        $upc = DB::table('photos')->where('filename', $file)->first()->upc;
        try {
            Storage::disk('public')->delete($file);
            DB::table('photos')->where('filename', $file)->delete();
            return to_route('update', ['upc' => $upc])->with(["successMessage", "Photo deleted successfully."]);
        } catch (\Illuminate\Database\QueryException $e) {
            return to_route('update', ['upc' => $upc])->with(["errorMessage", $e->getMessage()]);
        }

    }

/* item page view - gets upc passed in, retrieves item data, photos and number of photos to be used in view */
    public function viewSingleItemPage($upc)
    {
        $item = Item::where('upc', $upc)->where('user_id', auth()->id())->first();
        return view('auth_user_pages.item', array(
            'item' => $item,
            'photos' => DB::table('photos')->where('upc', $upc)->where('user_id', auth()->id())->get(),
            'photoCount' => DB::table('photos')->where('upc', $upc)->where('user_id', auth()->id())->count(),

        ));

    }

    /* gets the upc of item from page request, makes request to the UPCItemDB external API,
    sends back item info on success, or not available message on failure
     */
    public function callUPCitemDBAPI()
    {
        $upc = request()->validate(['upc' => ['required', 'regex:/\d{12}/']]);
        $base_url = "https://api.upcitemdb.com/prod/trial/lookup?upc=";

        /* To make API call work, proper certificate needed to be configured with test server,
        cacert.pem file retrieved from https://curl.se/docs/caextract.html
        set curl.cainfo= in php.ini to path of that file*/
        try {

            $response = Http::withOptions(['verify' => false])->get($base_url . $upc["upc"]);
            $response = json_decode($response); //receiving a json response

            if ($response->code == "OK") {
                return response()->json($response);
            } else {

                return response()->json(["errorMessage" => "UPC is not available for price check."]);
            }
            /* return response()->json($response);  */
        } catch (Exception $e) {
            return response()->json(['errorMessage' => $e->getMessage()]);
        }
    }

/* load contact view */
    public function viewContactPage()
    {

        return view('contact');
    }

    /* get message info from request, attempt to send mail with that info, redirect to contact success page on success */
    public function processContactInfo()
    {
        $mailInfo = request()->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:300', 'email'],
            'subject' => ['required'],
            'message' => ['required', 'max:10000'],
            'g-recaptcha-response' => ['required', 'recaptchav3:processContact,0.5'],
        ]);

        if (Mail::to('inventory@example.com')->send(new mailer($mailInfo))) {
            return to_route("contactSuccess");
        } else {
            return back()->withErrors();
        }

    }

    /* checks for item existence using upc and user_id
    maybe put this in model?
     */
    public function checkIfItemExists($upc, $user_id)
    {

        return Item::where('upc', $upc)->where('user_id', $user_id)->exists();

    }

    /* process a request that calls the above exists function, returns either exists or new item message */
    public function checkIfAddedItemExists()
    {

        $validated = request()->validate([
            'upc' => ['required', 'regex:/\d{12}/', 'size:12'],
        ]);

        $exists = $this->checkIfItemExists($validated['upc'], auth()->id());

        if ($exists) {
            $response = ['exists' => 'Item already exists.'];
        } else {
            $response = ['new' => 'Item does not yet exist.'];
        }

        return response()->json($response);
    }

    /* route that checks the validity of an individual upc, using the UPC validation rule */
    public function checkIfUPCValid()
    {

        $validator = Validator::make(request()->input(), [
            'upc' => ['required', 'regex:/\d{12}/', 'size:12', new UPC],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => 'UPC is invalid']);
        } else {
            return response()->json(['success' => 'UPC is valid.']);
        }

    }

    public function viewCategoriesPage()
    {
        return view('show_categories', ['categories' => Category::orderBy('category', 'ASC')->get()]);
    }
}
