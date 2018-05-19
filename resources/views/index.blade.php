@extends('layouts/app')

@section('content')
<div class="row map">

    <div class="searchForm">
        <div class="form-group">
            <input type="text" class="form-control" id="location" value="" placeholder="Search location...">

            <div id="searchThisRegion" class="btn btn-default">SEARCH THIS REGION</div>
        </div>
    </div>

    {!! Mapper::render() !!}

    @if(!Session::get('token'))
        <div class="loginInfo">
            <div class="col-sm-8 col-sm-offset-2 loginInfoCol">
                <p>Please sign in to add new points, events, comment existing points and many more.</p>
            </div>
        </div>
    @endif

    @if(Session::get('token'))
        <div class="logedInfo">
            <div class="col-sm-8 col-sm-offset-2 logedInfoCol">
                <p>Move the map and add new point by click button in top right area</p>
            </div>
        </div>
    @endif

    <div class="placesButtonsPanel">
        <div id="gasStationsBtn">
            <p>Gas stations<span class="arrow"></span></p>
            <div class="imageDiv">
                <img src="{{ URL::to('/') }}/images/gasStation.png">
            </div>
        </div>

        <div id="supermarketBtn">
            <p>Supermarkets</p>
            <div class="imageDiv">
                <img src="{{ URL::to('/') }}/images/supermarket.png">
            </div>
            
        </div>

        <div id="trainStationsBtn">
            <p>Train stations</p>
            <div class="imageDiv">
                <img src="{{ URL::to('/') }}/images/trainStation.png">  
            </div>
        </div>

        <div id="bankBtn">
            <p>Bank places</p>
            <div class="imageDiv">
                <img src="{{ URL::to('/') }}/images/bank.png">
            </div>
        </div>
    </div>

    <div class="panel">
    
        <div class="panelPointHeader"></div>
        <div class="panelPointAddress"></div>
        <div class="panelContent"></div>
        <div class="panelComments"></div>

        @if(Session::get('token'))
             <form id="addComment" method="post" action="{{ action('CommentController@store') }}">
                <div class="form-group">
                    <input type="text" class="form-control" id="commentBody" name="commentBody" placeholder="Comment ..." required>
                </div>

                <input class="form-control" id="point_id" name="point_id" type="hidden">

                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <button type="submit" class="btn btn-default addCommentBtn">Add Comment</button>
            </form>
        @endif
    </div>

    <!-- display add new point only for logged in users -->
    @if(Session::get('token'))
        <div class="panel2">
            <h1>Add new point</h1>
            <form id="addPointForm" method="post" action="{{ action('PointController@store') }}">
                <div class="form-group">
                    <input type="text" class="form-control" id="pointName" name="pointName" placeholder="Point name" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="pointDescription" name="pointDescription" placeholder="Point description" required>
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
                <button type="submit" class="btn btn-default addPointBtn">Add point</button>
            </form>

            <div id="closePanel2">
                <i class="fas fa-times"></i>
            </div>                           
        </div>
        <div class="btn btn-default" id="addPointBtn">New Point</div>
    @endif

</div>
@endsection


<script type="text/javascript">
    function addMapStyling(map) {

        //close add new point panel
        $('#closePanel2').click(function(){ 
            $('.panel2').css('display', 'none'); 
            $(".logedInfo").css('display', 'block');
            $(".searchForm").css('display', 'block');
            $("#addPointBtn").css('display', 'block');
        });



        $('.placesButtonsPanel > div').mouseover(function(){
            $(this).find("p").css('display',  'inline-block');
        });
        $('.placesButtonsPanel > div').mouseout(function(){
            $(this).find("p").css('display',  'none');
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


        // Deletes all markers in the array by removing references to them.
        function deleteMarkers() {
            clearMarkers();
            markers = [];
        }

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


        /* ==========================================

                    SEARCH THIS REGION BUTTON 

        ===========================================*/
        $('#searchThisRegion').click(function(){
            console.log("search");

            var ctr = map.getCenter();
            var lt = ctr.lat();
            var lng = ctr.lng();

            ltAdd = lt - 0.00005;
            lngAdd = lng - 0.00005;

            //console.log(lt + ", " + lng);

            //define range where search point
            urlApi = 'http://hitchwiki.org/maps/api/?bounds=' + lt + "," + lng + "," + ltAdd + "," + lngAdd + "&callback=?";
            console.log(urlApi);

            //get json with points list data in region
            $.ajax({
                type: "GET",
                url: urlApi,
                contentType: "application/json; charset=utf-8",
                async: false,
                dataType: "json",
                success: function (data, textStatus, jqXHR) {
                    //console.log(data);

                    //it only return id,coordinates and voting so you need to get info for every id
                    for (var i = 0; i < data.length; i++) {
                        //console.log(data[i].id);
        
                        let pointUrlApi = 'http://hitchwiki.org/maps/api/?place=' + data[i].id + "&callback=?";

                        $.ajax({
                            type: "GET",
                            url: pointUrlApi,
                            contentType: "application/json; charset=utf-8",
                            async: false,
                            dataType: "json",
                            success: function (singleData, textStatus, jqXHR) {
                                console.log(singleData);

                                let LatLng = {lat: Number(singleData.lat), lng: Number(singleData.lon)};

                                let marker = new google.maps.Marker({
                                    position: LatLng,
                                    map: map,
                                    draggable: true,
                                    icon: '../images/marker2.png'
                                });

                                //init infoWindow
                                let infowindow = new google.maps.InfoWindow();

                                google.maps.event.addListener(marker, 'click', function() {
                                    //infowindow.setContent(singleData.description['en_UK'].description);
                                    //infowindow.open(map, marker);

                                    //$('#point_id').val('' . $id . '');
                                    $('#closePanel').remove();
                                    $('.loginInfo').css('display', 'none');
                                    $(".logedInfo").css('display', 'none'); 
                                    $(".searchForm").css('display', 'none');

                                    $('.panel2').css('display', 'none');
                                    $('#addPointBtn').css('display', 'none');
                                    $('.panel').css('display', 'block');

                                    $('.panelPointHeader').empty();
                                    $('.panelPointAddress').empty();
                                    $('.panelComments').empty();
                                    $('.panelContent').empty();  

                                    $('.panelPointHeader').append('<h1 class="pointName">' + singleData.location.country['name'] + ' ' + singleData.id + '</h1>');
                                    $('.panelPointHeader').append('<p>' + singleData.description['en_UK'].description + '</p>');

                                    
                                    /*var lat = ' . $lat . ';
                                    var lng = ' . $lng .';
                                    geocoder2 = new google.maps.Geocoder();
                                    var latlng = new google.maps.LatLng(lat, lng);
                                    geocoder2.geocode({
                                        'latLng': latlng
                                    }, function(results, status) {
                                        $('.panelPointAddress').append('<p>Address: ' + results[4].formatted_address + '</p>');
                                    });*/

                                    $('.panelContent').append('<p>Rating: ' + singleData.rating + '</p>');
                    
                                    $('.panelContent').append('<p>Created by: ' + singleData.user['name'] + '</p>');

                                    //$('.panelComments').append('<h3>Comments:</h3>');
                                    //$('.panelComments').append('' . $commentToPost . '');

                                    $('.panel').append('<div id="closePanel"><i class="fas fa-times"></i></div>');
                                    $('#closePanel').click(function(){ $(".logedInfo").css('display', 'block'); $(".searchForm").css('display', 'block'); $('.panel').css('display', 'none'); $('.loginInfo').css('display', 'block'); $('#addPointBtn').css('display', 'block');}); 
                                });
                                
                            },
                            error: function (errorMessage) {
                                console.log(errorMessage);
                            }
                        });

                }
            },
            error: function (errorMessage) {
                console.log(errorMessage);
            }
        });





        });


        /* ==========================================

                    GAS STATION BUTTON 

        ===========================================*/
        document.getElementById('gasStationsBtn').onclick = function(){
            //get cursor coordinates
            var ctr = map.getCenter();
            var lt = ctr.lat();
            var lng = ctr.lng();
            var myLatLng = {lat: lt, lng: lng};

            console.log(myLatLng.lat);

            //search nearby service type=gas_station
            var service = new google.maps.places.PlacesService(map);
            service.nearbySearch({
                location: myLatLng,
                radius: 10000,
                type: ['gas_station']
            }, callback);

            //init infoWindow
            var infowindow = new google.maps.InfoWindow();

            function callback(results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    //for all results create markers and infowindows
                    for (var i = 0; i < results.length; i++) {

                        let LatLng = {lat: results[i].geometry.location.lat(), lng: results[i].geometry.location.lng()};

                        let marker = new google.maps.Marker({
                            position: LatLng,
                            map: map,
                            draggable: true
                        });

                        console.log(results[i]);
                        let stationAddress = results[i].vicinity;
                        let stationName = results[i].name;

                        google.maps.event.addListener(marker, 'click', function() {
                            infowindow.setContent(stationName + "<br>" + stationAddress);
                            infowindow.open(map, marker);
                        });
                    }
                }
            }
        }



        /* ==========================================

                    SUPERMARKET BUTTON 

        ===========================================*/
        document.getElementById('supermarketBtn').onclick = function(){
            //get cursor coordinates
            var ctr = map.getCenter();
            var lt = ctr.lat();
            var lng = ctr.lng();
            var myLatLng = {lat: lt, lng: lng};

            console.log(myLatLng.lat);

            //search nearby service type=gas_station
            var service = new google.maps.places.PlacesService(map);
            service.nearbySearch({
                location: myLatLng,
                radius: 10000,
                type: ['supermarket']
            }, callback);

            //init infoWindow
            var infowindow = new google.maps.InfoWindow();

            function callback(results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    //for all results create markers and infowindows
                    for (var i = 0; i < results.length; i++) {

                        let LatLng = {lat: results[i].geometry.location.lat(), lng: results[i].geometry.location.lng()};

                        let marker = new google.maps.Marker({
                            position: LatLng,
                            map: map,
                            draggable: true
                        });

                        console.log(results[i]);
                        let stationAddress = results[i].vicinity;
                        let stationName = results[i].name;

                        google.maps.event.addListener(marker, 'click', function() {
                            infowindow.setContent(stationName + "<br>" + stationAddress);
                            infowindow.open(map, marker);
                        });
                    }
                }
            }
        }



        /* ==========================================

                    TRAIN STATION BUTTON 

        ===========================================*/
        document.getElementById('trainStationsBtn').onclick = function(){
            //get cursor coordinates
            var ctr = map.getCenter();
            var lt = ctr.lat();
            var lng = ctr.lng();
            var myLatLng = {lat: lt, lng: lng};

            console.log(myLatLng.lat);

            //search nearby service type=gas_station
            var service = new google.maps.places.PlacesService(map);
            service.nearbySearch({
                location: myLatLng,
                radius: 10000,
                type: ['train_station']
            }, callback);

            //init infoWindow
            var infowindow = new google.maps.InfoWindow();

            function callback(results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    //for all results create markers and infowindows
                    for (var i = 0; i < results.length; i++) {

                        let LatLng = {lat: results[i].geometry.location.lat(), lng: results[i].geometry.location.lng()};

                        let marker = new google.maps.Marker({
                            position: LatLng,
                            map: map,
                            draggable: true
                        });

                        console.log(results[i]);
                        let stationAddress = results[i].vicinity;
                        let stationName = results[i].name;

                        google.maps.event.addListener(marker, 'click', function() {
                            infowindow.setContent(stationName + "<br>" + stationAddress);
                            infowindow.open(map, marker);
                        });
                    }
                }
            }
        }



        /* ==========================================

                    BANK BUTTON 

        ===========================================*/
        document.getElementById('bankBtn').onclick = function(){
            //get cursor coordinates
            var ctr = map.getCenter();
            var lt = ctr.lat();
            var lng = ctr.lng();
            var myLatLng = {lat: lt, lng: lng};

            console.log(myLatLng.lat);

            //search nearby service type=gas_station
            var service = new google.maps.places.PlacesService(map);
            service.nearbySearch({
                location: myLatLng,
                radius: 10000,
                type: ['bank']
            }, callback);

            //init infoWindow
            var infowindow = new google.maps.InfoWindow();

            function callback(results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    //for all results create markers and infowindows
                    for (var i = 0; i < results.length; i++) {

                        let LatLng = {lat: results[i].geometry.location.lat(), lng: results[i].geometry.location.lng()};

                        let marker = new google.maps.Marker({
                            position: LatLng,
                            map: map,
                            draggable: true
                        });

                        console.log(results[i]);
                        let stationAddress = results[i].vicinity;
                        let stationName = results[i].name;

                        google.maps.event.addListener(marker, 'click', function() {
                            infowindow.setContent(stationName + "<br>" + stationAddress);
                            infowindow.open(map, marker);
                        });
                    }
                }
            }
        }




        //add new point based on user click map position when user click on map-canvas-0
        document.getElementById('map-canvas-0').onclick = function(){
            $('.logininfo').css('display', 'none');
            $( "#addPointBtn" ).css('display', 'block');
            //get cursor position on map 
            //when user click addPointBtn create draggable marker and update lat and lng when user drag marker, dont display plus button to prevent 
            //creating few markers. display panel div to display data inside of that.
            $( "#addPointBtn" ).click(function() {
                $(".panel").css('display', 'none');
                $(".logedInfo").css('display', 'none');
                $(".searchForm").css('display', 'none');
                $("#addPointBtn").css('display', 'none');

                var ctr = map.getCenter();
                var lt = ctr.lat();
                var lng = ctr.lng();

                var myLatLng = {lat: lt, lng: lng};

                $('#lattitude').val(lt);
                $('#longitude').val(lng);

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