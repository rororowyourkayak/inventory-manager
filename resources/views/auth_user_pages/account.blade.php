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

@elseif(session()->has('errorMessage'))

<div class="col-sm-8 mx-auto">
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        {{session()->get('errorMessage')}}
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
    <div class="row my-2">
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
                        <button type="submit" class="btn btn-primary mx-auto">Change Username</button>
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
                        <button type="submit" class="btn btn-primary mx-auto">Change Name</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 mx-auto">
            <div class="card text-center">
                <div class="card-header fw-bold">Change Email</div>
                <div class="card-body">
                    <p>Use this box to change your email. <br>
                       Email must be between 1-255 characters long. <br>
                        Letters and numbers only.
                    </p>

                    <form action="/change_email" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{auth()->user()->id}}">

                        <input class="form-control mb-2" type="email" name="email" id="email" required min="1" max="255" placeholder="Email" required>
                        <input class="form-control mb-2" type="email" name="email_confirmation" id="email_c" required min="1" max="255" placeholder="Confirm Email" required>

                        <button type="submit" class="btn btn-primary mx-auto">Change Email</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-6 mx-auto">
            <div class="card text-center">
                <div class="card-header fw-bold">Change Password</div>
                <div class="card-body">
                    <p>Use this box to change your password. <br>
                      Password must be between 8-127 characters long. <br>
                        Must contain one uppercase and one number.
                    </p>

                    <form action="/change_password" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{auth()->user()->id}}">

                        <input class="form-control mb-2" type="password" name="password" id="password" required min="8" max="127" placeholder="Password" required>
                        <input class="form-control mb-2" type="password" name="password_confirmation" id="password_c" required min="8" max="127" placeholder="Confirm Password" required>

                        <button type="submit" class="btn btn-primary mx-auto">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection