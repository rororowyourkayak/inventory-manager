@extends('layouts.master')

@section('content')


<div class="container text-center my-2">
    <h1>Add Items</h1>
    <p>Use the box below to add new entries to the inventory.</p>
    <p>UPC must be a valid 12-digit UPC-A code.</p>
    <p>Photo uploads must be .png, .jpg, or .jpeg.<br>
        Storage amount of photos may not exceed 2MB in total.
    </p>
</div>

<div id="messageBox" class="alert col-sm-8 text-center mx-auto">

</div>


<div class="container my-4">
    <div class="row">
        <div class="col-8 mx-auto">
            <div class="card text-center">
                <div class="card-header text-center fw-bold">Add Item to Inventory</div>
                <div class="card-body">

                    @foreach($errors->all() as $error)
                    <p class="text-danger text-center mt-1">{{$error}}</p>
                    @endforeach
                    <form method="post" action="/add_item" enctype="multipart/form-data" id="addForm">
                        @csrf
                        <div class="container">
                            <div class="form-group">

                                <label for="item_upc" class="mb-2 mr-sm-2">Item UPC: </label>
                                <input class="form-control mb-2 mr-sm-2 col-sm" type="text" name="upc" id="item_upc"
                                    placeholder="Item UPC" required value="{{ old('upc') }}" maxlength=12>

                                <p class="text-danger check_message" id="upc_check_message"></p>

                                <label for="category" class="mb-2 mr-sm-2">Category:</label>
                                <select name="category" id="category" class="form-control col-sm mb-2 mr-sm-2" required
                                    value="{{ old('category') }}">
                                    <option hidden disabled selected value> -- select a category -- </option>
                                    @foreach($categories as $category)
                                    <option value="{{$category->category}}">{{$category->category}}</option>
                                    @endforeach
                                </select>



                                <label for="description" class="mb-2 mr-sm-2">Description (Optional): </label>
                                <textarea class="form-control mb-2 mr-sm-2 col-sm" rows="2" cols="4" name="description"
                                    placeholder="Description" id="description" value="{{ old('description') }}"
                                    max=511></textarea>


                                <label for="quantity" class="mb-2 mr-sm-2">Quantity:</label>
                                <input class="form-control mb-2 mr-sm-2 col-sm" type="number" name="quantity"
                                    id="quantity" min="1" value="1" required value="{{ old('quantity') }}">

                                <p class="text-danger check_message" id="quantity_check_message"></p>

                                <label for="file" class="mb-2 mr-sm-2">Upload Photos (Optional):</label>
                                <input class="form-control mb-2 mr-sm-2 col-sm" type="file" name="file[]" id="file"
                                    accept=".png, .jpg, .jpeg" multiple>

                            </div>
                            <button type="submit" class="btn btn-primary mx-auto">Add</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    /* UPC checksum fucntion, accepts a string upc, return boolean value */
function isValidUPC(upc) {

if (upc.match(/\d{12}/) && upc.length == 12) {
    let splitDigits = upc.split('');
   
    let checkDigit = Number(splitDigits[11]);
    let oddSum = 0, evenSum = 0;
    for (let n = 0; n < 11; n++) {
       
        let temp = Number(splitDigits[n]);
        
        if (n % 2 == 0) { oddSum += temp; }
        else {
            evenSum += temp;
        }

    }
    
    oddSum *= 3;
    let totalSum = oddSum + evenSum;

    if ((totalSum + checkDigit) % 10 == 0) {
        return true;
    }
}

return false;
}


$(document).ready(function () {

//check for upc being valid
$("#item_upc").on('change', function () {
    var upc = $(this).val().trim();
    if (isValidUPC(upc)) {

        $.ajax({
            method: "POST",
            url: "/check_item_exists",
            data: { upc: upc },
            headers: { 'X-CSRF-TOKEN': '{{csrf_token()}}' },
            success: function (response) {
                if (response.exists) {
                    window.location.href = `/redirect_to_update_page/${upc}`;
                    /* $("#upc_check_message").text("UPC already exists in inventory.");
                    $("#upc_check_message").removeClass("text-success");
                    $("#upc_check_message").addClass("text-danger"); */
                }
                if (response.new) {
                    $("#upc_check_message").text("UPC is new to inventory.");
                    $("#upc_check_message").addClass("text-success");
                    $("#upc_check_message").removeClass("text-danger");
                }
            },
        });
    }
    else {
        $("#upc_check_message").text("UPC is invalid.");
        $("#upc_check_message").removeClass("text-success");
        $("#upc_check_message").addClass("text-danger");
    }

});



$("#quantity").on("change", function () {
    var q = Number($("#quantity").val());


    if (q > 0 && Number.isInteger(q)) {
        $("#quantity_check_message").addClass("text-success");
        $("#quantity_check_message").removeClass("text-danger");
        $("#quantity_check_message").text("Quantity is valid.");
    }
    else {
        $("#quantity_check_message").addClass("text-danger");
        $("#quantity_check_message").removeClass("text-success");
        $("#quantity_check_message").text("Quantity must be a number greater than 0.");

    }
});



$("#addForm").on('submit', function (e) {


    var upc = $("#item_upc").val().trim();
    var quantity = Number($("#quantity").val());
    e.preventDefault();
    if (isValidUPC(upc)) {

        /* ajax call to check if the upc is valid or not */

        /* if it is valid, we then need to check if it exists */

        $.ajax({
            method: "POST",
            url: "/check_item_exists",
            data: { upc: upc },
            headers: { 'X-CSRF-TOKEN': '{{csrf_token()}}' },
            success: function (response) {
                if (response.exists) {
                    window.location.href = `/redirect_to_update_page/${upc}`;
                  /*   if (confirm("Item already exists in the inventory. \n Would you like to update the item by quantity just entered?")) {

                         if the upc exists, then check if the user wants to increment, if so make ajax call to increment route 
                        $.ajax({
                            method: "POST",
                            url: "/increment_item_quantity",
                            data: {
                                upc: upc,
                                quantity: quantity
                            },
                            headers: { 'X-CSRF-TOKEN': '{{csrf_token()}}' },
                            success: function (response) {
                                if (response.success) {
                                     changes the message box to display success message 
                                    $("#messageBox").addClass("alert-success");
                                    $("#messageBox").removeClass("alert-danger");
                                    $("#messageBox").text(response.success);
                                    $("input").val("");
                                    $(".check_message").text("");

                                }
                                if (response.errors) {
                                    $("#messageBox").addClass("alert-danger");
                                    $("#messageBox").removeClass("alert-success");
                                    $("#messageBox").text(response.errors);

                                }
                            },
                        });
                    }
                    else {
                        if (confirm("Would you like to be moved to the update page for this item?")) {
                            window.location.href = `/redirect_to_update_page/${upc}`;
                        }
                        else {
                            alert("No action will be taken.");
                        }
                    } */
                }

                /* if the item upc is valid but does not exist, proceed to send it to the server to be added to inventory */
                if (response.new) {

                    var formData = new FormData();

                    formData.append('upc', $("#item_upc").val());
                    formData.append('quantity', $("#quantity").val());
                    formData.append('category', $("#category").val());
                    formData.append('description', $("#description").val());

                    //code to add files from file input to formdata
                    var totalfiles = document.getElementById('file').files.length;
                    for (var index = 0; index < totalfiles; index++) {
                        formData.append("file[]", document.getElementById('file').files[index]);

                    }

                    /* post form data to add item code on the backend */
                    $.ajax({
                        method: "POST",
                        url: "/add_item",
                        data: formData,
                        headers: { 'X-CSRF-TOKEN': '{{csrf_token()}}' },
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.success) {
                                $("#messageBox").addClass("alert-success");
                                $("#messageBox").removeClass("alert-danger");
                                $("#messageBox").text(response.success);
                                $("input").val("");
                                $(".check_message").text("");

                            }
                            else if (response.errors) {

                                $("#messageBox").addClass("alert-danger");
                                $("#messageBox").removeClass("alert-success");
                                var errorText = "";
                                for (error of Object.values(response.errors)) {
                                    errorText += error[0] + "\n";
                                }
                                console.log(errorText);
                                $("#messageBox").text(errorText);

                            }
                        },
                    });
                }
            },
        });

    }
    /* if the upc was not valid, this will raise an alert to the user, and nothing else will happen */
    else {
        alert("UPC submitted is not a valid UPC.");
    }

});
});


</script>
@endsection