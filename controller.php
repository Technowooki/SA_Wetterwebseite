<?php

include("phpclass wetaherinfo.php");
include ("phpclass weatherforcast.php");
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");
include("templates/header.inc.php");
?>


<?php
$user = check_user();
$unit = setUnit();
$idifexists;


//Sucht in der Datenbank ob der Ort bereits eingetragen ist
$statement = $pdo->prepare("Select id, username, datetime FROM favorits WHERE cityname='" . $_POST['address'] . "'");
$result = $statement->execute();
$row = $statement->fetch();
if ($row['id'] == Null) {
    $unit = setUnit();
    $WeatherObject = new weatherinfo($_POST["Coordlat"], $_POST["Coordlon"], $unit);

//Definition der Varabeln
    $cityname = $_POST['address'];
    $weather = $WeatherObject->getWeatherDescription();
    $temp = $WeatherObject->getMainTemperatur();
    $maxtemp = $WeatherObject->getMainMaxTemp();
    $mintemp = $WeatherObject->getMainMinTemp();
    $pressure = $WeatherObject->getMainPressure();
    $humidity = $WeatherObject->getMainHumidity();
    $windspeed = $WeatherObject->getWindSpeed();
    $coordlon = $WeatherObject->getCoordLon();
    $country = $WeatherObject->getCountry();
    $icon = $WeatherObject->getWeatherIcon();
    $username = $user['vorname'];
    $datetime = time();
    $sunrise = $WeatherObject->getSunrise();
    $sunset = $WeatherObject->getSunset();
    $coordlat = $_POST["Coordlat"];
    $coordlon = $_POST["Coordlon"];

//Datenbank wird beschrieben
    $statement = $pdo->prepare("INSERT INTO favorits (cityname, weathermain, temperatur, mintemp, maxtemp, pressure, humidity, windspeed, icon, username, country, sunrise, sunset, coordlat, coordlon, unit) "
            . "VALUES (:cityname, :weather, :temp, :mintemp, :maxtemp, :pressure, :humidity, :windspeed, :icon, :username, :country, :sunrise, :sunset, :coordlat, :coordlon, :unit)");

    $result = $statement->execute(array(':cityname' => $cityname, ':weather' => $weather, ':temp' => $temp,
        ':mintemp' => $mintemp, ':maxtemp' => $maxtemp, ':pressure' => $pressure, ':humidity' => $humidity,
        ':windspeed' => $windspeed, ':icon' => $icon, ':username' => $username, ':country' => $country,
        ':sunrise' => $sunrise, ':sunset' => $sunset, ':coordlat' => $coordlat, ':coordlon' => $coordlon, ':unit' => $unit));

    if ($result) {
        echo 'Eintrag erfolgreich hinzugefügt. Sie werden automatisch weitergeleitet <a href="index.php">Zum Login</a>';
        header('Refresh: 2; URL=mapscreen.php');
        $showFormular = false;
    } else {
        echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
    }
} else {//Wenn der Eintrag schon vorhanden ist, wird geprüft ob der User schon einen Eintrag hat
    $unit = setUnit();
    //Wenn der Username bereits vorhanden ist bei der ortschaft
    if (strpos($row['username'], $user['vorname']) !== false) {
        if (checkDateTime($row['datetime']) == FALSE) {
            refreshData($id, $pdo);
        }
        echo "eintrag bereits vorhanden";
        header('Refresh: 2; URL=mapscreen.php');
    } else {//Wenn der username noch nicht vorhanden ist
        if (checkDateTime($row['datetime']) == FALSE) {
           refreshData($id, $pdo);
        }else{
        $trimmed = $row['username'] . $user['vorname'];
        $sql = "UPDATE favorits SET username = '" . $trimmed . "'  WHERE id='" . $row['id'] . "'";
        $statement = $pdo->prepare($sql);
        $result = $statement->execute();
        echo "eintrag hinzugefügt";
        }
        header('Refresh: 2; URL=mapscreen.php');
    }
}

function setUnit() {
    if (isset($_POST["metricswitch"]) == 'Yes') {
        return 'imperial';
    } else {
        return 'metric';
    }
}

//Setzt die Einheit auf °F oder °C

function refreshData($id, $pdo) {
//Aus db die Kordinaten, Einheit und Usernamen lesen
$statement = $pdo->prepare("Select coordlat, coordlon, unit FROM favorits WHERE id='" . $id . "'");
$result = $statement->execute();
$row = $statement->fetch();

//Neues Wetter Objekt erstellen
$WeatherObject = new weatherinfo($row["coordlat"], $row["coordlon"], $row["unit"]);

 $weather = $WeatherObject->getWeatherDescription();
    $temp = $WeatherObject->getMainTemperatur();
    $maxtemp = $WeatherObject->getMainMaxTemp();
    $mintemp = $WeatherObject->getMainMinTemp();
    $pressure = $WeatherObject->getMainPressure();
    $humidity = $WeatherObject->getMainHumidity();
    $windspeed = $WeatherObject->getWindSpeed();
    $icon = $WeatherObject->getWea1therIcon();
    $datetime = time();
    $sunrise = $WeatherObject->getSunrise();
    $sunset = $WeatherObject->getSunset();
    
    
//Daten in der Datenbank updaten
        $data = [
        'temperatur' => $temp,
        'mintemp' => $mintemp,
         'maxtemp' => $maxtemp,
        'pressure' => $pressure,
        'humidity' => $humidity,
          'windspeed' => $windspeed,
              'icon' => $icon,
              'sunrise' => $sunrise,
            'sunset' => $sunset,
            'datetime' => $datetime,
            'weathermain' => $weather,
            
    ];
    $sql = "UPDATE favorites SET temperatur=:temperatur, mintemp=:mintemp, maxtemp=:maxtemp, pressure=:pressure, humidity=:humidity, windspeed=:windspeed, icon=:icon, sunrise=:sunrise,"
            . " sunset=:sunset, datetime=:datetime, weathermain=:weathermain WHERE id='.$id.'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($sql);
    
    
}

function checkDateTime($pRowDate) {
    $timeCompare = 3;
    $nowtime = date("Y-m-d H:i:s");
    $date = date('Y-m-d H:i:s', strtotime($nowtime . " -$timeCompare hours"));

    if ($date > $pRowDate) {
        return TRUE;
    } else {
        return FALSE;
    }
}
