@extends('layouts.master')

@section('content')
<div class="container text-center my-4">
    <h1>Statistics</h1>
    <p>You currently have <b>{{$itemCount}}</b> unique items stored in your inventory.</p>
</div>
@if($itemCount > 0)
<div class="container text-center my-4">
    <h3 class="mb-4">Categories by the Numbers:</h3>
    <table class="table table-bordered text-center table-striped table-responsive-sm">
        <thead class="thead steelblueBG">
            <tr>
                <th>Category</th>
                <th>Items Stored</th>
                <th>Percent of Total Items</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $catName => $catCount)
            <tr>
                <td>{{$catName}}</td>
                <td>{{$catCount}}</td>
                <td>{{round($catCount / $itemCount, 4)*100}}%</td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endif
@endsection