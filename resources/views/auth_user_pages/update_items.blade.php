<?php
 use App\Models\Item;
 ?>
@extends('layouts.master')

@section('content')
<div class="container text-center my-4">
    <h1>Update Items</h1>
    <p>Use the box below to add update entries in the inventory.</p>
</div>

<div class="container my-4">
            <div class="row">
                <div class="col-8 mx-auto">
                    <div class="card text-center">
                        <div class="card-header fw-bold">Update Item</div>
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
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $("#item_selector").change(function(){
            var idNum = $(this).children("option:selected").val();
                $("#item_name_update").val($("#".concat(idNum, "_item_name")).text()); 
                console.log($("#item_name_update").val());
                $("#category_update").val($("#".concat(idNum, "_category")).text()); 
                $("#description_update").val($("#".concat(idNum, "_description")).text()); 
                $("#quantity_update").val($("#".concat(idNum, "_quantity")).text()); 
        });
    });
</script>
@endsection