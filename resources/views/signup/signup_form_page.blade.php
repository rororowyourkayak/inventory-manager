@extends('layouts.master')
   

<title>Sign Up</title>
    @section('content')
    {!! NoCaptcha::renderJs() !!}
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
                        <form action="/signup" method="post" id="signUpForm">
                            @csrf
                            <div class="container col-sm-8">
                                <div class="input-group mb-4">
                                        <label for="name">Name:</label>
                                        <input class ="ms-1 form-control" type="text" name="name" id="name" placeholder="Name" required value ="{{ old('name') }}">
                
                                    </div>

                                <div class="input-group mb-4">
                                    <label for="email">Email:</label>
                                    <input class="ms-1 form-control" type="email" name="email" id="email" placeholder="Email" required value ="{{ old('email') }}">
                                    
                                </div>
                                <div class="input-group mb-4">
                                    <label for="username">Username:</label>
                                    <input class="ms-1 form-control" type="text" name="username" id="username" placeholder="Username" required value ="{{ old('username') }}">
                                    
                                </div>
                                <div class="input-group mb-4">
                                    <label for="password">Password:</label>
                                    <input class="ms-1 form-control" type="password" name="password" id="password" placeholder="Password" required>
                                    
                                </div>
                                <div class="input-group mb-4">
                                    <label for="password_c">Confirm Password:</label>
                                    <input class="ms-1 form-control" type="password" name="password_confirmation" id="password_c" placeholder="Confirm Password" required>
                                    
                                </div>
                                
                                @foreach($errors->all() as $error)
                                <p class="text-danger text-center mt-1">{{$error}}</p>
                                @endforeach
                                
                                {!! NoCaptcha::displaySubmit('signUpForm', 'Submit') !!}
                                

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    @endsection
    