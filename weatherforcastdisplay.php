
<!DOCTYPE html>
<html>
    <head>
       	<script src="/lib/js/jquery.min.js"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.4/highcharts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.4/js/modules/series-label.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.4/js/modules/exporting.js"></script>
        <style>
            #weathercontainer{

                align-content: center;
                align-items: center;
            }
            #container{
                width: 50%;
                height: 270px;
                display: none;
            }

            .highcharts-background {
                fill: #767f8e;
                stroke: #a4edba;
                stroke-width: 2px;
            }

            .labels{
                color:#f7f7f7;
            }

        </style>
    </head>




    <body>
        <?php
        session_start();
        require_once("inc/config.inc.php");
        require_once("inc/functions.inc.php");
        include("lib/inc/chartphp_dist.php");

        setlocale(LC_TIME, "de_DE.utf8");
        $user = check_user();
        $unit = setUnit();



//Id des Ortes wird vom Angeklickten Objekt geladen
        $statement = $pdo->prepare("SELECT * FROM favorits WHERE id='" . $_POST['id'] . "'");
        $result = $statement->execute();
        $row = $statement->fetch();

        $forcast = new weatherforcast($row["coordlat"], $row["coordlon"], $unit, Null);
        $forcastd1 = $forcast->getForcastD1();
        $forcastd2 = $forcast->getForcastD2();
        $forcastd3 = $forcast->getForcastD3();
        $forcastd4 = $forcast->getForcastD4();
        $forcastd5 = $forcast->getForcastD5();


//Wochentage werden in Array gepackt
        $tage = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag");
        $tag = date("w");
        $tage[$tag];


//Daten für Grafik
        $city = array($forcastd1->getCity());
        $mintemp = array($forcastd1->getMainMinTemp(), $forcastd2->getMainMinTemp(), $forcastd3->getMainMinTemp(), $forcastd4->getMainMinTemp(), $forcastd5->getMainMinTemp());
        $maxtemp = array($forcastd1->getMainMaxTemp(), $forcastd2->getMainMaxTemp(), $forcastd3->getMainMaxTemp(), $forcastd4->getMainMaxTemp(), $forcastd5->getMainMaxTemp());
        $feuchtigkeit = array($forcastd1->getMainHumidity(), $forcastd2->getMainHumidity(), $forcastd3->getMainHumidity(), $forcastd4->getMainHumidity(), $forcastd5->getMainHumidity());
        $wind = array($forcastd1->getWindSpeed(), $forcastd2->getWindSpeed(), $forcastd3->getWindSpeed(), $forcastd4->getWindSpeed(), $forcastd5->getWindSpeed());
        $pressure = array($forcastd1->getMainPressure(), $forcastd2->getMainPressure(), $forcastd3->getMainPressure(), $forcastd4->getMainPressure(), $forcastd5->getMainPressure());
        $sunrise = array($forcastd1->getSunrise(), $forcastd2->getSunrise(), $forcastd3->getSunrise(), $forcastd4->getSunrise(), $forcastd5->getSunrise());
        $sunset = array($forcastd1->getSunset(), $forcastd2->getSunset(), $forcastd3->getSunset(), $forcastd4->getSunset(), $forcastd5->getSunset());
        $icon = array($forcastd1->getWeatherIcon(), $forcastd2->getWeatherIcon(), $forcastd3->getWeatherIcon(), $forcastd4->getWeatherIcon(), $forcastd5->getWeatherIcon());

//Div für Buttons
        echo '<div id="button-container" class="col-sm-3 col-sm-offset-4" >
                <button id="wind" type="button" class="btn btn-info btn-sm" onclick="graph(this)">Windstärke</button>
                <button id="temp" type="button" class="btn btn-info btn-sm" onclick="graph(this)">Temperatur</button>
                <button id="feuchtigkeit" type="button" class="btn btn-info btn-sm" onclick="graph(this)">Luftfeuchigkeit</button>
                <button id="pressure" type="button" class="btn btn-info btn-sm" onclick="graph(this)">Luftdruck</button>
        </div>';
//Div in dem die Grafik angezeigt wird
        echo '<div id="container" class="col-sm-2 col-sm-offset-3"></div>'; 

        //Div für Vohersage
        echo '<div class="container-fluid">
                <div id="closecross">  <img src="/images/cross.png" alt="closecross" height="50" style="cursor: pointer;"></div>
                 <div class="row" >
                    <div class="col-sm-6 col-sm-offset-3" id="graph"></div>
                </div>
            <div class="row" >';
        //For Schlaufe die alle Daten anzeigt
        for ($x = 0; $x <= 4; $x++) {
            if ($x == 0) {
                echo '  <div class="col-sm-2 col-sm-offset-1" id="weathercontainer" >
                <div class="day-name">' . $tage[$tag + $x + 1] . '</div>
                    <img src="/images/' . $icon[$x] . '.png" alt="weathericon">
                   <div class="top-right1"><img id="description icon" src="/images/windicon.png" height="30">' . $wind[$x] . ' km/h</div>
                   <div class="top-right2"><img id="description icon" src="/images/minmax.png" height="20">' . $mintemp[$x] . ' / ' . $maxtemp[$x] . '</div>
                   <div class="top-right3"><img id="description icon" src="/images/sunset.png" height="20">' . $sunrise[$x] . ' / ' . $sunset[$x] . '</p></div>
                </div>';
            } else {
                echo '<div class="col-sm-2" id="weathercontainer">
                <div class="day-name">' . $tage[$tag + $x + 1] . '</div>
                    <img src="/images/' . $icon[$x] . '.png" alt="weathericon">
                   <div class="top-right1"><img id="description icon" src="/images/windicon.png" height="30">' . $wind[$x] . ' km/h</div>
                   <div class="top-right2"><img id="description icon" src="/images/minmax.png" height="20">' . $mintemp[$x] . ' / ' . $maxtemp[$x] . '</div>
                   <div class="top-right3"><img id="description icon" src="/images/sunset.png" height="20">' . $sunrise[$x] . ' / ' . $sunset[$x] . '</p></div>
                </div>';
            }
        }
        echo'    </div>
            </div>
        </div>';
        ?>
        <script type="text/javascript">
            /**
             * 
             * Wird bei Button Klick ausgeführt
             */
            function graph(e) {
                //Zeigt das Grafik Div bei Klick auf Button an
                var x = document.getElementById("container");
                if (x.style.display === "none") {
                    x.style.display = "block";
                } else {
                    x.style.display = "none";
                }

                //initialisiert das Array mit den Tagen
                var jarraydays =<?php echo json_encode($tage); ?>;

                //Prüft welcher Buttpn geklikt wird
                switch (e.id) {
                    case "temp":
                        var jarraydata1 =<?php echo json_encode($mintemp); ?>;
                        var jarraydata2 =<?php echo json_encode($maxtemp); ?>;
                        tempgraph(jarraydata1, jarraydata2, jarraydays, "mintemp", "maxtemp", "Temperatur")
                        break;
                    case "wind":
                        var jarraydata1 =<?php echo json_encode($wind); ?>;
                        tempgraph(jarraydata1, "", jarraydays, "windspeed", " ", "Speed");
                        break;
                    case "feuchtigkeit":
                        var jarraydata1 =<?php echo json_encode($feuchtigkeit); ?>;
                        tempgraph(jarraydata1, "", jarraydays, "Luftfeuchtigkeit", " ", "Feuchtigkeit");
                        break;
                    case "pressure":
                        var jarraydata1 =<?php echo json_encode($pressure); ?>;
                        tempgraph(jarraydata1, "", jarraydays, "Luftdruck", " ", "Druck");
                        break;
                }

            }
            /**
             * 
             * @param {type} pData1
             * @param {type} pData2
             * @param {type} pDays
             * @param {type} pNameD1
             * @param {type} pNameD2
             * @param {type} pYAxisTitel
             * 2 Daten Arrays werden übergeben für die lienien + Beschriftung der Achens usw.
             * Zeichnet die Grafik im Div
             */
            function tempgraph(pData1, pData2, pDays, pNameD1, pNameD2, pYAxisTitel) {
                Highcharts.chart('container', {

                    title: {
                        text: 'Wettervorhersagee für <?php echo $city[0]; ?>'
                    },

                    yAxis: {
                        title: {
                            text: pYAxisTitel
                        },

                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle'
                    },

                    xAxis: {
                        categories: pDays
                    },

                    series: [{
                            name: pNameD1,
                            data: pData1
                        }, {
                            name: pNameD2,
                            data: pData2
                        }],

                    responsive: {
                        rules: [{
                                condition: {
                                    maxWidth: 500
                                },
                                chartOptions: {
                                    legend: {
                                        layout: 'horizontal',
                                        align: 'center',
                                        verticalAlign: 'bottom'
                                    }
                                }
                            }]
                    }

                });
            }

            //Wird gebraucht für den Schliessen Button
            $("#closecross").click(function (e) {
                $(this).closest(".bottomStuff")
                        .toggleClass("active");

            });
        </script>
    </body>
</html>



