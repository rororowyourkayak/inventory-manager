
<?php use App\Models\Item; ?>
@extends('layouts.master')

<title>Inventory Home</title>

@section('content')

    
<div class="container text-center my-4"> 
            <h1>Inventory Home</h1>
            <p>Welcome back {{auth()->user()->name}}! View your inventory below.</p>
            
</div>
    
<div class="container">
    <div class="col-sm-8 mx-auto overflow-auto">
    
        @if(DB::table('items')->where('user', [auth()->user()->username])->exists())

        <table class="table table-bordered text-center table-striped table-responsive-sm">
            <thead class="thead" style="background-color:steelblue; color:white;">
                <tr> 
                    <th>Item</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Quantity</th>
                </tr>
            </thead>
                            
            @foreach(Item::where('user',auth()->user()->username)->get() as $item)
                <tr>
                    <td>{{$item->name}}</td>
                    <td>{{$item->category}}</td>
                    <td>{{$item->description}}</td>
                    <td>{{$item->quantity}}</td>
                </tr>    
            @endforeach
                        
        </table> 
            
            @else({{<p><b>There are currently no entries in the inventory.</b><br>Add Items on the <a href="edit">Edit page</a>.</p>}})                                   
            @endif
    </div>    
</div>
                
@endsection