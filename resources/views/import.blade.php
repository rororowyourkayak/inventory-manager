@extends('layouts.master')
<title>Import</title>

@section('content')

<div class="container text-center my-2">
    <h1>Import Inventory</h1>
    <p>Use the input box below to upload a CSV of items to your inventory.<br>
        Please refer to the requirements below before uploading.
    </p>

</div>

<div class="row">

    <div id="upc-auto-increment-message" class="feedback-message-box col-sm-6 alert alert-info mx-auto text-center">
        <h3>Existing UPCs</h3>
        <p>The following UPCs existed already in the inventory, and were incremented by quantity listed in file:
        </p>
        <p id="increment_box"></p>

    </div>
    <div id="file-upload-error-message" class="feedback-message-box col-sm-6 alert alert-danger mx-auto text-center">
        <h3>Errors in File Upload:</h3>
        <p id="error_box"></p>
    </div>

    <div id="success-message" class="feedback-message-box col-sm-6 alert alert-success mx-auto text-center">
        <h3>Success!</h3>
        <p>Import was successful.</p>
    </div>
</div>

<div class="container col-sm-10 my-2">
    <h3>Fail Options:</h3>

    <div class="container">
        <h6>Full Fail</h6>
        <p>When full fail is set, any errors in upload file will prevent any items from being entered into
            inventory.<br>
            Errors for the upload will be displayed. <br>
            (Use this option if all items need to be imported at the same time.)
        </p>
    </div>

    <div class="container">
        <h6>Partial Fail</h6>
        <p>When partial fail is set, any items that are valid will go through to your inventory, invalid items will
            not.<br>
            If any UPCs imported with this method exist, they will be incremented for the quantity listed in the
            file.<br>
            Any errors and incremented UPCs will be displayed back to you on the import page. <br>

            (Use this option if you need valid items to go through despite having error values.)

        </p>
    </div>

</div>

<div class="col-sm-8 mx-auto">
    <div class="card text-center">
        <div class="card-header fw-bold">CSV File Upload</div>
        <div class="card-body">


            <p id="initial_error_message" class="feedback-message-box text-danger text-center mt-1"></p>


            <form action="/import" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" id="file" class="form-control mb-2" required accept=".csv">
                <h6>Options: </h6>

                <div class="container lightGrayBG col-3 mx-auto">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="option" id="o1" value="1" checked>
                        <label class="form-check-label" for="o1">Full Fail</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="option" id="o2" value="2">
                        <label class="form-check-label" for="o2">Partial Fail</label>
                    </div>
                </div>


                <button type="submit" class="btn btn-primary my-2">Submit</button>
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
        <li>Must be less than or equal to 1000 rows </li>
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
            <code>description</code> - must not exceed 1000 characters, and should consist of letters and numbers.
            Description may be null.
        </p>

        <p>
            <code>quantity</code> - must be an integer, greater than 0.
        </p>
    </div>

    <h4>Notes: </h4>
    <p>Upload operation cannot be directly undone, any added items need to be deleted through the Delete page.</p>
</div>


@endsection


@section('scripts')

<script>
    $(".feedback-message-box").hide();

    $(document).ready(function(){

        //intially keep message boxes hidden
        

        $("form").on('submit', (e)=>{

            //reset all message boxes on the page
            $(".feedback-message-box").hide();
            
            e.preventDefault(); 
            var formData = new FormData(); 

            formData.append("file", document.getElementById('file').files[0]);  
            formData.append('option',$('input[name="option"]:checked').val());

            $.ajax({
                method: "POST",
                url: "/import",
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){

                    console.log(response);

                    //validation failed for file type or size
                    if(response.validation_error){
                        var errorText = "";
                        for(var error of Object.values(response.validation_error)){
                            errorText += error + '\n';
                        }
                        $("#initial_error_message").text(errorText);
                        $("#initial_error_message").show();

                    }

                    //file had incorrect headers
                    if(response.header_error){
                        $("#initial_error_message").text(response.header_error);
                        $("#initial_error_message").show();
                    }

                    //file had errors with import and full fail was set, preventing any items from being imported
                    if(response.full_fail_import_errors){
                        console.log(response.full_fail_import_errors);
                        var errorText = ""; 
                        for(var error of Object.values(response.full_fail_import_errors)){
                            errorText += "<p>Row " + error.row + ": "+ error.attr + ": ";
                            for(var err of Object.values(error.error)){
                                errorText+= err + " ";
                            }
                            errorText+="</p>";
                        }
                        $("#error_box").html(errorText);
                        $("#file-upload-error-message").show();
                    }

                    //file had errors and partial fail was set, so valid items were still imported
                    if(response.partial_fail_import_errors){
                        
                        if(response.partial_fail_import_errors.errors){
                            var errorText = ""; 
                        for(var error of Object.values(response.partial_fail_import_errors.errors)){
                            errorText += "<p>Row " + error.row + ": "+ error.attr + ": ";
                            for(var err of Object.values(error.error)){
                                errorText+= err + " ";
                            }
                            errorText+="</p>";
                        }
                        $("#error_box").html(errorText);
                        $("#file-upload-error-message").show();
                        }

                        if(response.partial_fail_import_errors.incUPCS){
                            var incText = "";
                            for(var inc of Object.entries(response.partial_fail_import_errors.incUPCS)){
                                incText+= "<p>"+ inc[0] + ": New quantity of " + inc[1]+".</p>";
                            }
                            $("#increment_box").html(incText);
                            $("#upc-auto-increment-message").show();
                        }
                    }

                    //no issues with the import 
                    if(response.success){
                        $("#success-message").show();
                    }   


                    
                }
            }); 

        });
    });

</script>

@endsection