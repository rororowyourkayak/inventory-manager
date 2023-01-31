
<!DOCTYPE html>
<html lang="en">
@include('reusable_snippets/page_head') 
<title>Success!</title>
<body>
@include('reusable_snippets\navbar_for_not_logged_in_pages')
    <div class="container text-center my-4">
        <h1 class="my-2">Signup Successful!</h1>
        <p>You may now log in to your account.</p>
        <button type="button" class="btn btn-primary" onClick= "location.href = 'login'">Login</button>
    </div>
</body>
@include('reusable_snippets/page_footer')
</html>