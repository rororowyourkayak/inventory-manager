<?php 
use App\Http\Controllers\DBController;
use App\Models\User;
?>

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
        <thead class="thead-light steelblueBG text-white">
                <tr> 
                    <th>Username</th>
                    <th>Name</th>
                    <th>Items Stored</th>
                </tr>
        </thead>
        @foreach($allUsers as $user)

            <tr>
                <td>{{$user->username}}</td>
                <td>{{$user->name}}</td>
                <td>{{DB::table('items')->where('user',$user->username)->count()}}</td>
            </tr>
        @endforeach
    </table>
</div>
</div>
@endsection