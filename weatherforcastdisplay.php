
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
                height: 200px;

            }


            .highcharts-background {
                fill: #767f8e;
                stroke: #a4edba;
                stroke-width: 2px;
            }

        </style>


    </head>




    <body>
        <?php
        session_start();
        require_once("inc/config.inc.php");
        require_once("inc/functions.inc.php");
        include("lib/inc/chartphp_dist.php");
        ?>


        <?php
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

        $mintemp = array($forcastd1->getMainMinTemp(), $forcastd2->getMainMinTemp(), $forcastd3->getMainMinTemp(), $forcastd4->getMainMinTemp(), $forcastd5->getMainMinTemp());
        $maxtemp = array($forcastd1->getMainMaxTemp(), $forcastd2->getMainMaxTemp(), $forcastd3->getMainMaxTemp(), $forcastd4->getMainMaxTemp(), $forcastd5->getMainMaxTemp());

        echo '<div id="container"></div>'; //Div in dem die Grafik angezeigt wird
//Ausgabe die zurückgegeben wird und in Butto
        echo'  <div class="container-fluid">
            <div id="closecross">  <img src="/images/cross.png" alt="closecross" height="50"></div>
            
            <div class="row" >
                <div class="col-sm-6 col-sm-offset-3" id="graph">
                ^
                </div>
            </div>
            <div class="row" >
                <div class="col-sm-2 col-sm-offset-1" id="weathercontainer" >
                <div class="day-name">' . $tage[$tag + 1] . '</div>
                       <img src="/images/' . $forcastd1->getWeatherIcon() . '.png" alt="weathericon">
                   <div class="top-right1"><img id="description icon" src="/images/windicon.png" height="30">' . $forcastd1->getWindSpeed() . ' km/h</div>
                   <div class="top-right2"><img id="description icon" src="/images/minmax.png" height="20">' . $forcastd1->getMainMinTemp() . ' / ' . $forcastd1->getMainMaxTemp() . '</div>
                   <div class="top-right3"><img id="description icon" src="/images/sunset.png" height="20">' . $forcastd1->getSunrise() . ' / ' . $forcastd1->getSunset() . '</p></div>
                </div>
                <div class="col-sm-2" id="weathercontainer">
                <div class="day-name">' . $tage[$tag + 2] . '</div>
                    <img src="/images/' . $forcastd2->getWeatherIcon() . '.png" alt="weathericon">
                   <div class="top-right1"><img id="description icon" src="/images/windicon.png" height="30">' . $forcastd2->getWindSpeed() . ' km/h</div>
                   <div class="top-right2"><img id="description icon" src="/images/minmax.png" height="20">' . $forcastd2->getMainMinTemp() . ' / ' . $forcastd2->getMainMaxTemp() . '</div>
                   <div class="top-right3"><img id="description icon" src="/images/sunset.png" height="20">' . $forcastd2->getSunrise() . ' / ' . $forcastd2->getSunset() . '</p></div>
                </div>
                <div class="col-sm-2" id="weathercontainer" >
                <div class="day-name">' . $tage[$tag + 3] . '</div>
                        <img src="/images/' . $forcastd3->getWeatherIcon() . '.png" alt="weathericon">
                   <div class="top-right1"><img id="description icon" src="/images/windicon.png" height="30">' . $forcastd3->getWindSpeed() . ' km/h</div>
                   <div class="top-right2"><img id="description icon" src="/images/minmax.png" height="20">' . $forcastd3->getMainMinTemp() . ' / ' . $forcastd3->getMainMaxTemp() . '</div>
                   <div class="top-right3"><img id="description icon" src="/images/sunset.png" height="20">' . $forcastd3->getSunrise() . ' / ' . $forcastd3->getSunset() . '</p></div>
                </div>
                <div class="col-sm-2" id="weathercontainer" >
                  <div class="day-name">' . $tage[$tag + 4] . '</div>
                        <img src="/images/' . $forcastd4->getWeatherIcon() . '.png" alt="weathericon">
                   <div class="top-right1"><img id="description icon" src="/images/windicon.png" height="30">' . $forcastd4->getWindSpeed() . ' km/h</div>
                   <div class="top-right2"><img id="description icon" src="/images/minmax.png" height="20">' . $forcastd4->getMainMinTemp() . ' / ' . $forcastd4->getMainMaxTemp() . '</div>
                   <div class="top-right3"><img id="description icon" src="/images/sunset.png" height="20">' . $forcastd4->getSunrise() . ' / ' . $forcastd4->getSunset() . '</p></div>
                </div>
                <div class="col-sm-2" id="weathercontainer" >
                <div class="day-name">' . $tage[$tag + 5] . '</div>
                    <img src="/images/' . $forcastd5->getWeatherIcon() . '.png" alt="weathericon">
                    
                   <div class="top-right1"><img id="description icon" src="/images/windicon.png" height="30">' . $forcastd5->getWindSpeed() . ' km/h</div>
                   <div class="top-right2"><img id="description icon" src="/images/minmax.png" height="20">' . $forcastd5->getMainMinTemp() . ' / ' . $forcastd5->getMainMaxTemp() . '</div>
                   <div class="top-right3"><img id="description icon" src="/images/sunset.png" height="20">' . $forcastd5->getSunrise() . ' / ' . $forcastd5->getSunset() . '</p></div>
           
                </div>
            </div>

        </div>';
        ?>


        <script type="text/javascript">//Graifk


            var mintempd1 = parseInt(<?php echo $mintemp[0]; ?>);
            var mintempd2 = parseInt(<?php echo $mintemp[1]; ?>);
            var mintempd3 = parseInt(<?php echo $mintemp[2]; ?>);
            var mintempd4 = parseInt(<?php echo $mintemp[3]; ?>);
            var mintempd5 = parseInt(<?php echo $mintemp[4]; ?>);


            var maxtempd1 = parseInt(<?php echo $maxtemp[0]; ?>);
            var maxtempd2 = parseInt(<?php echo $maxtemp[1]; ?>);
            var maxtempd3 = parseInt(<?php echo $maxtemp[2]; ?>);
            var maxtempd4 = parseInt(<?php echo $maxtemp[3]; ?>);
            var maxtempd5 = parseInt(<?php echo $maxtemp[4]; ?>);

            Highcharts.chart('container', {

                title: {
                    text: 'Wettervorhersagee über 5 Tage'
                },

                yAxis: {
                    title: {
                        text: 'Grad Celsius'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                xAxis: {
                    categories: ['<?php echo $tage[$tag + 1]; ?>', '<?php echo $tage[$tag + 2]; ?>', '<?php echo $tage[$tag + 3]; ?>', '<?php echo $tage[$tag + 4]; ?>', '<?php echo $tage[$tag + 5]; ?>']
                },

                series: [{
                        name: 'MinTemp',
                        //data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]
                        data: [mintempd1, maxtempd2, mintempd3, mintempd4, mintempd5]
                    }, {
                        name: 'MaxTemp',
                        data: [maxtempd1, maxtempd2, maxtempd3, maxtempd4, maxtempd5]
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
        </script>
        <script>//Schliesskreutz von Container
            //Wird gebraucht für den Schliessen Button
            $("#closecross").click(function (e) {
                $(this).closest(".bottomStuff")
                        .toggleClass("active");

            });


        </script>



    </body>
</html>



