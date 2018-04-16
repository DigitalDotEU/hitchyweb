@extends('layouts/app')

@section('content')
<div class="row login">
    <div class="col-sm-6 col-sm-offset-3 loginColumn">

        <h2>Sign In</h2>

        <form method="post" action="{{ action('LoginController@store') }}">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            
            <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" class="form-control" id="pwd" name="password">
            </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <button type="submit" class="btn btn-default loginBtn">Sign In</button>  
        </form>

        <p>Don't have an account? <span class="bold"><a href="/register">Sign Up<a/></span></p>

    </div>
</div>
@endsection