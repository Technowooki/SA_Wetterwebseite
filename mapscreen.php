<?php
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");
include("templates/header.inc.php")
?>

<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <title>Maps</title>
        <style>
            /* Always set the map height explicitly to define the size of the div
             * element that contains the map. */

            #wrap{
                height:88%; 
            }
            #map {
                height: 100%;
            }
            /* Optional: Makes the sample page fill the window. */
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }

            .buttonclick{
                cursor:pointer; 
            }

            #latlng {
                width: 225px;
            }

            .panel-group{
                float: right;
                width: 370px;
                border: 3px solid #73AD21;
                padding: 5px;
                height: 100%;
                background-color: rgba(0, 229, 255, 0.0);

            }
            .panel-body-favorits{
                overflow-y:scroll;  
                border: 3px solid #73AD21;
                height:80%;
            }

            .panel-body-form{
                height:20%;
                border: 3px solid #FF0000;
            }


            p {
                font-weight: bold;
                font-size: 16px;
            }
            .box {
                width: 330px;
                height: 170px;
                background-color: #0C5D8A;
                /*        background-color: transparent;*/
                color: #fff;
                margin: 3em auto;
                border: 3px;
                border-color: #000000;
                padding-left: 10px;
                padding-top: 5px;
            }

            .rounded {
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
            }

            .switch {
                position: relative;
                display: inline-block;
                width: 90px;
                height: 34px;
            }

            .switch input {display:none;}

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ca2222;
                -webkit-transition: .4s;
                transition: .4s;
                border-radius: 34px;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                -webkit-transition: .4s;
                transition: .4s;
                border-radius: 50%;
            }

            input:checked + .slider {
                background-color: #0000cc;
            }

            input:focus + .slider {
                box-shadow: 0 0 1px #2196F3;
            }

            input:checked + .slider:before {
                -webkit-transform: translateX(26px);
                -ms-transform: translateX(26px);
                transform: translateX(55px);
            }

            /*------ ADDED CSS ---------*/
            .slider:after
            {
                content:'°C';
                color: white;
                display: block;
                position: absolute;
                transform: translate(-50%,-50%);
                top: 50%;
                left: 50%;
                font-size: 20px;
                font-family: Verdana, sans-serif;
            }

            input:checked + .slider:after
            {  
                content:'°F';
            }


            
            .bottomStuff {
                position: fixed;
                bottom: -70%;
                height: 450px;
              background-color: rgba(30,144,255,0.8);
                transition: bottom .5s;
                width:100%;
            }

            .bottomStuff.active {
                position: fixed;
                bottom: 0;
            }
            #closecross{
                float: right;
            }
        </style>
    </head>



    <body>
        <div id="wrap">
            <div id="bottompanel">
            </div>
            <div class="panel-group">
                <div class="panel-body-form">
                    <form action="controller.php" method="post">

                        <input id="address" type="text" name="address" value="ortschaft eingeben">
                        <input type="submit" value="Add to favorits!">
                        <input id="coordlon" type="text" name="Coordlon" value="Coordlon ">
                        <input id="submit" type="button" value="search">
                        <input id="coordlat" type="text" name="Coordlat" value="Coordlat">

                        <input id="location" type="button" value="getyourlcation">


                        <label class="switch"><input type="checkbox" name="metricswitch" id="togBtn"><div class="slider round"></div></label>
                    </form>


                </div>

                <div class="panel-body-favorits" id="favorites">
                    <?php include("displaydbcontent.php"); ?>
                </div>
            </div> <!--end Div Panelgrooup -->

            <div id="map"></div>

            <script>
                var map;
                var markers = [];
                var geocoder;

                function initMap() {
                    map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 8,
                        center: {lat: 40.731, lng: -73.997}
                    });
                    geocoder = new google.maps.Geocoder;
                    var infowindow = new google.maps.InfoWindow;


                    map.addListener('click', function (e) {

                        var clickedlat = e.latLng.lat();
                        var clickedlng = e.latLng.lng();
                        var latarray = [clickedlat, clickedlng];

                        document.getElementById("coordlat").value = e.latLng.lat();
                        document.getElementById("coordlon").value = e.latLng.lng();

                        //Funktion für die Namensauflösung wird aufgerufen
                        geocodeLatLng(geocoder, map, infowindow, latarray);
                    });//End Map Clicklistener

                    document.getElementById('submit').addEventListener('click', function () {
                        geocodeAddress(geocoder, map, infowindow);
                    });//End Button Clicklistener

                    document.getElementById('location').addEventListener('click', function () {
                        geolocation(map, infowindow);
                    });//End Button Clicklistener

                }//End initMap

                // Adds a marker to the map and push to the array.
                function addMarker(location) {
                    var marker = new google.maps.Marker({
                        position: location,
                        map: map
                    });
                    markers.push(marker);
                }

                function details(e) {
                    $.ajax({
                        type: "POST",
                        url: 'weatherforcastdisplay.php',
                        data: {
                            id: e.id,
                            username: "<?php echo ($user['vorname']); ?>",
                            metricswitch:   document.getElementById('togBtn').checked

                        },
                        success: function (html) {
                            $(".bottomStuff")

                                    .html(html)
                                    .toggleClass("active");
                        }
                    });


                }

                //Setzt ausgewähltes Element als Homebasis
                function home(e) {
                    $.ajax({
                        type: "POST",
                        url: 'test.php',
                        data: {action: 'homebase',
                            id: e.id,
                            username: "<?php echo ($user['vorname']); ?>"

                        },
                        success: function (html) {
                            $('.panel-body-favorits').load('displaydbcontent.php');
                            alert("Neue Homebase wurde gesetzt");
                        }
                    });
                }



                function remove(e) {
                    $.ajax({
                        type: "POST",
                        url: 'test.php',
                        data: {action: 'remove',
                            id: e.id,
                            username: "<?php echo ($user['vorname']); ?>"

                        },
                        success: function (html) {
                            $('.panel-body-favorits').load('displaydbcontent.php');
                            alert("Favorit wurde entfernt");
                        }
                    });
                }
                // Sets the map on all markers in the array.
                function setMapOnAll(map) {
                    for (var i = 0; i < markers.length; i++) {
                        markers[i].setMap(map);
                    }
                }

                // Removes the markers from the map, but keeps them in the array.
                function clearMarkers() {
                    setMapOnAll(null);
                }

                // Shows any markers currently in the array.
                function showMarkers() {
                    setMapOnAll(map);
                }

                // Deletes all markers in the array by removing references to them.
                function deleteMarkers() {
                    clearMarkers();
                    markers = [];
                }

                function geocodeLatLng(geocoder, map, infowindow, latarray) { //Array mit lat und lng wird übergeben und infoWindow mit Ortschaft und Koordinaten wird erstellt
                    var latlng = {lat: parseFloat(latarray[0]), lng: parseFloat(latarray[1])};
                    deleteMarkers();
                    geocoder.geocode({'location': latlng}, function (results, status) {
                        if (status === 'OK') {
                            map.setCenter(latlng);
                            document.getElementById("address").value = results[2].formatted_address;
                            if (results[1]) {
                                var marker = new google.maps.Marker({
                                    position: latlng,
                                    map: map
                                });
                                markers.push(marker);
                                infowindow.setContent(results[2].formatted_address + "<br>" + "  " + latarray);
                                infowindow.open(map, marker);
                            } else {
                                window.alert('Keine Ortschaft gefunden');
                            }
                        } else {
                            window.alert('Keine Ortschaft gefunden');
                        }
                    });
                }//End goecodeLatLng function

                function geocodeAddress(geocoder, resultsMap, infowindow) { //Ortschaft wird gesucht und Marker auf Karte platziert
                    var address = document.getElementById('address').value;
                    geocoder.geocode({'address': address}, function (results, status) {
                        if (status === 'OK') {
                            resultsMap.setCenter(results[0].geometry.location);
                            document.getElementById("coordlat").value = results[0].geometry.location.lat();
                            document.getElementById("coordlon").value = results[0].geometry.location.lng();
                            var marker = new google.maps.Marker({
                                map: resultsMap,
                                position: results[0].geometry.location
                            });
                            infowindow.setContent(address + "<br>" + results[0].geometry.location);
                            infowindow.open(map, marker);
                        } else {
                            alert('Geocode was not successful for the following reason: ' + status);
                        }
                    });
                }//End geocodeLatLng function

                function geolocation(map, infoWindow) {//Findet den aktuellen Standort

                    geocoder = new google.maps.Geocoder;
                    var infowindow = new google.maps.InfoWindow;
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function (position) {
                            var pos = {//Array mit latlng
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };

                            document.getElementById("coordlat").value = position.coords.latitude;
                            document.getElementById("coordlon").value = position.coords.longitude;
                            var latarray = [position.coords.latitude, position.coords.longitude];


                            //Funktion für die Namensauflösung wird aufgerufen
                            geocodeLatLng(geocoder, map, infowindow, latarray);

                        }, function () {
                            handleLocationError(true, infoWindow, map.getCenter());
                        });
                    } else {
                        // Browser doesn't support Geolocation
                        handleLocationError(false, infoWindow, map.getCenter());
                    }
                }

                function handleLocationError(browserHasGeolocation, infoWindow, pos) { //Fehlerbehandlung von geolocation
                    infoWindow.setPosition(pos);
                    infoWindow.setContent(browserHasGeolocation ?
                            'Error: The Geolocation service failed.' :
                            'Error: Your browser doesn\'t support geolocation.');
                }




            </script>
            <script async defer
                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDU_3XGa4TSpXcgPck1KGeIHOWCji9Ez8I&callback=initMap">
            </script>


            <div class="bottomStuff" id="bottomStuff">

            </div>

    </body>
</html>