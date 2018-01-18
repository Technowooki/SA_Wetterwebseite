<!DOCTYPE html>
<html lang="en">

    <head>
        <title>WeatherApp</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
        <style>
            
            #weathercontainer{
                background-color: greenyellow;
                align-content: center;
                align-items: center;
            }
            #graph{
                background-color: lightcoral;
            }
            
            </style>
    </head>

    <body>

        <div class="container-fluid">
            <div class="row" >
                <div class="col-sm-6 col-sm-offset-3" id="graph">
                   fgsdgs
                </div>
            </div>
            <div class="row" >
                <div class="col-sm-2 col-sm-offset-1" id="weathercontainer">
                       <img src="/images/' . $forcastd1->getWeatherIcon() . '.png" alt="weathericon">
                    <div class="day-name">' . $tage[$tag + 1] . '</div>
                   <div class="top-right1"><img id="description icon" src="/images/windicon.png" height="30">' . $forcastd1->getWindSpeed() . ' km/h</div>
                   <div class="top-right2"><img id="description icon" src="/images/minmax.png" height="20">' . $forcastd1->getMainMinTemp() . ' / ' . $forcastd1->getMainMaxTemp() . '</div>
                   <div class="top-right3"><img id="description icon" src="/images/sunset.png" height="20">fehlt noch</p></div>
          
                </div>
                <div class="col-sm-2" id="weathercontainer">
                    <img src="/images/' . $forcastd2->getWeatherIcon() . '.png" alt="weathericon">
                   <div class="day-name">' . $tage[$tag + 2] . '</div>
                   <div class="top-right1"><img id="description icon" src="/images/windicon.png" height="30">' . $forcastd2->getWindSpeed() . ' km/h</div>
                   <div class="top-right2"><img id="description icon" src="/images/minmax.png" height="20">' . $forcastd2->getMainMinTemp() . ' / ' . $forcastd2->getMainMaxTemp() . '</div>
                   <div class="top-right3"><img id="description icon" src="/images/sunset.png" height="20">fehlt noch</p></div>
                </div>
                <div class="col-sm-2" id="weathercontainer" >
                        <img src="/images/' . $forcastd3->getWeatherIcon() . '.png" alt="weathericon">
                   <div class="day-name">' . $tage[$tag + 3] . '</div>
                   <div class="top-right1"><img id="description icon" src="/images/windicon.png" height="30">' . $forcastd3->getWindSpeed() . ' km/h</div>
                   <div class="top-right2"><img id="description icon" src="/images/minmax.png" height="20">' . $forcastd3->getMainMinTemp() . ' / ' . $forcastd3->getMainMaxTemp() . '</div>
                   <div class="top-right3"><img id="description icon" src="/images/sunset.png" height="20">fehlt noch</p></div>
           
                </div>
                <div class="col-sm-2" id="weathercontainer" >
                        <img src="/images/' . $forcastd4->getWeatherIcon() . '.png" alt="weathericon">
                   <div class="day-name">' . $tage[$tag + 4] . '</div>
                   <div class="top-right1"><img id="description icon" src="/images/windicon.png" height="30">' . $forcastd4->getWindSpeed() . ' km/h</div>
                   <div class="top-right2"><img id="description icon" src="/images/minmax.png" height="20">' . $forcastd4->getMainMinTemp() . ' / ' . $forcastd4->getMainMaxTemp() . '</div>
                   <div class="top-right3"><img id="description icon" src="/images/sunset.png" height="20">fehlt noch</p></div>
           
                </div>
                <div class="col-sm-2" id="weathercontainer" >
                    <img src="/images/' . $forcastd5->getWeatherIcon() . '.png" alt="weathericon">
                    <div class="day-name">' . $tage[$tag + 5] . '</div>
                   <div class="top-right1"><img id="description icon" src="/images/windicon.png" height="30">' . $forcastd2->getWindSpeed() . ' km/h</div>
                   <div class="top-right2"><img id="description icon" src="/images/minmax.png" height="20">' . $forcastd2->getMainMinTemp() . ' / ' . $forcastd2->getMainMaxTemp() . '</div>
                   <div class="top-right3"><img id="description icon" src="/images/sunset.png" height="20">fehlt noch</p></div>
           
                </div>
            </div>

        </div>

    </body>

</html>
