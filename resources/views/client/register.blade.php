@extends('layouts/app')

@section('content')
<div class="row register">
    <div class="col-sm-6 col-sm-offset-3 registerColumn">

        <form method="post" action="{{ action('RegisterController@store') }}">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="name">Name in App:</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" class="form-control" id="firstName" name="firstName">
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" class="form-control" id="lastName" name="lastName">
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" class="form-control" id="city" name="city">
            </div>
            <div class="form-group">
                <label for="county">Country:</label>
                <input type="text" class="form-control" id="county" name="country">
            </div>
            <div class="form-group">
                    <label for="about">Bio:</label>
                    <input type="text" class="form-control" id="about" name="about">
                </div>
            <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" class="form-control" id="pwd" name="password">
            </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <button type="submit" class="btn btn-default">Sign Up</button>  
        </form>

    </div>
</div>
@endsection