@extends('layouts/app')

@section('content')
<div class="row map">

    <div class="searchForm">
        <div class="form-group">
            <input type="text" class="form-control" id="location" value="" placeholder="Search location...">
        </div>
    </div>

    {!! Mapper::render() !!}

    @if(!Session::get('token'))
        <div class="loginInfo">
            <div class="col-sm-8 col-sm-offset-2 loginInfoCol">
                <p>Please sign in to add new points, comment existing points and many more.</p>
            </div>
        </div>
    @endif

    <div class="panel">
    
        <div class="panelPointHeader"></div>
        <div class="panelPointAddress"></div>
        <div class="panelContent"></div>

        @if(Session::get('token'))
             <form id="addComment" method="post" action="{{ action('CommentController@store') }}">
                <div class="form-group">
                    <input type="text" class="form-control" id="commentBody" name="commentBody" placeholder="Comment ...">
                </div>

                <input class="form-control" id="point_id" name="point_id" type="hidden">

                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <button type="submit" class="btn btn-primary">Add Comment</button>
            </form>
        @endif
    </div>

    <!-- display add new point only for logged in users -->
    @if(Session::get('token'))
        <div class="panel2">
            <form id="addPointForm" method="post" action="{{ action('PointController@store') }}">
                <div class="form-group">
                    <input type="text" class="form-control" id="pointName" name="pointName" placeholder="Point name">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="pointDescription" name="pointDescription" placeholder="Point description">
                </div>
                <div class="form-group" style="display: none;">
                    <input type="text" class="form-control" id="lattitude" name="lattitude" value="">
                </div>
                <div class="form-group" style="display: none;">
                    <input type="text" class="form-control" id="longitude" name="longitude" value="">
                </div>
                <div class="form-group">
                    <label for="rating">Your average rate for that point from 1 to 5:</label>
                    <select class="form-control" id="rating" name="rating">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rating">Your safety rate for that point from 1 to 5:</label>
                    <select class="form-control" id="safety" name="safety">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <button type="submit" class="btn btn-primary">Add point</button>
            </form>

            <div id="closePanel2">
                <i class="fas fa-times"></i>
            </div>                           
        </div>
        <div class="btn btn-default" id="addPointBtn"><i class="fas fa-plus-circle"></i></div>
    @endif

</div>
@endsection


<script type="text/javascript">
    function addMapStyling(map) {

        //close add new point panel
        $('#closePanel2').click(function(){ 
            $('.panel2').css('display', 'none'); 
            $("#addPointBtn").css('display', 'block');
        });

        //init geocoder
        var geocoder = new google.maps.Geocoder();

        /*search box autocomplete */
        var autocomplete = new google.maps.places.Autocomplete(document.getElementById('location'));
        autocomplete.bindTo('bounds', map);

         var marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29)
        });

        autocomplete.addListener('place_changed', function() {
          marker.setVisible(false);
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            window.alert("No details available for input: '" + place.name + "'");
            return;
          }

          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17); 
          }
          marker.setPosition(place.geometry.location);
          marker.setVisible(false);

          var address = '';
          if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
          }
        });

        //add new point based on user click map position when user click on map-canvas-0
        document.getElementById('map-canvas-0').onclick = function(){
            //get cursor position on map 
            //$("#addPointBtn").css('display', 'block');

            //when user click addPointBtn create draggable marker and update lat and lng when user drag marker, dont display plus button to prevent 
            //creating few markers. display panel div to display data inside of that.
            $( "#addPointBtn" ).click(function() {
                $(".panel").css('display', 'none');
                $("#addPointBtn").css('display', 'none');
                var ctr = map.getCenter();
                var lt = ctr.lat();
                var lng = ctr.lng();

                var myLatLng = {lat: lt, lng: lng};

                $('#lattitude').val(lt);
                $('#longitude').val(lng);

                console.log(lt);
                console.log(lng);

                //after click add new point create draggable marker to work with
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    draggable: true,
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
                            $('#lattitude').val(responses[0].geometry.location.lat());
                            $('#longitude').val(responses[0].geometry.location.lng());

                            console.log($('#lattitude').val());
                            console.log($('#longitude').val());

                            //set content of infowindow to address easy to understand for humans and programmers e.g. woloska 65, Warszawa ... 
                            infowindow.setContent(responses[0].formatted_address);
                        } else {
                            console.log('Cannot determine address at this location.');
                        }
                    });
                        
                    //set marker current position adress to content of infowindow
                    infowindow.open(map, marker);
                });
                
                $(".panel2").css('display', 'block');
            });
        }
        
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