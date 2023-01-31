<!DOCTYPE html>
<html lang="en">
@include('reusable_snippets/page_head') 
<title>Account</title>
<body class="mb-4">
@include('reusable_snippets/navbar_for_logged_in_pages')

<div class="container text-center my-4"> 
    <h1>Account</h1>
</div>
<div class="container">
    <div class="col-sm-6 mx-auto">
        <div class="card text-center">
            <div class="card-header font-weight-bold">Account Details</div>
                <div class="card-body">
                    <p>Name: {{auth()->user()->name}}</p>
                    <p>Username: {{auth()->user()->username}}</p>
                    <p>Email: {{auth()->user()->email}}</p>
                 
                </div>
        </div>
    </div>
</div>

</body>
</html>