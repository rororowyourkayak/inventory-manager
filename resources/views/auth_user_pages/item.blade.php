@extends('layouts.master')

@section('content')



<div class="container my-2 text-center">
    <h1>#{{$item->upc}}</h1>
    <button class="btn btn-primary my-2" onClick="location.href = '/update?upc={{$item->upc}}'">Update This Item</button>
    <div class="row">
        <div class="col-sm-6 my-2 mx-auto">
            <div class="card h-100 text-center">
                <div class="card-header fw-bold">Item Details</div>
                <div class="card-body text-center">

                    {{-- item is passed into the view, here page is displaying the info in a card --}}
                    <h6>Category: {{$item->category}}</h6>
                    <h6>Quantity: {{$item->quantity}}</h6>
                    <h6>Description: {{$item -> description}}</h6>
                    <h6>Created At: {{$item -> created_at}}</h6>
                    <h6>Last Updated: {{$item -> updated_at}}</h6>

                    {{-- this is the button that starts the api call for price check --}}
                    <div class="container" id="api_call_button_container">
                        <button class="btn btn-info"id="api_call_button">Check Price</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- this section handles the items photos --}}
        <div class="col-sm-6 my-2 text-center mx-auto">
            <div class="card h-100">
                <div class="card-header fw-bold">Photos</div>
                <div class="card-body">

                    {{-- if no photos we don't want an empty carousel --}}
                    @if($photoCount == 0)
                    <p>No photos are currently stored for this item.</p>

                    {{-- if there are any photos make a carousel and show the photos --}}
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
                            {{-- loop through to add photos
                                first one needs to have active class,--}}
                           
                            @foreach($photos as $photo)
                            @if($loop->first)
                            <div class="carousel-item active">
                                <img src="{{url($photo->filename)}}" alt="photo" class="d-block h-100 w-100"
                                    style="max-height:300px">
                            </div>
                            @else
                            <div class="carousel-item">
                                <img src="{{url($photo->filename)}}" alt="photo" class="d-block h-100 w-100"
                                    style="max-height:300px">
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

@section('scripts')
<script>
    $(document).ready(function(){
        $("#api_call_button").click(function(){
            var upc = "{{$item->upc}}";
            /* code for  original front-end implementation, does not work because of CORS 
             $.ajax({
                method: "GET",
                url: "https://api.upcitemdb.com/prod/trial/lookup?upc="+upc,
                headers: {
                    "Accept": "application/json", 
                    "Content-Type": "application/json"
                },
                success: function(response){
                    $("#api_call_button_container").html("Price: $"+response["items"]["offers"][0]["price"]);
                },
                error: function(response){
                    $("#api_call_button_container").html(response["message"]); 
                }

            }); */

            /* ajax call the endpoint on the backend for the api, grabs the price and places it in the card */
            $.ajax({
                method: "GET", 
                url: "/callUPCitemDBAPI", 
                data: {upc: upc},
                success: function(result){
                    
                    if(result["errorMessage"]){
                        $("#api_call_button_container").html(result["errorMessage"]); 
                    }
                    else if(result["items"][0]){
                        $("#api_call_button_container").html("$"+result["items"][0]["offers"]["0"]["price"]); 
                    }
                    else{
                        $("#api_call_button_container").html("UPC is not available for price check.");
                    }
                }
            })
        });
    });
</script>
@endsection