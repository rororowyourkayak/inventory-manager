@extends('layouts.master')

<title>Inventory Home</title>

@section('content')


<div class="container text-center my-4">
    <h1>Inventory Home</h1>
    <p>Welcome back {{auth()->user()->name}}! View your inventory below.</p>

</div>

<div class="container">
    <div class="col-sm-10 mx-auto overflow-auto text-center">

        {{-- wrap in if statement because we don't want a table showing if there are no items to display in it  --}}
        @if($itemsExist)
        <table id="itemsTable" class="table table-bordered text-center table-striped table-responsive-sm">
            <thead class="thead steelblueBG">
                <tr>
                    <th>UPC #</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Last Updated At</th>
                </tr>
            </thead>

            {{-- $data is passed in variable to view holding all of the items in inventory --}}
            @foreach( $data as $item)
            <tr>
                <td><a href="/items/{{$item->upc}}">{{$item->upc}}</a></td>
                <td>{{$item->category}}</td>
                <td>{{$item->description}}</td>
                <td>{{$item->quantity}}</td>
                <td>{{$item->updated_at->format('m-d-Y h:m:s')}}</td>
                {{-- format function just changes the look of the time dispalyed to the user --}}
            </tr>
            @endforeach

        </table>
        @else <p><b>There are currently no entries in the inventory.</b><br>Add Items on the <a href="/add">Add
                page</a>.</p>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

<script>
    //make the items display table an off-the-shelf DataTable
    $(document).ready( function () {
    $('#itemsTable').DataTable();
} );
</script>

@endsection