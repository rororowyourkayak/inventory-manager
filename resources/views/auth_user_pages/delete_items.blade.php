

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
                    <tr>
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
                    </tr>
                    @endforeach
                    </table>
                    @else <p> <b>There are currently no entries in the inventory.</b></br>Add Items on the <a href="/add">Add page</a>.</p>
                    @endif


            </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

<script>
    $(document).ready( function () {
    $('#itemsTable').DataTable( {
  columnDefs: [
    { orderable: false, targets: 4 }
  ]
});
} );
</script>

@endsection
