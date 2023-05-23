<?php use Illuminate\Support\Facades\DB;?>

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
                <td>{{DB::table('items')->where('user',$user->username)->count()}}</td>
                <td>
                    <form action="/admin_delete_user" method="post">
                        @csrf
                        <input type="hidden" name="name" value="{{$user->username}}">
                        <button type="submit" class="btn btn-danger mx-auto">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <table id="catTable" class="table table-bordered table-striped table-responsive-sm text-center w-100 h-100">
                <thead class="thead-light steelblueBG">
                    <tr>
                        <th>Category</th>
                    </tr>
                </thead>
                @foreach($categories as $cat)
                <tr>
                    <td>{{$cat->category}}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="container col-sm-6">
            <div class="card text-center h-100">
                <div class="card-header text-center fw-bold">
                    Edit Categories
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item mx-auto active">
                            <a class="nav-link fw-bold" data-bs-toggle="tab" href="#add_box">Add</a>
                        </li>
                        <li class="nav-item mx-auto">
                            <a class="nav-link fw-bold" data-bs-toggle="tab" href="#update_box">Update</a>
                        </li>
                        <li class="nav-item mx-auto">
                            <a class="nav-link fw-bold" data-bs-toggle="tab" href="#delete_box">Delete</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane container" id="add_box">
                            <form method="post" action="/add_category">
                                @csrf
                                <div class="container">
                                    <div class="form-group">

                                        <label for="item_name" class="mb-2 mr-sm-2">New Category: </label>
                                        <input class="form-control mb-2 mr-sm-2 col-sm" type="text" name="name"
                                            id="item_name" maxlength="64" placeholder="Category Name" required>

                                    </div>
                                    <button type="submit" class="btn btn-primary mx-auto">Add</button>
                                </div>

                            </form>
                        </div>
                        <div class="tab-pane container fade" id="update_box">
                            <form action="/update_category" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="catSelect">Select: </label>
                                    <select name="cat" id="catSelect">
                                        @foreach($categories as $cat)
                                        <option value="{{$cat->category}}">{{$cat->category}}</option>
                                        @endforeach
                                    </select>
                                    <label for="newCat">New name: </label>
                                    <input type="text" name="new" id="newCat">
                                    <button type="submit">Update</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane container fade" id="delete_box">
                            ...
                        </div>
                        @foreach($errors->all() as $error)
                        <p class="text-danger text-center mt-1">{{$error}}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')

@endsection