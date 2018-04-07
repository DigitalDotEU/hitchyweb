@extends('layouts/app')

@section('content')
<div class="row newEvent">
    <div class="col-sm-8 col-sm-offset-2 newEventCol">
        <h1>New Event</h1>

       <form method="post" action="{{ action('EventController@store') }}">
            <div class="form-group">
                <label for="name">Event name:</label>
                <input type="name" class="form-control" id="name" name="name">
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <input type="description" class="form-control" id="description" name="description">
            </div>

            <div class="form-group">
                <label for="startPlaceLattitude">startPlaceLattitude:</label>
                <input type="startPlaceLattitude" class="form-control" id="startPlaceLattitude" name="startPlaceLattitude">
            </div>

            <div class="form-group">
                <label for="startPlaceLongitude">startPlaceLongitude:</label>
                <input type="startPlaceLongitude" class="form-control" id="startPlaceLongitude" name="startPlaceLongitude">
            </div>

            <div class="form-group">
                <label for="stopPlaceLattitude">stopPlaceLattitude:</label>
                <input type="stopPlaceLattitude" class="form-control" id="stopPlaceLattitude" name="stopPlaceLattitude">
            </div>

            <div class="form-group">
                <label for="stopPlaceLongitude">stopPlaceLongitude:</label>
                <input type="stopPlaceLongitude" class="form-control" id="stopPlaceLongitude" name="stopPlaceLongitude">
            </div>

            <div class="form-group">
                <label for="startDate">startDate:</label>
                <input type="startDate" class="form-control" id="startDate" name="startDate">
            </div>

            <input type="hidden" class="form-control" id="author" name="author" value="{{Session::get('loggedInUser')}}">
            <input type="hidden" class="form-control" id="joinedUser" name="joinedUser" value="{{Session::get('loggedInUser')}}">
        

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <button type="submit" class="btn btn-default">Add event</button>  
        </form>

        	<!--"name" : "event222rerrer22",
	"description" : "testdssdsdsd",
	"startPlaceLattitude": 53.1515,
	"startPlaceLongitude": 13.1212,
	"stopPlaceLattitude": 53.6262,
	"stopPlaceLongitude": 13.6262,
	"startDate" : "2018-04-02 08:17:46",
	"author" : "test@gmail.com",
	"joinedUser" : "te221st221212122@gmail.com"-->

    </div>
</div>

@endsection