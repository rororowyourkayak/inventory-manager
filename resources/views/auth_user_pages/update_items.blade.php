
@extends('layouts.master')

@section('content')

<div class="container text-center my-4">
    <h1>Update Items</h1>
    <p>Use the box below to add update entries in the inventory.</p>
</div>

@if(session()->has('successMessage'))
<div class="col-sm-8 mx-auto">
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    {{session()->get('successMessage')}}
    </div>
</div>

@endif 

<div class="container my-4">
            <div class="row">
                <div class="col-8 mx-auto">
                    <div class="card text-center">
                        <div class="card-header fw-bold">Update Item</div>
                        <div class="card-body">
                        @foreach($errors->all() as $error)
                                <p class="text-danger text-center mt-1">{{$error}}</p>
                        @endforeach
                        <form action="/update_item" method="post" enctype="multipart/form-data">
                            @csrf
                            <label for="item_selector" class="mb-2 mr-sm-2 ">Choose item to change:</label>
                            <select name="item_selector" class="col-sm-8 mb-2 mr-sm-2" id="item_selector">
                                    
                                    <option hidden disabled selected value> -- select an item -- </option>
                                    @foreach($data as $item)
                                    <option id="{{$item->upc}}_option" value="{{$item->id}}" class="optionBar">#{{$item->upc}} - {{$item->description}}</option>
                                    @endforeach
                                    
                            </select>
                            
                                <div class="container">
                                    <div class="form-group">
                                        
                                        <label for="category" class="mb-2 mr-sm-2">Category:</label>
                                        <select name="category" id="category_update" class="form-control col-sm mb-2 mr-sm-2" required>
                                        <option hidden disabled selected value> -- select a category -- </option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->category}}">{{$category->category}}</option>
                                        @endforeach
                                        </select>

                                        <label for="description_update" class="mb-2 mr-sm-2">Description (Optional): </label>
                                        <textarea class="form-control mb-2 mr-sm-2 col-sm" rows="2" cols ="4" name="description"  id="description_update"></textarea>
                                        
                                        <label for="quantity_update" class="mb-2 mr-sm-2">Quantity:</label>
                                        <input class="form-control mb-2 mr-sm-2 col-sm" type="number" name="quantity" id="quantity_update" min="1" required>

                                        <label for="file" class="mb-2 mr-sm-2">Upload Photos (Optional):</label>
                                        <input class="form-control mb-2 mr-sm-2 col-sm" type="file" name="file[]" id="file" accept=".png, .jpg, .jpeg" multiple>

                                    </div>
                                    <button type="submit" class="btn btn-primary mx-auto">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                </div> 
                
            </div>
            <div class="container" id="photoDelete">

            </div>
            
</div>
@endsection


@section('scripts')
<!-- //links for the select2 jquery library -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function(){
        $("#item_selector").select2(); 
        
        $("#item_selector").change(function(){
            var id = $(this).children("option:selected").val();
                $.ajax({ method: "GET", 
                    url: "/updateLoader", 
                    data: {id: id},
                    success: function(result){
                    console.log(result);
                    var itemData = result; 
                   /*  var itemData = JSON.parse(result);
                    console.log(itemData);  */
                    $("#category_update").val(itemData["category"]);
                    $("#description_update").val(itemData["description"]);
                    $("#quantity_update").val(itemData["quantity"]);

                    $("#photoDelete").html("");
                    
                    if(itemData["photos"][0]){

                        $("#photoDelete").html("<table id=\"deleteTable\" class=\"table table-bordered text-center table-striped table-responsive-sm\"></table>");
                        $("#deleteTable").html("<thead class=\"steelblueBG\"><tr>\"<th>Photo</th><th>Filename</th><th>Delete?</th>\"</tr></thead>");

                        var photos = itemData["photos"]; 
                        
                         for(var photo of photos){
                            var photoDeleteForm = `<form action="/delete_item_photo" method="post">
                            @csrf
                            <input type="hidden" name="delete" value=${photo["filename"]}>
                            <button type="submit" class="btn btn-danger mx-auto">Delete</button>
                         </form>`;
                            $("#deleteTable").append(`<tr> <td><img src=${photo["filename"]} id=${photo["filename"]} height=100 width=100 alt="photo"></td> <td>${photo["original_name"]}</td> <td>${photoDeleteForm}</td> </tr>`);
                        } 
                    } 
                   
                } });
        });
        
    });
</script>
@endsection