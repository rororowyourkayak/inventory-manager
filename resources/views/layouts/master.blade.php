
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
@auth
<nav class="navbar navbar-expand-sm sticky-top navbar-dark" style="background-color: steelblue;">
    <div class="container-fluid">
        <a class="navbar-brand" href="/home">Inventory Manager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/add">Add</a></li>
                <li class="nav-item"><a class="nav-link" href="/update">Update</a></li>
                <li class="nav-item"><a class="nav-link" href="/delete">Delete</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Welcome, {{auth()->user()->name}}!</a>
                    <ul class="dropdown-menu">
                    <li class="dropdown-item"><a class="nav-link text-black" href="/account">Account</a></li>
                    <li class="dropdown-item"><a class="nav-link text-black" href="/logout">Logout</a></li>
                    </ul>
                </li>  
            </ul>
        </div>
    </div>
</nav>
@endauth

@guest
<nav class="navbar navbar-expand-sm navbar-dark" style="background-color: steelblue;">
  <div class="container-fluid">
  <ul class="navbar-nav">
  <li class="nav-item">
          <a class="nav-link text-white" href="/">
            Inventory Manager
          </a>
      </li>
  </ul>
  <ul class="navbar-nav ml-auto">
      <li class="nav-item">
          <a class="nav-link" href="/login">
            Login
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="/signup">
            Sign Up
          </a>
      </li>
      
  </ul>
  </div>
</nav>
@endguest

<body>
    <div class="container">
        @yield('content')
    </div>

    @yield('scripts')
</body>

<footer class="text-center mt-4">
    <hr>
    Inventory Manager - Created by Roshan Forde
</footer>
</html>

    