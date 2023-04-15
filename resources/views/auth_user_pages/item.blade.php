@extends('layouts.master')

@section('content')

<div class="my-4 container text-center">
    <h1>{{$item->name}}</h1>
    
</div>

<div class="container my-2">
        <div class="col-sm-8 mx-auto">
        <div class="card text-center">
            <div class="card-header fw-bold">Item Details</div>
            <div class="card-body text-center">
                <h6>Category: {{$item->category}}</h6>
                <h6>Quantity: {{$item->quantity}}</h6>
                <h6>Description: {{$item -> description}}</h6>
                <h6>Last Updated: {{$item -> updated_at}}</h6>
            </div>
</div>
        </div>
    </div>
</div>
<div class="container text-center">
    <h3>Photos: </h3>
    <div class="row">
    @if($photoCount == 0)
    <p>No photos are currently stored for this item.</p>
    @endif
        @foreach($photos as $photo)
         <!-- <img src="{{public_path($photo->filename)}}" alt="photo">  -->
         <img src={{url($photo->filename)}} alt="photo"> 
        @endforeach

    
    </div>
</div>


@endsection

