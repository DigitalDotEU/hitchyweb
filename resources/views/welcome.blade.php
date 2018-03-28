@extends('layouts/app')

@section('content')
<div class="row welcome">
    <div class="col-sm-12">
        <img src="{{ URL::to('/') }}/images/logo2.png" class="logo">
        <!--<h2>Take a ride.</h2>-->
    
        <div class="buttons">
            <a href="{{url('/login')}}"><div class="btn btn-default signIn">SIGN IN</div></a>
            <a href="{{url('/register')}}"><div class="btn btn-default signUp">SIGN UP</div></a>
        </div>
    </div>
</div>
@endsection