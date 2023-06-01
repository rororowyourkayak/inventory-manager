<?php use App\Models\Item;?>

@extends('layouts.master')
<title>Admin</title>

@section('content')

<div class="container text-center my-4">
    <h1>Admin Page</h1>
</div>

<div class="container text-center">
    <p>Total Users: {{$numUsers}}</p>
    <p>Total Items: {{$numItems}}</p>
</div>

<div class="container">
    <div class="col-sm-8 text-center mx-auto overflow-auto">

        <table class="table table-bordered table-striped table-responsive-sm text-center">
            <thead class="thead-light steelblueBG ">
                <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Items Stored</th>
                    <th>Delete?</th>
                </tr>
            </thead>
            @foreach($allUsers as $user)

            <tr>
                <td>{{$user->username}}</td>
                <td>{{$user->name}}</td>
                <td>{{Item::where('user_id',$user->id)->count()}}</td>
                <td>
                    <form action="/admin_delete_user" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <button type="submit" class="btn btn-danger mx-auto">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

<div class="container">
    @foreach($errors->all() as $error)
    <p class="text-danger text-center mt-1">{{$error}}</p>
    @endforeach
    <div class="row">
        <div class="col-sm-4 ">
            <div class="card text-center h-100">
                <div class="card-header fw-bold">Add Category</div>
                <div class="card-body">
                    <form method="post" action="/add_category">
                        @csrf
                        <div class="container">
                            <div class="form-group">

                                <label for="item_name" class="mb-2 mr-sm-2">New Category: </label>
                                <input class="form-control mb-2 mr-sm-2 col-sm" type="text" name="name" id="item_name"
                                    maxlength="64" placeholder="Category Name" required>

                            </div>
                            <button type="submit" class="btn btn-primary mx-auto">Add</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>


        <div class="col-sm-4 ">
            <div class="card text-center h-100">
                <div class="card-header fw-bold">Update Categories</div>
                <div class="card-body">
                    <form action="/update_category" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="catSelect">Select: </label>
                            <select name="cat" id="catSelect" class="form-control mb-2 mr-sm-2 selector">
                                @foreach($categories as $cat)
                                <option value="{{$cat->category}}">{{$cat->category}}</option>
                                @endforeach
                            </select>

                            <label for="newCat" class="mb-2 mr-sm-2">New name: </label>
                            <input class="form-control mb-2 mr-sm-2 col-sm" type="text" name="new" id="newCat">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card h-100 text-center">
                <div class="card-header fw-bold">Delete Categories</div>
                <div class="card-body">
                    <form action="/delete_category" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="catSelector">Select: </label>
                            <select name="cat" id="catSelector" class="form-control mb-2 mr-sm-2 selector">
                                @foreach($categories as $cat)
                                <option value="{{$cat->category}}">{{$cat->category}}</option>
                                @endforeach
                            </select>


                            <button class="btn btn-danger" type="submit">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<!-- //links for the select2 jquery library -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(".selector").select2();
</script>
@endsection