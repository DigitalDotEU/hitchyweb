@extends('layouts/app')

@section('content')
<div class="row newEvent">
    <div class="col-sm-5 newEventCol">
        <h1>New Event</h1>

       <form method="post" action="{{ action('EventController@store') }}">
            <div class="form-group">
               <!-- <label for="name">Event name:</label>-->
                <input type="text" class="form-control" id="name" name="name" placeholder="Event name" required>
            </div>

            <div class="form-group">
               <!-- <label for="description">Description:</label>-->
                <input type="text" class="form-control" id="description" name="description" placeholder="Event description" required>
            </div>

            <div id="startPlacePick" class="btn btn-default startPlacePickBtn">Pick start point</div>
            <div id="stopPlacePick" class="btn btn-default stopPlacePickBtn">Pick stop point</div>

            <!--<div class="form-group">
                <label for="startPlaceLattitude">startPlaceLattitude:</label>-->
                <input type="hidden" class="form-control" id="startPlaceLattitude" name="startPlaceLattitude">
            <!--</div>

            <div class="form-group">
                <label for="startPlaceLongitude">startPlaceLongitude:</label>-->
                <input type="hidden" class="form-control" id="startPlaceLongitude" name="startPlaceLongitude">
            <!--</div>

            <div class="form-group">
                <label for="stopPlaceLattitude">stopPlaceLattitude:</label>-->
                <input type="hidden" class="form-control" id="stopPlaceLattitude" name="stopPlaceLattitude">
            <!--</div>

            <div class="form-group">
                <label for="stopPlaceLongitude">stopPlaceLongitude:</label>-->
                <input type="hidden" class="form-control" id="stopPlaceLongitude" name="stopPlaceLongitude">
            <!--</div>-->

            <div class="form-group dateGroup">
                <label for="startDate">start Date:</label>
                <input type="date" class="form-control" id="startDate" name="startDate" required>
            </div>

            <input type="hidden" class="form-control" id="author" name="author" value="{{Session::get('loggedInUser')}}">
            <input type="hidden" class="form-control" id="joinedUser" name="joinedUser" value="{{Session::get('loggedInUser')}}">
        

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <button type="submit" class="btn btn-default addEventFormBtn">Add event</button>  
        </form>
    </div>

    <div class="col-sm-7 newEventMap">
        {!! Mapper::render() !!}
    </div>
</div>

@endsection

<script>
    function newEvent(map) {

        //init geocoder
        var geocoder = new google.maps.Geocoder();

        //get marker for start point coordinates which you can move
        $( "#startPlacePick" ).click(function() {
            var ctr = map.getCenter();
            var lt = ctr.lat();
            var lng = ctr.lng();

            var myLatLng = {lat: lt, lng: lng};

            $('#startPlaceLattitude').val(lt);
            $('#startPlaceLongitude').val(lng);

            console.log(lt);
            console.log(lng);

            //after click add new point create draggable marker to work with
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                draggable: true,
                icon: '../images/marker3.png'
            });

            //int infowindow
            var infowindow = new google.maps.InfoWindow({
                content: ""
            });

            //dragend marker, use geocoder to get info about marker
            google.maps.event.addListener(marker, 'dragend', function() {
                //update inputs- lattitude and longitude, center map on dragged marker
                map.panTo(marker.getPosition()); 

                var markerPos = geocoder.geocode({
                    latLng: marker.getPosition()

                }, function(responses) {
                    if (responses && responses.length > 0) {
                        //update position and value for lattitude and longitude of new point inputs
                        $('#startPlaceLattitude').val(responses[0].geometry.location.lat());
                        $('#startPlaceLongitude').val(responses[0].geometry.location.lng());

                        console.log($('#startPlaceLattitude').val());
                        console.log($('#startPlaceLongitude').val());

                        //set content of infowindow to address easy to understand for humans and programmers e.g. woloska 65, Warszawa ... 
                        infowindow.setContent(responses[0].formatted_address);
                    } else {
                        console.log('Cannot determine address at this location.');
                    }
                });
                    
                //set marker current position adress to content of infowindow
                infowindow.open(map, marker);
            });
        });

        //get marker for stop point coordinates which you can move
        $( "#stopPlacePick" ).click(function() {
            var ctr = map.getCenter();
            var lt = ctr.lat();
            var lng = ctr.lng();

            var myLatLng = {lat: lt, lng: lng};

            $('#stopPlaceLattitude').val(lt);
            $('#stopPlaceLongitude').val(lng);

            console.log(lt);
            console.log(lng);

            //after click add new point create draggable marker to work with
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                draggable: true,
                icon: '../images/marker3.png'
            });

            //int infowindow
            var infowindow = new google.maps.InfoWindow({
                content: ""
            });

            //dragend marker, use geocoder to get info about marker
            google.maps.event.addListener(marker, 'dragend', function() {
                //update inputs- lattitude and longitude, center map on dragged marker
                map.panTo(marker.getPosition()); 

                var markerPos = geocoder.geocode({
                    latLng: marker.getPosition()

                }, function(responses) {
                    if (responses && responses.length > 0) {
                        //update position and value for lattitude and longitude of new point inputs
                        $('#stopPlaceLattitude').val(responses[0].geometry.location.lat());
                        $('#stopPlaceLongitude').val(responses[0].geometry.location.lng());

                        console.log($('#stopPlaceLattitude').val());
                        console.log($('#stopPlaceLongitude').val());

                        //set content of infowindow to address easy to understand for humans and programmers e.g. woloska 65, Warszawa ... 
                        infowindow.setContent(responses[0].formatted_address);
                    } else {
                        console.log('Cannot determine address at this location.');
                    }
                });
                    
                //set marker current position adress to content of infowindow
                infowindow.open(map, marker);
            });
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
