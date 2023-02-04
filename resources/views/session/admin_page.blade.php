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
    <p>Total Users: {{DB::table('users')->count()}}</p>
    <p>Total Items: {{DB::table('items')->count()}}</p>
    </div>

    <div class="container">
    <div class="col-sm-8 text-center mx-auto overflow-auto">
    
    <table class="table table-bordered table-striped table-responsive-sm">
        <thead class="thead-light">
                <tr> 
                    <th>Username</th>
                    <th>Name</th>
                    <th>Last Updated</th>
                    <th>Items Stored</th>
                </tr>
        </thead>
        @foreach(User::all() as $user)

            <tr>
                <td>{{$user->username}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->updated_at}}</td>
                <td>{{DB::table('items')->where('user',$user->username)->count()}}</td>
            </tr>
        @endforeach
    </table>
</div>
</div>

@endsection