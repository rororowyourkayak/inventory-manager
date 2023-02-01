<?php 
use App\Http\Controllers\DBController;
use App\Models\User;
use App\Models\Item;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
@auth
    @include('reusable_snippets/navbar_for_logged_in_pages')
@endauth
@guest
    @include('reusable_snippets/navbar_for_not_logged_in_pages')
@endguest

<body>
    <div class="container">
        @yield('content')
    </div>
</body>

</html>

    