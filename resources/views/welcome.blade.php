@extends('layouts/app')

@section('content')
<div class="row welcome">
    <div class="col-sm-12">
        <h1>Hitchy</h1>
        <h2>Take a ride.</h2>
    
        <a href="{{url('/login')}}"><div class="btn btn-default signIn">SIGN IN</div></a>
        <a href="{{url('/register')}}"><div class="btn btn-default signUp">SIGN UP</div></a>
    </div>
</div>
@endsection