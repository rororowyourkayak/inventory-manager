@extends('layouts.master')

@section('content')
<div class="container text-center my-4">
    <h1>Statistics</h1>
    <p>View stats based on the items you stored.</p>
</div>
<div class="container text-center my-4">
            <h3 class="mb-4">Categories by the numbers:</h3>
            <table class="table table-bordered text-center table-striped table-responsive-sm">
                <thead class="thead steelblueBG">
                    <tr>
                        <th>Category</th>
                        <th>Items Stored</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $catName => $catCount)
                        <tr>
                            <td>{{$catName}}</td>
                            <td>{{$catCount}}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
@endsection