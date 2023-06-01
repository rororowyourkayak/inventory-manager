@extends('layouts.master')

@section('content')

<div class="container text-center my-2">
    <h1>Supported Categories</h1>
    <p>These are the categories supported by the Inventory Manager: </p>
</div>


<div class="row">
    <div class="container mx-auto text-center col-sm-10">
        @foreach($categories as $cat)
            <div class="col-sm-3 mx-auto">
                <li>{{$cat->category}}</li>
            </div>
           
        @endforeach
    </div>
</div>

<div class="container text-center">
    <p>Don't see a category you want to use?<br>
        Put in a suggestion using the <a href="/contact">contact</a> page.
    </p>
</div>



@endsection