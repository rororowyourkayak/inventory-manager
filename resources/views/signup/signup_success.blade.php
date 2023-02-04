
@extends('layouts.master')
<title>Success!</title>

@section('content')
    <div class="container text-center my-4">
        <h1 class="my-2">Signup Successful!</h1>
        <p>You may now log in to your account.</p>
        <button type="button" class="btn btn-primary" onClick= "location.href = 'login'">Login</button>
    </div>
@endsection

