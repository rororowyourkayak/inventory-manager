@extends('layouts.master')

@section('content')
<div class="container text-center my-4">
    <h1>Delete Items</h1>
    <p>Use the table below to delete items from the inventory.</p>
    <p>To select multiple items to delete, select desired rows and press Delete All Selected Items. </p>
    <p>Note: Deleting items <b>cannot</b> be undone.</p>
</div>

<div class="container mx-auto text-center" id="messageContainer"></div>

<div class="container my-4">
    <div class="col-sm-10 text-center mx-auto">
        @if($itemsExist)



        <div class="container">
            <button class="btn btn-danger" id="selectAllDeleteButton"> Delete All Selected Items</button>
            <p id="selectedTableItemsToDelete"></p>
        </div>


        <table id="itemsTable" class="table table-bordered text-center table-striped table-responsive-sm">
            <thead class="thead text-white steelblueBG">
                <tr>
                    
                    <th>UPC #</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    {{-- <th>Delete?</th> --}}
                    <th>Select</th>

                </tr>
            </thead>
            @foreach($data as $item)
            <tr class="checkboxInTable">
                
                <td>{{$item->upc}}</td>
                <td>{{$item->category}}</td>
                <td>{{$item->description}}</td>
                <td>{{$item->quantity}}</td>
               {{--  <td> <button id="{{$item->upc}}" class="singleItemDeleteButton btn btn-danger mx-auto">Delete</button>
                </td> --}}
                <td></td>
            </tr>
            @endforeach
        </table>

        @else <p> <b>There are currently no entries in the inventory.</b><br>Add Items on the <a href="/add">Add
                page</a>.</p>
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

<!-- datatables buttons link -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>

<script>
    $(document).ready( function () {

   var table = $('#itemsTable').DataTable( {
        dom: 'Blfrtip',
        select:{
            style: 'mutli'
          },
        columnDefs: [
                { orderable: false, targets: [4],},
                   { className: 'select-checkbox', targets:  4
                }
            ],
            buttons: [
            {
                text: 'Select all',
                action: function () {
                    table.rows().select();
                    $('#selectedTableItemsToDelete').html("<b>"+table.rows('.selected').data().length + "</b> row(s) selected to delete"); 

                }
            },
            {
                text: 'Unselect all',
                action: function () {
                    table.rows().deselect();
                    $('#selectedTableItemsToDelete').html("<b>"+table.rows('.selected').data().length + "</b> row(s) selected to delete"); 

                }
            }
        ]
    });

  
    

    $('#selectedTableItemsToDelete').html("<b>"+table.rows('.selected').data().length + "</b> row(s) selected to delete"); //intital value display for 0 items selected

    //select rows on click
    $('#itemsTable tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
        $('#selectedTableItemsToDelete').html("<b>"+table.rows('.selected').data().length + "</b> row(s) selected to delete"); 
    });


    //delete button for deleting all selected items
    $("#selectAllDeleteButton").on('click', function (){
        var length = table.rows('.selected').data().length;
        if(length > 0){

            if(confirm(`Are you sure you want delete ${length} items?`)){
            const formData = new FormData(); 
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
                
                table.rows('.selected').remove().draw(false);
                $('#selectedTableItemsToDelete').html("<b>"+table.rows('.selected').data().length + "</b> row(s) selected to delete");
                $("#messageContainer").text(response.success);
                }
            }); 
        }
    }
        else{ alert("No items were selected.");}
    });

   /*  $(".singleItemDeleteButton").on('click', function(){

        $(this).parent().parent().addClass("selectedForSingleDelete");
        var upc = table.row( $(this).parent().parent()).data()[0]; 

       
        if(confirm(`Are you sure you want to delete UPC# ${upc}?`)){
            
            const formData = new FormData(); 
            formData.append("upc", upc); 
            
            $.ajax({
                method: "POST",
                url:  "/delete_single_item",
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }, 
                data: formData, 
                processData: false,
                contentType: false,
                success: function(response){
                    if(response.success){
                        $("#messageContainer").text(response.success);
                        table.row(".selectedForSingleDelete").remove().draw(false);
                         $('#selectedTableItemsToDelete').html("<b>"+table.rows('.selected').data().length + "</b> row(s) selected to delete");
                    }
                    else if (response.error){
                        $("#messageContainer").text(response.error);
                    }
                }

            });
        }
        else{
             $(this).parent().parent().removeClass("selectedForSingleDelete");
        }
    }) */

   
}); 
</script>

@endsection

<!-- /* $(".checkboxInTable").click(function(){
        var box = $(this).children("td").children("input:checkbox"); 
        if(box.is(':checked')){
            box.prop('checked', false);
        }else{box.prop('checked', true);}
    
     });

    */ -->