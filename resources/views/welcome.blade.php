@extends('layouts/app')

@section('content')
<div class="row welcome">
    <div class="col-sm-12">
        <img src="{{ URL::to('/') }}/images/logo2.png" class="logo">
        <!--<h2>Take a ride.</h2>-->

        <div class="welcomeText">
            <p>Hitchy is free application for hitchhikers. Find the best spots in your area. </p>
            <p>You can also find gas stations, stores and more.</p>
            <p> Are you going to a new place and are you looking for companions? </br>You can do it using hitchy.</p>
        </div>
    
        <div class="buttons">
            <a href="{{url('/login')}}"><div class="btn btn-default signIn">SIGN IN</div></a>
            <a href="{{url('/register')}}"><div class="btn btn-default signUp">SIGN UP</div></a>
        </div>
    </div>
</div>
@endsection