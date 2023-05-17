@extends('layouts.master')
<title>Account</title>

@section('content')
<div class="container text-center my-4"> 
    <h1>Account</h1>
</div>
<div class="container mb-4">
    <div class="col-sm-6 mx-auto">
        <div class="card text-center">
            <div class="card-header fw-bold">Account Details</div>
                <div class="card-body">
                    <p>Name: {{auth()->user()->name}}</p>
                    <p>Username: {{auth()->user()->username}}</p>
                    <p>Email: {{auth()->user()->email}}</p>
                </div>
        </div>
    </div>
</div>
@if(session()->has('successMessage'))
<div class="col-sm-8 mx-auto">
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    {{session()->get('successMessage')}}
    </div>
</div>
@endif 
@foreach($errors->all() as $error)
<div class="col-sm-8 mx-auto">
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        {{$error}}
    </div>
</div>
    
@endforeach


<div class="container">
    <div class="row">
        <div class="col-sm-6 mx-auto">
            <div class="card text-center">
                <div class="card-header fw-bold">Change Username</div>
                <div class="card-body">
                    <p>Use this box to change your username. <br>
                        Username must be between 1-127 characters long. <br>
                        Letters and numbers only. 
                    </p>
                    
                    <form action="/change_username" method="post">
                        @csrf 
                        <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                        <input type="text" name="name" id="name" required min="1" max="127" placeholder="Username">
                        <button type="submit" class="btn btn-primary mx-auto" >Change Username</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-6 mx-auto">
            <div class="card text-center">
                <div class="card-header fw-bold">Change Name</div>
                <div class="card-body">
                <p>Use this box to change your name. <br>
                        Username must be between 1-127 characters long. <br>
                        Name must only contain letters and spaces. 
                    </p>
                    
                    <form action="/change_name" method="post">
                        @csrf 
                        <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                        <input type="text" name="name" id="name" required min="1" max="127" placeholder="Name">
                        <button type="submit" class="btn btn-primary mx-auto" >Change Name</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

