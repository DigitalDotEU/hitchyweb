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

                    <p>Joined User:
                        @foreach($joinedUsers as $joinedResult)
                            @if($joinedResult[0] == $event['name'])
                                {{$joinedResult[1]}} ,
                            @endif
                        @endforeach
                    </p>
                </div>
            @endif
        @endforeach

    </div>
</div>
@endsection