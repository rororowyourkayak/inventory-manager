<!DOCTYPE html>
<html lang="en">
@include('reusable_snippets/page_head') 
<title>Forgot Password</title>
<body class="my-4">
<div class="container text-center">
    <h1>Password Reset</h1>
    <p>Enter your email address below to get a password reset link.</p>
</div>

<div class="container mt-2 mb-4">
    <div class="col-sm-6 mx-auto">
        <div class="card text-center">
            <div class="card-header font-weight-bold">Reset</div>
            <div class="card-body">
                <form action ="/forgot-password" method ="post">
                    @csrf
                    <div class="container mb-3">
                        <div class="form-group">
                            <label for="email">Email: </label>
                            <input type = "email" name="email" id="email" placeholder="Email" value ="{{ old('email') }}" > 
                            <button type="submit" class="btn btn-primary mx-auto">Get Link</button>
                    </div>
                        </div>  
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>
