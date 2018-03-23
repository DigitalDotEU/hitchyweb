@extends('layouts/app')

@section('content')
<div class="row profile">
    <div class="col-sm-6 col-sm-offset-3 profileCol">
        <h2>Hello {{ $user_info->name }}</h2>
        <p>First name: {{ $user_info->firstName }}</p>
        <p>Last name: {{ $user_info->lastName }}</p>
        <p>Bio: {{ $user_info->about }}</p>
        <p>Email: {{ $user_info->email }}</p>
        <p>City: {{ $user_info->city }}</p>
        <p>Country: {{ $user_info->country }}</p>
    </div>

</div>
@endsection
