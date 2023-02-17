@extends('layouts.master')

@section('content')
<div class="container text-center my-4">
    <h1>Add Items</h1>
    <p>Use the box below to add new entries to the inventory.</p>
</div>
<div class="container my-4">
            <div class="row">
                <div class="col-6 mx-auto">
                    <div class="card text-center">
                        <div class="card-header text-center fw-bold">Add Item to Inventory</div>
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
            </div>
</div>

@endsection