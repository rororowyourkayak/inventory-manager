@extends('layouts.master')
   

<title>Sign Up</title>
    @section('content')
        <div class="container text-center my-4" >
            <h1>Inventory Manager Sign Up</h1>
            <p>Use the form below to make an account.</p>
            <p>Passwords must be at least 8 characters long, with one uppercase letter and one number.</p>
        </div>

        <div class="container">
            <div class="col-sm-6 mx-auto">
                <div class="card text-center">
                    <div class="card-header fw-bold">Sign Up</div>
                    <div class="card-body">
                        <form action="/signup" method="post">
                            @csrf
                            <div class="container col-sm-8">
                                <div class="input-group mb-4">
                                        <label for="name">Name:</label>
                                        <input class ="ms-1 form-control" type="text" name="name" id="name" placeholder="Name" required value ="{{ old('name') }}">
                                        @error('name')
                                            <p class="text-danger text-center mt-1">{{$message}}</p>
                                        @enderror
                                    </div>

                                <div class="input-group mb-4">
                                    <label for="email">Email:</label>
                                    <input class="ms-1 form-control" type="email" name="email" id="email" placeholder="Email" required value ="{{ old('email') }}">
                                    @error('email')
                                            <p class="text-danger text-center mt-1">{{$message}}</p>
                                        @enderror
                                </div>
                                <div class="input-group mb-4">
                                    <label for="username">Username:</label>
                                    <input class="ms-1 form-control" type="text" name="username" id="username" placeholder="Username" required value ="{{ old('username') }}">
                                    @error('username')
                                            <p class="text-danger text-center mt-1">{{$message}}</p>
                                        @enderror
                                </div>
                                <div class="input-group mb-4">
                                    <label for="password">Password:</label>
                                    <input class="ms-1 form-control" type="password" name="password" id="password" placeholder="Password" required>
                                    @error('password')
                                            <p class="text-danger text-center mt-1">{{$message}}</p>
                                     @enderror
                                </div>
                                
                                <input type="submit" class="btn btn-primary" value="Sign Up">
                                

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    @endsection
    