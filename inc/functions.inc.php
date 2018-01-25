<?php
/**
 * A complete login script with registration and members area.
 *
 * @author: Nils Reimers / http://www.php-einfach.de/experte/php-codebeispiele/loginscript/
 * @license: GNU GPLv3
 */
include_once("password.inc.php");
include("phpclass wetaherinfo.php");
include ("phpclass weatherforcast.php");

/**
 * Checks that the user is logged in. 
 * @return Returns the row of the logged in user
 */
function check_user() {
	global $pdo;
	
	if(!isset($_SESSION['userid']) && isset($_COOKIE['identifier']) && isset($_COOKIE['securitytoken'])) {
		$identifier = $_COOKIE['identifier'];
		$securitytoken = $_COOKIE['securitytoken'];
		
		$statement = $pdo->prepare("SELECT * FROM securitytokens WHERE identifier = ?");
		$result = $statement->execute(array($identifier));
		$securitytoken_row = $statement->fetch();
	
		if(sha1($securitytoken) !== $securitytoken_row['securitytoken']) {
			//Vermutlich wurde der Security Token gestohlen
			//Hier ggf. eine Warnung o.ä. anzeigen
			
		} else { //Token war korrekt
			//Setze neuen Token
			$neuer_securitytoken = random_string();
			$insert = $pdo->prepare("UPDATE securitytokens SET securitytoken = :securitytoken WHERE identifier = :identifier");
			$insert->execute(array('securitytoken' => sha1($neuer_securitytoken), 'identifier' => $identifier));
			setcookie("identifier",$identifier,time()+(3600*24*365)); //1 Jahr Gültigkeit
			setcookie("securitytoken",$neuer_securitytoken,time()+(3600*24*365)); //1 Jahr Gültigkeit
	
			//Logge den Benutzer ein
			$_SESSION['userid'] = $securitytoken_row['user_id'];
		}
	}
	
	
	if(!isset($_SESSION['userid'])) {
		die('Bitte zuerst einloggen');
	}
	

	$statement = $pdo->prepare("SELECT * FROM users WHERE id = :id");
	$result = $statement->execute(array('id' => $_SESSION['userid']));
	$user = $statement->fetch();
	return $user;
}

/**
 * Returns true when the user is checked in, else false
 */
function is_checked_in() {
	return isset($_SESSION['userid']);
}
 
/**
 * Returns a random string
 */
function random_string() {
	if(function_exists('openssl_random_pseudo_bytes')) {
		$bytes = openssl_random_pseudo_bytes(16);
		$str = bin2hex($bytes); 
	} else if(function_exists('mcrypt_create_iv')) {
		$bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
		$str = bin2hex($bytes); 
	} else {
		//Replace your_secret_string with a string of your choice (>12 characters)
		$str = md5(uniqid('your_secret_string', true));
	}	
	return $str;
}

/**
 * Returns the URL to the site without the script name1
 */
function getSiteURL() {
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	return $protocol.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/';
}

/**
 * Outputs an error message and stops the further exectution of the script.
 */
function error($error_msg) {
	include("templates/header.inc.php");
	include("templates/error.inc.php");
	include("templates/footer.inc.php");
	exit();
}


/**
 * Aktualisiert den die Wetterkarte die geklickt wird
 * @param type $id des zu prüfenden Datensatzes
 * @param type $pdo
 */
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
    $icon = $WeatherObject->getWeatherIcon();
    $datetime = date('Y-m-d H:i:s');
    $sunrise = $WeatherObject->getSunrise();
    $sunset = $WeatherObject->getSunset();

//Daten in der Datenbank updaten 
    $statement = $pdo->prepare("UPDATE favorits SET temperatur = :temperatur , datetime =:datum, icon=:icon,mintemp=:mintemp, maxtemp=:maxtemp, pressure=:pressure, humidity=:humidity, windspeed=:windspeed WHERE id=:id");
    $statement->execute(array('temperatur' => $temp, 'id' => $id, 'datum' => $datetime, 'icon' => $icon, 'mintemp' => $mintemp, 'maxtemp' => $maxtemp, 'pressure' => $pressure, 'humidity' => $humidity, 'windspeed' => $windspeed));


    if ($statement) {
        echo 'Eintrag erfolgreich hinzugefügt. Sie werden automatisch weitergeleitet <a href="index.php">Zum Login</a>';
        $showFormular = false;
    } else {
        echo "$result Beim Abspeichern ist leider ein Fehler aufgetreten<br>'";
        echo "SQL Error <br />";
        echo $statement->queryString . "<br />";
        echo $statement->errorInfo()[2];
    }
}

/**
 * Überprüft ob das Datum älter als die gesetzte Variabel
 * @param type $pDate Datum zum überprüfen
 * @return boolean
 */
function checkDateTime($pDate) {
    $timeCompare = 3;
    $nowtime = date("Y-m-d H:i:s");
    $date = date('Y-m-d H:i:s', strtotime($nowtime . " -$timeCompare hours"));

    if ($date > $pDate) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function setUnit() {
    if ($_POST["metricswitch"] == 'true') {
        return 'imperial';
    } else {
        return 'metric';
    }
}