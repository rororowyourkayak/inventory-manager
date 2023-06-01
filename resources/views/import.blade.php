@extends('layouts.master')
<title>Import</title>

@section('content')

<div class="container text-center my-2">
    <h1>Import Inventory</h1>
    <p>Use the input box below to upload a CSV of items to your inventory.<br>
        Please refer to the requirements below before uploading.
    </p>

</div>
@if(session()->has('error'))
           
<div class="col-sm-8 lightGrayBG mx-auto text-center border border-danger">
        <h2 class="my-2">Import Errors</h2>
    
    <div class="container">
        @foreach(session()->get('error') as $error)
        <p>Row {{$error["row"]}}: 
            <span class="text-danger"> 
                @foreach($error["error"] as $error)
                   {{$error}}  
                @endforeach
            </span> 
        </p>
        @endforeach
    </div>
</div>

@endif

<div class="col-sm-8 mx-auto">
    <div class="card text-center">
        <div class="card-header fw-bold">CSV File Upload</div>
        <div class="card-body">
            
            
            <form action="/import" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" id="file" class="form-control mb-2" required accept=".csv">
                <button class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<style>
    li {
        text-align: left
    }
</style>

<div class="container col-sm-10 my-2">
    <h3>Requirements</h3>
    <ul>
        <li><b>.CSV</b> File Type</li>
        <li>No greater than <b>1 MB</b></li>
        <li>Has header row</li>
        <li>Contains correct headers as listed below, in any order.</li>
        <li>Columns have correct content as specified below.</li>
    </ul>

    <h3>Headers and Column Content</h3>
    <p>The following specifies the header names for each column in the CSV.<br>
        The requirements are listed for each column next its name.<br>
        Content not following the format will not be inserted into your inventory.
    </p>

    <div class="container lightGrayBG rounded">
        <p>
            <code>upc</code> - 12-digit valid UPC-A code, must be numeric.
        </p>
        <p>
            <code>category</code> - name must be supported by Inventory Manager (<a href="/categories"
                target="blank">View Supported Categories Here</a>).
        </p>
        <p>
            <code>description</code> - must not exceed 1000 characters, and should consist of letters and numbers. Description may be null. 
        </p>

        <p>
            <code>quantity</code> - must be an integer, greater than 0.
        </p>
    </div>

    <h4>Notes: </h4>
    <p>If a UPC already exists in the inventory upload will fail. <br>
        Please be sure to screen data for duplicates and errors before upload.
    </p>
    <p>Upload operation cannot be directly undone, any added items need to be deleted through the Delete page.</p>
</div>


@endsection