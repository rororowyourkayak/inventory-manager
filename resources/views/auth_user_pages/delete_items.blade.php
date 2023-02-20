
<?php
 use App\Models\Item;
 ?>
@extends('layouts.master')

@section('content')
<div class="container text-center my-4">
    <h1>Delete Items</h1>
    <p>Use the table below to delete items from the inventory.</p>
    <p>Note: Deleting items <b>cannot</b> be undone.</p>
</div>
<div class="container my-4">
            <div class="col-sm-10 text-center mx-auto"> 
                @if(DB::table('items')->where('user', [auth()->user()->username])->exists())
                    <table class="table table-bordered text-center table-striped table-responsive-sm">
                        <thead class="thead text-white steelblueBG">
                            <tr> 
                                <th>Item</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Delete?</th>
                            </tr>
                        </thead>
                  @foreach(Item::where('user',auth()->user()->username)->get() as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->category}}</td>
                        <td>{{$item->description}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>
                        <form action="/delete_item" method="post">
                            @csrf
                            <input type="hidden" name="delete" value="{{$item->id}}">
                            <button type="submit" class="btn btn-danger mx-auto">Delete</button>
                        </form>
                    </td>
                    </tr>
                    @endforeach
                    </table> 
                    @else({{<p><b>There are currently no entries in the inventory.</b></br>Add items using the form below.</p>}})
                    @endif
            
            </div>
</div>
@endsection

