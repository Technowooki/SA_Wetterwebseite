<?php

require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");
?>

<?php

/**
 * Diese Datei ist das Ziel der Ajax funktionen
 */
if ($_POST['action'] == 'remove') {//wenn der Remove Knopf gedrückt wird
    $statement = $pdo->prepare("SELECT id, username FROM favorits WHERE id='" . $_POST['id'] . "'");
    $result = $statement->execute();
    $row = $statement->fetch();

    if ($_POST['username'] == $row['username']) {//wenn der einzige user, dann wird der eintrag aus der db geläscht
        $statement = $pdo->prepare("DELETE FROM favorits WHERE id='" . $_POST['id'] . "'");
        $result = $statement->execute();
    } else {//Username wird entfernt von der Datenbank
        //Username Feld wird aus DB gelesen und eingelogter user wird in String entfernt
        $subject = $row['username'];
        $search = $_POST['username'];
        $trimmed = str_replace($search, '', $subject);
//Eingeloggter user wird aus DB von id entfernt
        $sql = "UPDATE favorits SET username = '" . $trimmed . "'  WHERE id='" . $_POST['id'] . "'";
        $statement = $pdo->prepare($sql);
        $result = $statement->execute();
    }//End else
}//end if

if ($_POST['action'] == 'homebase') {//wenn der Remove Knopf gedrückt wird
    $statement = $pdo->prepare("SELECT id, homebase, username FROM favorits");
    $result = $statement->execute();

    while ($row = $statement->fetch()) {//prüfen ob homebase bereits gesetzt, wenn ja wird der Wert gelöscht
        if (strpos($row['homebase'], $_POST['username']) !== false) {
            $subject = $row['homebase'];
            $search = $_POST['username'];
            $trimmed = str_replace($search, '', $subject);

//hombase wird aus DB von id entfernt
            $sql = "UPDATE favorits SET homebase = '" . $trimmed . "'  WHERE id='" . $row['id'] . "'";
            $statement = $pdo->prepare($sql);
            $result = $statement->execute();
        }
    }//end while
    //Neue Homebase wird gesetzt
    $sql = "UPDATE favorits SET homebase = '" . $_POST['username'] . "'  WHERE id='" . $_POST['id'] . "'";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute();
}//end if

if ($_POST['action'] == 'refresh') {//wenn der Refresh Knopf gedrückt wird
    refreshData($_POST['id'], $pdo);
    echo "Eintrag wurde erfolgreich aktualisiert";
}//end if

if ($_POST['action'] == 'addtofavorits') {//wenn der Remove Knopf gedrückt wird
    echo $_POST["metricswitch"];
    $user = check_user();
    $unit = setUnit();
    //Sucht in der Datenbank ob der Ort bereits eingetragen ist
    $statement = $pdo->prepare("Select id, username, datetime FROM favorits WHERE cityname='" . $_POST['address'] . "'");
    $result = $statement->execute();
    $row = $statement->fetch();
    if ($row['id'] == Null) {
        $unit = setUnit();
        $WeatherObject = new weatherinfo($_POST["coordlat"], $_POST["coordlon"], $unit);

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
        $username = $user['email'];
        $datetime = time();
        $sunrise = $WeatherObject->getSunrise();
        $sunset = $WeatherObject->getSunset();
        $coordlat = $_POST["coordlat"];
        $coordlon = $_POST["coordlon"];

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

        if (strpos($row['username'], $user['email']) !== false) {//Wenn der Username bereits vorhanden ist bei der ortschaft
            if (checkDateTime($row['datetime']) == TRUE) {//Prüft das alter des Eintrags und aktualisiert ihn
                refreshData($row['id'], $pdo); //Die Daten werden aktualisiert
            }
            header('Refresh: 2; URL=mapscreen.php');
        } else {//Wenn der username noch nicht vorhanden ist, die Stadt aber schon
            if (checkDateTime($row['datetime']) == TRUE) {
                refreshData($row['id'], $pdo);
            } else {
                $trimmed = $row['username'] . $user['email'];
                $sql = "UPDATE favorits SET username = '" . $trimmed . "'  WHERE id='" . $row['id'] . "'";
                $statement = $pdo->prepare($sql);
                $result = $statement->execute();
                echo "eintrag hinzugefügt";
            }
            header('Refresh: 2; URL=mapscreen.php');
        }
    }
}//end if