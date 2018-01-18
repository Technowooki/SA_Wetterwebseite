
<!DOCTYPE html>
<html>
    <head>
       	<script src="/lib/js/jquery.min.js"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <style>

            #weathercontainer{

                align-content: center;
                align-items: center;
            }
            #graph{

            }
        </style>


    </head>
    <body>
        <?php
        session_start();
        require_once("inc/config.inc.php");
        require_once("inc/functions.inc.php");
        include("phpclass wetaherinfo.php");
        include ("phpclass weatherforcast.php");
        include("lib/inc/chartphp_dist.php");
        ?>


        <?php
        setlocale(LC_TIME, "de_DE.utf8");
        $user = check_user();

        if ($_POST['metricswitch'] == 'flase') {
            $unit = 'metric';
        } else {
            $unit = 'imperial';
        }


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
        $line_chart_data = array(
            array(
                array($tage[$tag + 1], $forcastd1->getMainMinTemp()),
                array($tage[$tag + 2], $forcastd2->getMainMinTemp()),
                array($tage[$tag + 3], $forcastd3->getMainMinTemp()),
                array($tage[$tag + 4], $forcastd4->getMainMinTemp()),
                array($tage[$tag + 5], $forcastd5->getMainMinTemp())),
            array(
                array($tage[$tag + 1], $forcastd1->getMainMaxTemp()),
                array($tage[$tag + 2], $forcastd2->getMainMaxTemp()),
                array($tage[$tag + 3], $forcastd3->getMainMaxTemp()),
                array($tage[$tag + 4], $forcastd4->getMainMaxTemp()),
                array($tage[$tag + 5], $forcastd5->getMainMaxTemp())),
        );


        $p = new chartphp();

// data array is populated from example data file
        $p->data = $line_chart_data;
        $p->chart_type = "line";


// Common Options
        $p->title = "Vorhersage";
        $p->xlabel = "Tage";
        $p->ylabel = "Temperatur in °C";
        $p->series_label = array("MinTemp", "Maxtemp");
        $p->width = "100%";
        $p->height = "200px";
        $p->color = "soft"; // Choices are "metro" "soft"
        $out = $p->render('c1');


//Ausgabe die zurückgegeben wird 


        echo'  <div class="container-fluid">
            <div id="closecross">  <img src="/images/cross.png" alt="closecross" height="50"></div>
            
            <div class="row" >
                <div class="col-sm-6 col-sm-offset-3" id="graph">
                   ' . $out . ' 
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






        <script>
            //Wird gebraucht für den Schliessen Button
            $("#closecross").click(function (e) {
                $(this).closest(".bottomStuff")
                        .toggleClass("active");

            });
        </script>



    </body>
</html>



