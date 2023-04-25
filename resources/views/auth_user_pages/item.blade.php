@extends('layouts.master')

@section('content')



<div class="container my-2 text-center">
    <h1>{{$item->name}}</h1>
    <div class="row">
    <div class="col-sm-6 my-2 mx-auto">
        <div class="card h-100 text-center">
            <div class="card-header fw-bold">Item Details</div>
            <div class="card-body text-center">
                <h6>Category: {{$item->category}}</h6>
                <h6>Quantity: {{$item->quantity}}</h6>
                <h6>Description: {{$item -> description}}</h6>
                <h6>Last Updated: {{$item -> updated_at}}</h6>
            </div>
        </div>
    </div>

    <div class="col-sm-6 my-2 text-center mx-auto">
        <div class="card h-100">
            <div class="card-header fw-bold">Photos</div>
            <div class="card-body">
                @if($photoCount == 0)
                <p>No photos are currently stored for this item.</p>


                @elseif($photoCount > 0)
                <div id="photoDisplayer" class="carousel slide" data-bs-ride="carousel">

                    <div class="carousel-indicators">
                        @for($i = 0; $i < $photoCount; $i++) @if($i==0) <button type="button"
                            data-bs-target="#photoDisplayer" data-bs-slide-to={{$i}} class="active"></button>
                            @else
                            <button type="button" data-bs-target="#photoDisplayer" data-bs-slide-to={{$i}}
                                class=""></button>
                            @endif
                            @endfor
                    </div>

                    <div class="carousel-inner">
                        @php $counterForIfFirst = 0@endphp
                        @foreach($photos as $photo)
                        @if($counterForIfFirst == 0)
                        <div class="carousel-item active">
                            <img src="{{url($photo->filename)}}" alt="photo" class="d-block h-100 w-100" style="max-height:300px">
                        </div>
                        @else
                        <div class="carousel-item">
                            <img src="{{url($photo->filename)}}" alt="photo" class="d-block h-100 w-100" style="max-height:300px">
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#photoDisplayer"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#photoDisplayer"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
    </div>
    

</div>

@endsection