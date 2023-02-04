@extends('layouts.master')
<title>Login</title>


@section('content')
    <div class="container text-center mt-4" >
        <h1>Inventory Manager Login</h1>
        <p>Please enter your credentials below to login.</p>
    </div>

    <div class="container mt-2 mb-4">
        <div class="col-sm-6 mx-auto">
            <div class="card text-center">
                <div class="card-header font-weight-bold">Login</div>
                <div class="card-body">
                    <form action ="session" method ="post">
                                @csrf
                                <div class="container mb-3">
                                    <div class="form-group">
                                        <label for="user">Username: </label>
                                        <input type = "text" name="username" id="username" placeholder="Username" value ="{{ old('username') }}" > 
                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password: </label>
                                        <input type = "password" name="password" id="password" placeholder="Password"> 
                                    </div>
                                    @error('username')
                                                        <p class="text-danger text-center mt-1">{{$message}}</p>
                                        @enderror
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </div>
                    </form>
                    <div class="container">
                        <p>New User?</p>
                        <button type="button" class="btn btn-primary" onClick= "location.href = '/signup'">Sign Up</button>
                    </div>
        </div>
    </div>
@endsection