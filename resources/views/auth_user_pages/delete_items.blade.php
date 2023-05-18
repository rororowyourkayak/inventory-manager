

@extends('layouts.master')

@section('content')
<div class="container text-center my-4">
    <h1>Delete Items</h1>
    <p>Use the table below to delete items from the inventory.</p>
    <p>To select multiple items to delete, select desired rows and press Delete All Selected Items. </p>
    <p>To delete individual items, press the red delete button in the row of the item.</p>
    <p>Note: Deleting items <b>cannot</b> be undone.</p>
</div>
@if(session()->has('successMessage'))
<div class="col-sm-8 mx-auto">
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    {{session()->get('successMessage')}}
    </div>
</div>

@elseif(session()->has('errorMessage'))
<div class="col-sm-8 mx-auto">
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        {{session()->get('errorMessage')}}
    </div>
</div>
@endif 

<div id="messageContainer" class="container col-sm-8 mx-auto"> </div>

<div class="container my-4">
            <div class="col-sm-10 text-center mx-auto">
                @if($itemsExist)
                  
                <div class="container my-2"> 
                   <button id="selectAllItemsButton" class="btn btn-primary">Select All Items</button> 
                   <button id="unselectAllItemsButton" class="btn btn-primary">Unselect All Items</button>
                </div>

                <div class="container">
                  <button class="btn btn-danger" id="selectAllDeleteButton"> Delete All Selected Items</button>  
                  <p id="selectedTableItemsToDelete"></p>  
                </div>
                       
                    <!-- <form action="/delete_multiple_items" method="POST" id="massDeleteForm"> -->
                    <input type="hidden" name="upc[]">
                        <table id="itemsTable" class="table table-bordered text-center table-striped table-responsive-sm">
                            <thead class="thead text-white steelblueBG">
                                <tr>
                                    <th>UPC #</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Delete?</th>
                                  
                                </tr>
                            </thead>
                    @foreach($data as $item)
                        <tr id="{{$item->upc}}"class="checkboxInTable">
                            <td id="{{$item->upc}}_upc">{{$item->upc}}</td>
                            <td id="{{$item->upc}}_category">{{$item->category}}</td>
                            <td id="{{$item->upc}}_description">{{$item->description}}</td>
                            <td id="{{$item->upc}}_quantity">{{$item->quantity}}</td>
                            <td>
                            <form action="/delete_item" method="post">
                                @csrf
                                <input type="hidden" name="delete" value="{{$item->upc}}">
                                <button type="submit" class="btn btn-danger mx-auto">Delete</button>
                            </form>
                         </td>                      
                        </tr>
                        @endforeach
                        </table>
                    <!-- </form> -->
                    @else <p> <b>There are currently no entries in the inventory.</b></br>Add Items on the <a href="/add">Add page</a>.</p>
                    @endif
            </div>
</div>
@endsection

@section('scripts')

<!-- datatables links -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

<!-- datatables highlight row select libraries -->
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.2/css/select.dataTables.min.css">
<script src="https://cdn.datatables.net/select/1.6.2/js/dataTables.select.min.js"></script>

<script>
    $(document).ready( function () {

   var table = $('#itemsTable').DataTable( {
        select:{
            style: 'mutli'
          },
        columnDefs: [
                { orderable: false, targets: [4/*, 5 */],
                
                }
            ]
    });


    $('#selectedTableItemsToDelete').html("<b>"+table.rows('.selected').data().length + "</b> row(s) selected to delete"); //intital value display for 0 items selected

    //select rows on click
    $('#itemsTable tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
        $('#selectedTableItemsToDelete').html("<b>"+table.rows('.selected').data().length + "</b> row(s) selected to delete"); 
    });

     //select and unselect rows with these buttons
     $("#selectAllItemsButton").on('click', function(){
        $("tr").addClass('selected');
    });
    $("#unselectAllItemsButton").on('click', function(){
        $("tr").removeClass('selected');
    });

    //delete button for deleting selected items
    $("#selectAllDeleteButton").on('click', function (){

        var formData = new FormData(); 
       
        
      //iterates rows().every() iterates over the entire selection of rows, but does not give properties 
        table.rows('.selected').every( function(){
            formData.append("upc[]", this.data()[0]);
        });

        $.ajax({
            method: "POST",
            url: "/delete_multiple_items",
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
               //console.log(response);
               location.reload();
                $("#messageContainer").html(response[0]);

            }
        }); 
    });

   
}); 
</script>

@endsection

<!-- /* $(".checkboxInTable").click(function(){
        var box = $(this).children("td").children("input:checkbox"); 
        if(box.is(':checked')){
            box.prop('checked', false);
        }else{box.prop('checked', true);}
    
     });
     //this function is to make the checkbox work as it should, not sure why it doesn't work without this 
    $(".checkbox").click(function(){
        if($(this).is(':checked')){
            $(this).prop('checked', false);
        }else{
            $(this).prop('checked', true);
        }
    });   

    //this will select all delete checkboxes 
    $("#selectAllBox").click(function(){
     if($(this).is(':checked')){
        $(".checkbox").prop('checked', true);
    }
    else{
        $(".checkbox").prop('checked', false);
    }
    }); */


    
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
    */ -->