@extends('layouts.master')

<title>Contact</title>

@section('content')

{!! NoCaptcha::renderJs() !!}

<div class="container my-2 text-center">
    <h1>Contact Us</h1>
</div>

<div class="col-sm-8 mx-auto text-center">
    <div class="card">
        <div class="card-header fw-bold">Contact</div>
        <div class="card-body">
            <form action="/processContact" id="contactForm" method="POST">
                @csrf
                
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" placeholder="Name" class="form-control" required>

                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>

                <label for="subject" class ="form-label">Subject:</label>
                <select name="subject" class="form-select" id="subject">
                    <option value="Website Suggestion">Website Suggestion</option>
                    <option value="Website Error">Website Error</option>
                    <option value="Other">Other</option>
                </select>

                <label for="message" class="form-label">Message:</label>
                <textarea name="message" id="message" rows="2" cols="4" class="form-control" placeholder="Message" required></textarea>

                {!! NoCaptcha::displaySubmit('contactForm', 'Submit') !!}
                
            </form>

        </div>
        @foreach($errors->all() as $error)
            <p class="text-danger text-center mt-1">{{$error}}</p>
        @endforeach
    </div>
</div>
@endsection