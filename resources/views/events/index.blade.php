@extends('layouts/app')

@section('content')
<div class="row events">
    <div class="col-sm-8 col-sm-offset-2 eventsCol">
        <h1>Events</h1>

        @foreach($res as $event)
            @if($event['submittedByAdmin'] == 1)
                <div class="well">
                    <p>Name: {{$event['name']}}</p>
                    <p>Description: {{$event['description']}}</p>
                    <p>Author: {{$event['author']}}</p>
                    <p>Start place coordinates: {{$event['startPlaceLattitude']}},  {{$event['startPlaceLongitude']}}</p>
                    <p>Stop place coordinates: {{$event['stopPlaceLattitude']}},  {{$event['stopPlaceLongitude']}}</p>
                    <p>Start date: {{$event['startDate']}}</p>

                    <p> id: {{$event['id']}}</p>
                    <?php $pathSingle = '/events/' . $event['id']; ?>
                    <a href="{{url($pathSingle)}}"><div class="btn btn-default">Show details</div></a>

                    <?php $userjoined = false; ?>
                    <p>Joined User:
                        @foreach($joinedUsers as $joinedResult)
                            @if($joinedResult[0] == $event['name'])
                                {{$joinedResult[1]}} ,

                                @if($joinedResult[1] == Session::get('loggedInUser'))
                                    <?php $userjoined = true; ?>
                                @endif
                            @endif
                        @endforeach
                    </p>

                    <!-- invisible form for join event-->
                    @if($event['author'] != Session::get('loggedInUser') && $userjoined == false && Session::get('token'))
                       <form method="post" action="{{ action('EventController@store') }}">
                            <input type="hidden" class="form-control" id="name" name="name" value="{{$event['name']}}">
                            <input type="hidden" class="form-control" id="description" name="description" value="{{$event['description']}}">
                            <input type="hidden" class="form-control" id="startPlaceLattitude" name="startPlaceLattitude" value="{{$event['startPlaceLattitude']}}">
                            <input type="hidden" class="form-control" id="startPlaceLongitude" name="startPlaceLongitude" value="{{$event['startPlaceLongitude']}}">
                            <input type="hidden" class="form-control" id="stopPlaceLattitude" name="stopPlaceLattitude" value="{{$event['stopPlaceLattitude']}}">
                            <input type="hidden" class="form-control" id="stopPlaceLongitude" name="stopPlaceLongitude" value="{{$event['stopPlaceLongitude']}}">
                            <input type="hidden" class="form-control" id="startDate" name="startDate" value="{{$event['startDate']}}">
                            <input type="hidden" class="form-control" id="author" name="author" value="{{$event['author']}}">
                            <input type="hidden" class="form-control" id="joinedUser" name="joinedUser" value="{{Session::get('loggedInUser')}}">
                    

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <button type="submit" class="btn btn-default">Join event!</button>  
                        </form>
                    @endif
                </div>
            @endif
        @endforeach

        @if(Session::get('token'))
            <a href="{{url('/newEvent')}}"><div class="btn btn-default createEventBtn">Add new Event</div></a>
        @endif

    </div>
</div>
@endsection