<?php
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");
include("templates/header.inc.php")
?>

<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link href="/css/main.css" rel="stylesheet">
        <title>Maps</title>
    </head>

    <body>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-md-push-8">
                    <div class="row">
                        <form  method="post">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input class="form-control" id="address" type="text" name="address">
                                        <div class="input-group-btn">
                                            <input id="search" class="btn btn-default" type="button" value="Suchen">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input id ="addtofavorits" class="btn btn-primary btn-block" type="button" value="Zu Favoriten hinzufügen">
                                </div>
                                <div class="col-md-12">
                                    <input id ="location" class="btn btn-default btn-block" type="button" value="Meine Position ermitteln">
                                    <input id="coordlon" type="hidden" name="Coordlon" value="Coordlon ">
                                    <input id="coordlat" type="hidden" name="Coordlat" value="Coordlat">
                                    <input id="erfolgreich" type="hidden" name="erfolgreich">
                                </div>
                                <div class="col-md-12">
                                    <label class="switch"><input type="checkbox" name="metricswitch" id="togBtn"><div class="slider round"></div></label>
                                </div>
                            </div>
                    </div>


                    <div class="col-md-12">
                        <div class="panel-body-favorits" id="favorites">
                            <?php include("favorits.php"); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 col-md-pull-4">
                    <div id="map"></div>

                    <script>
                        //Deklaration der Variabeln
                        var map;
                        var markers = [];
                        var geocoder;
                        var infowindow;
                        var success;

                        function initMap() {

                            map = new google.maps.Map(document.getElementById('map'), {
                                zoom: 11,
                                center: {lat: 47.478, lng: 8.301}
                            });

                            var input = (document.getElementById('address'));

                            var autocomplete = new google.maps.places.Autocomplete(input);
                            autocomplete.bindTo('bounds', map);

                            var marker = new google.maps.Marker({
                                map: map,
                                anchorPoint: new google.maps.Point(0, -29)
                            });

                            autocomplete.addListener('place_changed', function () {
                                infowindow.close();
                                marker.setVisible(false);
                                var place = autocomplete.getPlace();
                                if (!place.geometry) {
                                    // User entered the name of a Place that was not suggested and
                                    // pressed the Enter key, or the Place Details request failed.
                                    return;
                                }

                                // If the place has a geometry, then present it on a map.
                                if (place.geometry.viewport) {
                                    map.fitBounds(place.geometry.viewport);
                                } else {
                                    map.setCenter(place.geometry.location);
                                    map.setZoom(17);  // Why 17? Because it looks good.
                                }
                                marker.setIcon(/** @type {google.maps.Icon} */({
                                    url: place.icon,
                                    size: new google.maps.Size(71, 71),
                                    origin: new google.maps.Point(0, 0),
                                    anchor: new google.maps.Point(17, 34),
                                    scaledSize: new google.maps.Size(35, 35)
                                }));
                                marker.setPosition(place.geometry.location);
                                marker.setVisible(true);

                                var address = '';
                                if (place.address_components) {
                                    address = [
                                        (place.address_components[0] && place.address_components[0].short_name || ''),
                                        (place.address_components[1] && place.address_components[1].short_name || ''),
                                        (place.address_components[2] && place.address_components[2].short_name || '')
                                    ].join(' ');
                                }

                                infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                                infowindow.open(map, marker);
                            });

                            geocoder = new google.maps.Geocoder;
                            infowindow = new google.maps.InfoWindow;

                            /**
                             * Deaktiviert den Enter Key vom Formular und gibt eine neue Belegung.
                             */
                            $(document).keypress(
                                    function (event) {
                                        if (event.which == '13') {
                                            event.preventDefault();
                                            geocodeAddress(geocoder, map, infowindow);
                                        }
                                    });



                            //Deklaration der Event Listener
                            map.addListener('click', function (e) {

                                var clickedlat = e.latLng.lat();
                                var clickedlng = e.latLng.lng();
                                var latarray = [clickedlat, clickedlng];

                                document.getElementById("coordlat").value = e.latLng.lat();
                                document.getElementById("coordlon").value = e.latLng.lng();

                                //Funktion für die Namensauflösung wird aufgerufen
                                geocodeLatLng(geocoder, map, infowindow, latarray);
                            });//End Map Clicklistener

                            document.getElementById('search').addEventListener('click', function () {
                                geocodeAddress(geocoder, map, infowindow);
                            });//End Button Clicklistener

                            document.getElementById('location').addEventListener('click', function () {
                                geolocation(map, infowindow);
                            });//End Button Clicklistener

                            document.getElementById('addtofavorits').addEventListener('click', function () {
                                geocodeAddress(geocoder, map, infowindow);
                                setTimeout(addtofavorits, 1000);

                            });//End Button Clicklistener
                        }//End initMap

                        /**
                         *  Fügt den Marker auf der Karte und im Array ein
                         * @param {type} location 
                         */
                        function addMarker(location) {
                            var marker = new google.maps.Marker({
                                position: location,
                                map: map
                            });
                            markers.push(marker);
                        }

                        /**
                         * Wird gebraucht wennd die Grösse des Fenster angepasst wird, sonst wird die Map nicht angezeigt.  
                         * @param {type} map
                         * @returns {undefined} */
                        $(window).resize(function () {
                            var h = $(window).height(),
                                    offsetTop = 90; // Calculate the top offset
                            $('#map').css('height', (h - offsetTop));
                        }).resize();

                        /**
                         * 5 Tages Wetter Vorhersage wird aufgeklappt
                         * @param {type} e event
                         */
                        function details(e) {
                            document.getElementById("bottomStuff").style.backgroundColor = "#1a171b"; //ladekreis wird dargestellt
                            $(".bottomStuff").empty().html('<img src="/images/ladekreis.gif" id="ladekreis" class="col-sm-2 col-sm-offset-5">');
                            $(".bottomStuff").toggleClass("active");
                            $.ajax({
                                type: "POST",
                                url: 'weatherforcastdisplay.php',
                                data: {
                                    id: e.id,
                                    username: "<?php echo ($user['email']); ?>",
                                    metricswitch: document.getElementById('togBtn').checked,

                                },
                                success: function (html) {
                                    document.getElementById("bottomStuff").style.backgroundColor = "rgba(12,93,138,1)";
                                    $(".bottomStuff")
                                            .html(html)

                                }
                            });
                        }

                        /**
                         *Geklickter Favorit wird als Homebasis gesetzt und bleibt zu oberst
                         * @param {type} e event
                         */
                        function home(e) {
                            $.ajax({
                                type: "POST",
                                url: 'controller.php',
                                data: {action: 'homebase',
                                    id: e.id,
                                    username: "<?php echo ($user['email']); ?>"

                                },
                                success: function (html) {
                                    $('.panel-body-favorits').load('favorits.php');
                                    alert("Neue Homebase wurde gesetzt");
                                }
                            });
                        }

                        /**
                         * Geklickter Favorit wird entfernt
                         */
                        function remove(e) {
                            $.ajax({
                                type: "POST",
                                url: 'controller.php',
                                data: {action: 'remove',
                                    id: e.id,
                                    username: "<?php echo ($user['email']); ?>"

                                },
                                success: function (html) {
                                    $('.panel-body-favorits').load('favorits.php');
                                    alert("Favorit wurde entfernt");
                                }
                            });
                        }

                        /**
                         * Geklickter Favorit wird aktualisiert
                         * @param {type} e
                         */
                        function refresh(e) {
                            $.ajax({
                                type: "POST",
                                url: 'controller.php',
                                data: {action: 'refresh',
                                    id: e.id,
                                    username: "<?php echo ($user['email']); ?>"

                                },
                                success: function (html) {
                                    $('.panel-body-favorits').load('favorits.php');
                                    alert("Favorit wurde aktualisiert");
                                }
                            });
                        }

                        /**
                         * Ortschaft wird zu Favoriten hinzugefügt, wenn sie existiert
                         * @param {type} e
                         */
                        function addtofavorits(e) {
                            if (document.getElementById("erfolgreich").value === "Stimmt") {
                                $.ajax({
                                    type: "POST",
                                    url: 'controller.php',
                                    data: {action: 'addtofavorits',
                                        username: "<?php echo ($user['email']); ?>",
                                        coordlat: document.getElementById("coordlat").value,
                                        coordlon: document.getElementById("coordlon").value,
                                        metricswitch: document.getElementById('togBtn').checked,
                                        address: document.getElementById("address").value
                                    },
                                    success: function (html) {
                                        $('.panel-body-favorits').load('favorits.php');
                                        alert("Favorit wurde aktualisiert");
                                    }
                                });
                            } else {
                                alert("Favorit wurde nicht hinzugefügt, da keine Ortschaft gefunden wurde");
                            }

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

                        // Zeigt alle Marker in Array
                        function showMarkers() {
                            setMapOnAll(map);
                        }

                        // Löscht alle Markter
                        function deleteMarkers() {
                            clearMarkers();
                            markers = [];
                        }
                        /**
                         * Koordinaten werden in Array übergeben und Marker wird plaziert
                         * @param {type} geocoder
                         * @param {type} map
                         * @param {type} infowindow
                         * @param {type} latarray
                         */
                        function geocodeLatLng(geocoder, map, infowindow, latarray) {
                            var latlng = {lat: parseFloat(latarray[0]), lng: parseFloat(latarray[1])};
                            deleteMarkers();
                            geocoder.geocode({'location': latlng}, function (results, status) {
                                if (status === 'OK') {
                                    document.getElementById("address").value = results[2].formatted_address;
                                    map.setCenter(latlng);

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

                        /**
                         * Ortschaft wird gesucht und Marker auf Karte platziert
                         * @param {type} geocoder
                         * @param {type} resultsMap
                         * @param {type} infowindow
                         */
                        function geocodeAddress(geocoder, resultsMap, infowindow) {
                            var address = document.getElementById('address').value;
                            deleteMarkers();

                            geocoder.geocode({'address': address}, function (results, status) {
                                if (status === 'OK') {
                                    document.getElementById("erfolgreich").value = "Stimmt";
                                    resultsMap.setCenter(results[0].geometry.location);
                                    document.getElementById("coordlat").value = results[0].geometry.location.lat();
                                    document.getElementById("coordlon").value = results[0].geometry.location.lng();

                                    var latarray = [results[0].geometry.location.lat(), results[0].geometry.location.lng()];

                                    //Funktion für die Namensauflösung wird aufgerufen
                                    geocodeLatLng(geocoder, map, infowindow, latarray);


                                } else {
                                    alert('Es konnte keine Ortschaft gefunden werden');
                                    document.getElementById("erfolgreich").value = "Falsch";
                                }
                            });
                        }//End geocodeLatLng function

                        /**
                         * Findet den aktuellen Standort, muss im Browser erlaubt sein
                         * @param {type} map
                         * @param {type} infoWindow
                         */
                        function geolocation(map, infoWindow) {//Findet den aktuellen Standort
                            deleteMarkers();
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
                    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDU_3XGa4TSpXcgPck1KGeIHOWCji9Ez8I&libraries=places&callback=initMap" async defer></script>

                    <div class="bottomStuff" id="bottomStuff">

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>