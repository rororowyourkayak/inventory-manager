
@extends('layouts.master')

<title>Inventory Home</title>

@section('content')


<div class="container text-center my-4">
            <h1>Inventory Home</h1>
            <p>Welcome back {{auth()->user()->name}}! View your inventory below.</p>

</div>

<div class="container">
    <div class="col-sm-10 mx-auto overflow-auto text-center">

        @if($itemsExist)

        <table id="itemsTable" class="table table-bordered text-center table-striped table-responsive-sm">
            <thead class="thead" style="background-color:steelblue; color:white;">
                <tr>
                    <th>UPC #</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Quantity</th>
                </tr>
            </thead>

            @foreach( $data as $item)
                <tr>
                    <td><a href="/items/{{$item->upc}}">{{$item->upc}}</a></td>
                    <td>{{$item->category}}</td>
                    <td>{{$item->description}}</td>
                    <td>{{$item->quantity}}</td>

                </tr>
            @endforeach

        </table>
            @else <p><b>There are currently no entries in the inventory.</b><br>Add Items on the <a href="/add">Add page</a>.</p>
            @endif
    </div>
</div>

@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

<script>
    $(document).ready( function () {
    $('#itemsTable').DataTable();
} );
</script>

@endsection
