@extends('layouts/app')

@section('content')
<div class="row eventSingle">
    <div class="col-sm-5 eventSingleCol">

            @if($res['submittedByAdmin'] == 1)
                <div class="single">
                    <p>Name: {{$res['name']}}</p>
                    <p>Description: {{$res['description']}}</p>
                    <p>Author: {{$res['author']}}</p>

                    <div class="eventAddress"></div>

                    <p id="startPlaceLattitude">{{$res['startPlaceLattitude']}}</p>
                    <p id="startPlaceLongitude">{{$res['startPlaceLongitude']}}</p>
                    <p id="stopPlaceLattitude">{{$res['stopPlaceLattitude']}}</p>
                    <p id="stopPlaceLongitude">{{$res['stopPlaceLongitude']}}</p>

                    <p>Start date: {{$res['startDate']}}</p>

                    <?php $userjoined = false; ?>
                    <p>Joined User:
                        @foreach($joinedUsers as $joinedResult)
                            @if($joinedResult[0] == $res['name'])
                                {{$joinedResult[1]}} ,

                                @if($joinedResult[1] == Session::get('loggedInUser'))
                                    <?php $userjoined = true; ?>
                                @endif
                            @endif
                        @endforeach
                    </p>

                    <!-- invisible form -->
                    @if($res['author'] != Session::get('loggedInUser') && $userjoined == false && Session::get('token'))
                       <form method="post" action="{{ action('EventController@store') }}">
                            <input type="hidden" class="form-control" id="name" name="name" value="{{$res['name']}}">
                            <input type="hidden" class="form-control" id="description" name="description" value="{{$res['description']}}">
                            <input type="hidden" class="form-control" id="startPlaceLattitude" name="startPlaceLattitude" value="{{$res['startPlaceLattitude']}}">
                            <input type="hidden" class="form-control" id="startPlaceLongitude" name="startPlaceLongitude" value="{{$res['startPlaceLongitude']}}">
                            <input type="hidden" class="form-control" id="stopPlaceLattitude" name="stopPlaceLattitude" value="{{$res['stopPlaceLattitude']}}">
                            <input type="hidden" class="form-control" id="stopPlaceLongitude" name="stopPlaceLongitude" value="{{$res['stopPlaceLongitude']}}">
                            <input type="hidden" class="form-control" id="startDate" name="startDate" value="{{$res['startDate']}}">
                            <input type="hidden" class="form-control" id="author" name="author" value="{{$res['author']}}">
                            <input type="hidden" class="form-control" id="joinedUser" name="joinedUser" value="{{Session::get('loggedInUser')}}">
                    

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <button type="submit" class="btn btn-default">Join event!</button>  
                        </form>
                    @endif
                </div>
            @endif
    </div>

    <div class="col-sm-7 eventPointMap">
         {!! Mapper::render() !!}
    </div>
</div>
@endsection

<script type="text/javascript">
    function eventPoint(map) { 

        //get content of div's with coordinates
        var startPlaceLattitude = document.getElementById('startPlaceLattitude').textContent;
        var startPlaceLongitude = document.getElementById('startPlaceLongitude').textContent;
        var stopPlaceLattitude = document.getElementById('stopPlaceLattitude').textContent;
        var stopPlaceLongitude = document.getElementById('stopPlaceLongitude').textContent;

        //get address of start point and stop point
        $('.eventAddress').empty(); 
        geocoder = new google.maps.Geocoder();
        var latlng1 = new google.maps.LatLng(startPlaceLattitude, startPlaceLongitude);
        geocoder.geocode({
            'latLng': latlng1
        }, function(results, status) {
            $('.eventAddress').append('<p>Start point address: ' + results[0].formatted_address + '</p>');
        });
        var latlng2 = new google.maps.LatLng(stopPlaceLattitude, stopPlaceLongitude);
        geocoder.geocode({
            'latLng': latlng2
        }, function(results, status) {
            console.log(results);
            $('.eventAddress').append('<p>Stop point address: ' + results[0].formatted_address + '</p>');
        });

        var myLatLng = new google.maps.LatLng(stopPlaceLattitude, stopPlaceLongitude);

        //after click add new point create draggable marker to work with
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            draggable: true,
        });

        //console.log(startPlaceLattitude);

        //display route between start and stop markers
        var directionsDisplay = new google.maps.DirectionsRenderer();
        var directionsService = new google.maps.DirectionsService();

        var start = new google.maps.LatLng(startPlaceLattitude, startPlaceLongitude);
        var end = new google.maps.LatLng(stopPlaceLattitude, stopPlaceLongitude);

        var bounds = new google.maps.LatLngBounds();
        bounds.extend(start);
        bounds.extend(end);
        map.fitBounds(bounds);
        var request = {
            origin: start,
            destination: end,
            travelMode: google.maps.TravelMode.DRIVING
        };
        directionsService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                directionsDisplay.setMap(map);
            } else {
                console.log("Directions Request from " + start.toUrlValue(6) + " to " + end.toUrlValue(6) + " failed: " + status);
            }
        });
 

        var options = {
            styles: [
                        {
                            "featureType": "landscape",
                            "elementType": "labels",
                            "stylers": [
                                {
                                    "visibility": "off"
                                }
                            ]
                        },
                        {
                            "featureType": "transit",
                            "elementType": "labels",
                            "stylers": [
                                {
                                    "visibility": "off"
                                }
                            ]
                        },
                        {
                            "featureType": "poi",
                            "elementType": "labels",
                            "stylers": [
                                {
                                    "visibility": "off"
                                }
                            ]
                        },
                        {
                            "featureType": "water",
                            "elementType": "labels",
                            "stylers": [
                                {
                                    "visibility": "off"
                                }
                            ]
                        },
                        {
                            "featureType": "road",
                            "elementType": "labels.icon",
                            "stylers": [
                                {
                                    "visibility": "off"
                                }
                            ]
                        },
                        {
                            "stylers": [
                                {
                                    "hue": "#00aaff"
                                },
                                {
                                    "saturation": -100
                                },
                                {
                                    "gamma": 2.15
                                },
                                {
                                    "lightness": 12
                                }
                            ]
                        },
                        {
                            "featureType": "road",
                            "elementType": "labels.text.fill",
                            "stylers": [
                                {
                                    "visibility": "on"
                                },
                                {
                                    "lightness": 24
                                }
                            ]
                        },
                        {
                            "featureType": "road",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "lightness": 57
                                }
                            ]
                        }
                    ]
        };
        map.setOptions(options);
    }
</script>