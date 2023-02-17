
<?php
 use App\Models\Item;
 ?>
@extends('layouts.master')

@section('content')
<div class="container text-center my-4">
    <h1>Delete Items</h1>
    <p>Use the table below to delete items from the inventory.</p>
</div>
<div class="container my-4">
            <div class="col-sm-8 text-center mx-auto"> 
                      @if(DB::table('items')->where('user', [auth()->user()->username])->exists())
                        
                
                <form method="post" action="/delete_item">
                    @csrf
                    <table class="table table-bordered text-center table-striped table-responsive-sm">
                        <thead class="thead" style="background-color:steelblue; color:white;">
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
                        <td><input id="{{$item->id}}_check" type="checkbox" value="{{$item->id}}" name="{{$item->id}}">
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

@endsection
@section('scripts')
<script>
     $(document).ready(function(){
        $("tr.checkboxInTable").click(function(){                
            var id = $(this).attr("id");
            if($("#".concat(id,"_check")).is(':checked')){
                $("#".concat(id,"_check")).prop('checked', false);
            }
            else $("#".concat(id,"_check")).prop('checked', true);
        });
    });
</script>
@endsection