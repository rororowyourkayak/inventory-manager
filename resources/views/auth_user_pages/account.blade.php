@extends('layouts.master')
<title>Account</title>

@section('content')
<div class="container text-center my-4"> 
    <h1>Account</h1>
</div>
<div class="container">
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
@endsection

