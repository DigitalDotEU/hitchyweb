@extends('layouts/app')

@section('content')
<div class="row admin">
    <div class="col-sm-10 col-sm-offset-1 adminColumn">
        <h1>Admin Page - events</h1>

        <a href="/adminPage">
            <div class="btn btn-default">List of unsubmitted points</div>
        </a>

        @foreach($res as $event)
            @if($event['submittedByAdmin'] == 0)
                <div class="well">

                    <p>Id: {{$event['id']}}</p>
                    <p>Name: {{$event['name']}}</p>
                    <p>Description: {{$event['description']}}</p>
                    <p>Author: {{$event['author']}}</p>
                    <p>start place coordinates: {{$event['startPlaceLattitude']}}, {{$event['startPlaceLongitude']}}</p>
                    <p>stop place coordinates: {{$event['stopPlaceLattitude']}}, {{$event['stopPlaceLongitude']}}</p>
                    <p>start date: {{$event['startDate']}}</p>
                    <p>created at: {{$event['created_at']}}</p>

                    <a href="/adminSubmitEvents/{{$event['id']}}">
                        <div class="btn btn-default" value="{{$event['id']}}">Publish</div>
                    </a>
                </div>
            @endif
        @endforeach

    </div>
</div>
@endsection