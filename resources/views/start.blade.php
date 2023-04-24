
@extends('layouts.master')
<title>Welcome to the Inventory Manager</title>

@section('content')
    <div class="container text-center my-4">
        <h1>Welcome to the Inventory Manager</h1>
    </div>
    <div class="container text-center mb-4">
        <button type="button" class="btn btn-primary" onClick= "location.href = '/login'">Login</button>
        <button type="button" class="btn btn-primary" onClick= "location.href = '/signup'">Sign Up</button>
    </div>
    <div class="container text-center">
        <p>The Inventory Manager is a tool that allows you to store info about items.</p>
        <p>Enter a name and description, choose a category and quantity, and add it in!</p>
       <p> Contents of your inventory can be viewed in a table on your home page.</p>
        <p>Individual item pages can be found by clicking on item names. </p>
        <p>There is currently support for photo uploads. </p>
        <p>Click sign up to make an account today!</p>
    </div>
@endsection

