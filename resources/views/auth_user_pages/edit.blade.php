<?php
 use App\Http\Controllers\DBController;
 use App\Models\Item;
 ?>

<!DOCTYPE html>
<html lang="en">
@include('reusable_snippets/page_head') 
<title>Edit Inventory</title>
<script>
                        $(document).ready(function(){
                        $("select#item_selector").change(function(){
                            var idNum = $(this).children("option:selected").val();
                            $("#item_name_update").val($("#".concat(idNum, "_item_name")).text()); 
                            $("#category_update").val($("#".concat(idNum, "_category")).text()); 
                            $("#description_update").val($("#".concat(idNum, "_description")).text()); 
                            $("#quantity_update").val($("#".concat(idNum, "_quantity")).text()); 
                        });

                        $("tr.checkboxInTable").click(function(){
                            console.log(this)
                            var id = $(this).attr("id");
                            if($("#".concat(id,"_checkbox")).is(':checked')){
                                $("#".concat(id,"_checkbox")).prop('checked', false);
                            }else $("#".concat(id,"_checkbox")).prop('checked', true);
                            
                        });
                        });
                </script>
<body class="mb-4">
@include('reusable_snippets/navbar_for_logged_in_pages')
    <div class="container text-center my-4">
        <h1>Edit Inventory</h1>
        <p>Use this page to edit your inventory entries.</p>
        <p>To <b>delete</b> entries, select each item to delete by click on it, then hit the delete button. <br>
        Note: *Deleting items <b>cannot</b> be undone.*</p>
        <p>To <b>add</b> entries, use the <b>Add Item to Inventory</b> box. Enter information and hit Add.</p>

        <p>To <b>update</b> entries in the inventory, use the <b>Update Item</b> box.<br>
            Select the item you want to update from the dropdown list, and its info will be displayed.<br>
            Change the desired info and hit Update to submit changes.
        </p>
    </div>

        <div class="container">
            <div class="col-sm-8 text-center mx-auto"> 
                      @if(DB::table('items')->where('user', [auth()->user()->username])->exists())
                        
                
                <form method="post" action="/delete_item">
                    @csrf
                    <table class="table table-bordered table-hover overflow-auto">
                        <thead class="thead-light">
                            <tr> 
                                <th>Item</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Delete?</th>
                            </tr>
                        </thead>
                
                    
                  @foreach(Item::where('user',auth()->user()->username)->get() as $item)
                    <tr id="<?=$item->id?>" class="checkboxInTable">
                        <td id="{{$item->id}}_item_name">{{$item->name}}</td>
                        <td id="{{$item->id}}_category">{{$item->category}}</td>
                        <td id="{{$item->id}}_description">{{$item->description}}</td>
                        <td id="{{$item->id}}_quantity">{{$item->quantity}}</td>
                        <td id="{{$item->id}}_checkbox"><input type="checkbox"  value="{{$item->id}}" name="{{$item->id}}">
                    </tr>
            
                    @endforeach
                    <tr>
                    <td></td><td></td><td></td><td></td>
                    <td><button type="submit" class="btn btn-danger mx-auto">Delete</button></td>
                    </tr>
                    </table> 
                    @else({{<p><b>There are currently no entries in the inventory.</b></br>Add items using the form below.</p>}})
                    @endif
                </form>
            </div>
        </div>

        <div class="container mb-4">
            <div class="row">
                <div class="col-6">
                    <div class="card text-center">
                        <div class="card-header text-center font-weight-bold">Add Item to Inventory</div>
                        <div class="card-body">
                            <form method="post" action="/add_item" >
                                @csrf
                                <div class="container">
                                    <div class="form-group">
                                        
                                        <label for="item_name" class="mb-2 mr-sm-2">Item name: </label>
                                        <input class="form-control mb-2 mr-sm-2 col-sm" type = "text" name="name" id="item_name" placeholder="Item Name" required>
                                        
                                        <label for="category" class="mb-2 mr-sm-2">Category:</label>
                                        <input class="form-control mb-2 mr-sm-2 col-sm" name="category" id="category" placeholder="Category" required>
                                            
                                        <label for="description" class="mb-2 mr-sm-2">Description (Optional): </label>
                                        <textarea class="form-control mb-2 mr-sm-2 col-sm" rows="2" cols ="4" name="description" placeholder="Description" id="description"></textarea>
                                        
                                        <label for="quantity" class="mb-2 mr-sm-2">Quantity:</label>
                                        <input class="mb-2 mr-sm-2 col-sm" type="number" name="quantity" id="quantity" min="1" value="1" required>

                                    </div>
                                    <button type="submit" class="btn btn-primary mx-auto">Add</button>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div> 
               
                <div class="col-6">
                    <div class="card text-center">
                        <div class="card-header font-weight-bold">Update Item</div>
                        <div class="card-body">
                        <form action="/update_item" method="post">
                            @csrf
                            <label for="item_selector" class="mb-2 mr-sm-2 ">Choose item to change:</label>
                            <select name="item_selector" class="col-sm-8 mb-2 mr-sm-2" id="item_selector">
                                    
                                    <option hidden disabled selected value> -- select an item -- </option>
                                    @foreach(Item::where('user',auth()->user()->username)->get() as $item)
                                    <option id="{{$item->id}}_option" value={{$item->id}}>{{$item->name}}</option>
                                    @endforeach
                                    
                            </select>
                            
                                <div class="container">
                                    <div class="form-group">
                                        
                                        <label for="item_name_update" class="mb-2 mr-sm-2">Item name: </label>
                                        <input class="form-control mb-2 mr-sm-2 col-sm" type = "text" name="name" id="item_name_update"  required>
                                        
                                        <label for="category_update" class="mb-2 mr-sm-2">Category:</label>
                                        <input class="form-control mb-2 mr-sm-2 col-sm" name="category" id="category_update"  required>
                                            
                                        <label for="description_update" class="mb-2 mr-sm-2">Description (Optional): </label>
                                        <textarea class="form-control mb-2 mr-sm-2 col-sm" rows="2" cols ="4" name="description"  id="description_update"></textarea>
                                        
                                        <label for="quantity_update" class="mb-2 mr-sm-2">Quantity:</label>
                                        <input class="mb-2 mr-sm-2 col-sm" type="number" name="quantity" id="quantity_update" min="1" required>

                                    </div>
                                    <button type="submit" class="btn btn-primary mx-auto">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>