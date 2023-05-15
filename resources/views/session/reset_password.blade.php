@extends('layouts.master')
<title>Reset Password</title>

@section('content')
    <div class="container text-center my-2">
        <h1>Reset Password</h1>
        <p>Passwords must be at least 8 characters long, with one uppercase letter and one number.</p>
    </div>

    <div class="container mt-2 mb-4">
        <div class="col-sm-6 mx-auto">
            <div class="card text-center">
                <div class="card-header fw-bold">Reset</div>
                <div class="card-body">
                    <form action ="/reset-password" method ="post">
                        @csrf
                        <input type="hidden" name="token" value={{$token}}>
                        <div class="container mb-3">
                                       
                            <div class="form-group">
                                <label for="email" class="mb-2 mr-sm-2">Email: </label>
                                <input class="form-control mb-2 mr-sm-2 col-sm"type = "email" name="email" id="email" placeholder="Email" value ="{{ old('email') }}" > 
                            </div>
                            <div class="form-group">
                                <label for="password" class="mb-2 mr-sm-2">Password:</label>
                                <input  class="form-control mb-2 mr-sm-2 col-sm" type = "password" name="password" id="password" placeholder="Password" value ="{{ old('password') }}" > 
                            </div>
                            <div class="form-group">
                                <label for="password_c" class="mb-2 mr-sm-2">Retype password:</label>
                                <input  class="form-control mb-2 mr-sm-2 col-sm" type = "password" name="password_confirmation" id="password_c" placeholder="Retype password" value ="{{ old('password_c') }}" > 
                            </div>
                            </div> 
                            <button type="submit" class="btn btn-primary mx-auto">Reset</button> 
                    </form>
                </div>
            </div>
        </div>
        @foreach($errors->all() as $error)
            <p class="text-danger text-center mt-1">{{$error}}</p>
        @endforeach
    </div>
@endsection
