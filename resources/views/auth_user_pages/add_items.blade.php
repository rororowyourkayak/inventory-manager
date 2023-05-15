@extends('layouts.master')

@section('content')


<div class="container text-center my-4">
    <h1>Add Items</h1>
    <p>Use the box below to add new entries to the inventory.</p>
    <p>UPC must be twelve numbers long.</p>
    <p>Photo uploads must be .png, .jpg, or .jpeg.<br>
       Storage amount of photos may not exceed 2MB in total. 
    </p>
</div>

<div class="col-sm-8 mx-auto">
@if(session()->has('existsMessage'))

<div class="alert alert-info  alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  {{session()->get('existsMessage')}}
</div>

@elseif(session()->has('successMessage'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  {{session()->get('successMessage')}}
</div>
@endif 
</div>

<div class="container my-4">
            <div class="row">
                <div class="col-8 mx-auto">
                    <div class="card text-center">
                        <div class="card-header text-center fw-bold">Add Item to Inventory</div>
                        <div class="card-body">
                            @foreach($errors->all() as $error)
                                <p class="text-danger text-center mt-1">{{$error}}</p>
                            @endforeach
                            <form method="post" action="/add_item" enctype="multipart/form-data">
                                @csrf
                                <div class="container">
                                    <div class="form-group">
                                        
                                        <label for="item_upc" class="mb-2 mr-sm-2">Item UPC: </label>
                                        <input class="form-control mb-2 mr-sm-2 col-sm" type = "text" name="upc" id="item_upc" placeholder="Item UPC" required value="{{ old('upc') }}">
                                        
                                        <label for="category" class="mb-2 mr-sm-2">Category:</label>
                                        <select name="category" id="category" class="form-control col-sm mb-2 mr-sm-2" required value="{{ old('category') }}">
                                        <option hidden disabled selected value> -- select a category -- </option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->category}}">{{$category->category}}</option>
                                        @endforeach
                                        </select>

                                        <label for="description" class="mb-2 mr-sm-2">Description (Optional): </label>
                                        <textarea class="form-control mb-2 mr-sm-2 col-sm" rows="2" cols ="4" name="description" placeholder="Description" id="description" value="{{ old('description') }}"></textarea>
                                        
                                        <label for="quantity" class="mb-2 mr-sm-2">Quantity:</label>
                                        <input class="form-control mb-2 mr-sm-2 col-sm" type="number" name="quantity" id="quantity" min="1" value="1" required value="{{ old('quantity') }}">

                                        <label for="file" class="mb-2 mr-sm-2">Upload Photos (Optional):</label>
                                        <input class="form-control mb-2 mr-sm-2 col-sm" type="file" name="file[]" id="file" accept=".png, .jpg, .jpeg" multiple>

                                    </div>
                                    <button type="submit" class="btn btn-primary mx-auto">Add</button>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div> 
            </div>
</div>

@endsection

