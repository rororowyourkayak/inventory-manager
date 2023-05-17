

@extends('layouts.master')

@section('content')
<div class="container text-center my-4">
    <h1>Delete Items</h1>
    <p>Use the table below to delete items from the inventory.</p>
    <p>Note: Deleting items <b>cannot</b> be undone.</p>
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
            <div class="col-sm-10 text-center mx-auto">
                @if($itemsExist)

                    <form action="/delete_item" method="POST">
                        @csrf
                        <table id="itemsTable" class="table table-bordered text-center table-striped table-responsive-sm">
                            <thead class="thead text-white steelblueBG">
                                <tr>
                                    <th>UPC #</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Delete?</th>
                                    <th><span><input type="checkbox" name="" id="selectAllBox"></span></th>
                                </tr>
                            </thead>
                    @foreach($data as $item)
                        <tr id="{{$item->id}}"class="checkboxInTable">
                            <td id="{{$item->id}}_upc">{{$item->upc}}</td>
                            <td id="{{$item->id}}_category">{{$item->category}}</td>
                            <td id="{{$item->id}}_description">{{$item->description}}</td>
                            <td id="{{$item->id}}_quantity">{{$item->quantity}}</td>
                            <td>
                            <form action="/delete_item" method="post">
                                @csrf
                                <input type="hidden" name="delete" value="{{$item->id}}">
                                <button type="submit" class="btn btn-danger mx-auto">Delete</button>
                            </form>
                         </td>
                          <td><input type="checkbox" name="{{$item->id}}" id="{{$item->id}}_check" value="{{$item->id}}" class="checkbox"></td> 
                         
                        </tr>
                        @endforeach
                        </table>
                    </form>
                    @else <p> <b>There are currently no entries in the inventory.</b></br>Add Items on the <a href="/add">Add page</a>.</p>
                    @endif
            </div>
</div>
@endsection

@section('scripts')

<!-- datatables links -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

<script>
    $(document).ready( function () {

    $('#itemsTable').DataTable( {
  columnDefs: [
         { orderable: false, targets: [4,5],
          
        }
    ]
    });
    //https://www.gyrocode.com/articles/jquery-datatables-how-to-add-a-checkbox-column/
    

    $(".checkboxInTable").click(function(){
        var box = $(this).children("td").children("input:checkbox"); 
        if(box.is(':checked')){
            box.prop('checked', false);
        }else{box.prop('checked', true);}
    
     });
     //this function is to make the checkbix work as it should, not sure why it doesn't work without this 
    $(".checkbox").click(function(){
        if($(this).is(':checked')){
            $(this).prop('checked', false);
        }else{
            $(this).prop('checked', true);
        }
    });   

    $("#selectAllBox").click(function(){
     if($(this).is(':checked')){
        $(".checkbox").prop('checked', true);
    }
    else{
        $(".checkbox").prop('checked', false);
    }
    });

    
    //this only works clicking anywhere in the row, but not on the checkbox itself

   /*  $(".checkboxInTable").click(function(){                  
        /* var id = $(this).attr("id");

        if($("#".concat(id,"_check")).is(':checked')){
            $("#".concat(id,"_check")).prop('checked', false);
        }else $("#".concat(id,"_check")).prop('checked', true);
    
 
          
    });

    //this function is to make the checkbix work as it should, not sure why it doesn't work without this 
    $(".checkbox").click(function(){
        if($(this).is(':checked')){
            $(this).prop('checked', false);
        }else{
            $(this).prop('checked', true);
        }
    });   
    */
}); 
</script>

@endsection
