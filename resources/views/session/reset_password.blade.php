@extends('layouts.master')
<title>Reset Password</title>

@section('content')
    <div class="container text-center">
        <h1>Reset Password</h1>
        
    </div>

    <div class="container mt-2 mb-4">
        <div class="col-sm-6 mx-auto">
            <div class="card text-center">
                <div class="card-header font-weight-bold">Reset</div>
                <div class="card-body">
                    <form action ="/reset-password" method ="post">
                        @csrf
                        <input type="hidden" name="token" value={{$token}}>
                        <div class="container mb-3">
                            <div class="form-group">
                                <label for="email">Email: </label>
                                <input type = "email" name="email" id="email" placeholder="Email" value ="{{ old('email') }}" > 
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type = "password" name="password" id="password" placeholder="Password" value ="{{ old('password') }}" > 
                            </div>
                            <div class="form-group">
                                <label for="password_c">Retype password:</label>
                                <input type = "password" name="password_confirmation" id="password_c" placeholder="Retype password" value ="{{ old('password_c') }}" > 
                            </div>
                            </div> 
                            <button type="submit" class="btn btn-primary mx-auto">Reset</button> 
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
