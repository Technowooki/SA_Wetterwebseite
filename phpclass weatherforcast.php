<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of phpclass watherforcast
 *
 * @author steve
 */
class weatherforcast {

//Deklaration der Membervariabeln
    private $mCoordLon;
    private $mCoordLat;
    private $mWeatherMain;
    private $mWeatherDescription;
    private $mMainTemperatur;
    private $mMainMaxTemp;
    private $mMainMinTemp;
    private $mMainPressure;
    private $mMainHumidity;
    private $mWindSpeed;
    private $mWindDeg;
    private $mCloudsAll;
    private $mCity;
    private $mCountry;
    private $mApikey = "851bcd046aec31c20df231806ca60676";
    private $mUrlcoordinates;
    private $mDay;
    private $mOwmResult;
    private $mSunriseResult;
    private $mUnit;
    private $mIcon;
    private $mSunriseApiUrl;
    private $mDate;
    private $mSunrise;
    private $mSunset;

    function __construct($pLat, $pLon, $pUnit, $pDate) {//Konstruktor der Klasse weatherinfo
        $this->mCoordLat = $pLat;
        $this->mCoordLon = $pLon;
        $this->mUnit = $pUnit;
        $this->mDate = $pDate;
        $this->mUrlcoordinates = "api.openweathermap.org/data/2.5/forecast?lat=$this->mCoordLat&lon=$this->mCoordLon&units=$this->mUnit&lang=de&appid=$this->mApikey";
        $this->mSunriseApiUrl = "https://api.sunrise-sunset.org/json?lat=$this->mCoordLat&lng=$this->mCoordLon&date=$this->mDate";
        $this->owmApiCall(); //Ruft die API Abfrage von OpenWeatherMap auf
        $this->sunriseApiCall();
    }

//End Constructor
//API Abfrage
    private function owmApiCall() {
        //curl wird ausgeführt	
        $ch = curl_init(); //  curl wird initalisiert
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL verifizierung wird deaktiviert
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // wenn falsch wird es ausgegeben
        curl_setopt($ch, CURLOPT_URL, $this->mUrlcoordinates); // // Url wird gesetzt
        $result = curl_exec($ch); // curl wird ausgeführt
        curl_close($ch); //curl wird geschlossen
        $this->mOwmResult = json_decode($result, true); //JSON Antwort von OpenWatherMap wird als Array gespeichert
    }

     private function sunriseApiCall() {
        //curl wird ausgeführt	
        $ch = curl_init(); //  curl wird initalisiert
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL verifizierung wird deaktiviert
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // wenn falsch wird es ausgegeben
        curl_setopt($ch, CURLOPT_URL, $this->mSunriseApiUrl); // // Url wird gesetzt
        $result = curl_exec($ch); // curl wird ausgeführt
        curl_close($ch); //curl wird geschlossen
        $this->mSunriseResult = json_decode($result, true); //JSON Antwort von OpenWatherMap wird als Array gespeichert
    }
    
    
    
//Wertzuweisung der Varablen D0
    private function value($pDay) {
        $this->mWeatherMain = $this->mOwmResult['list'][$pDay]['weather'][0]["main"];
        $this->mWeatherDescription = $this->mOwmResult['list'][$pDay]['weather'][0]["description"];
        $this->mMainTemperatur = $this->mOwmResult['list'][$pDay]['main']["temp"];
        $this->mMainMaxTemp = $this->mOwmResult['list'][$pDay]['main']["temp_max"];
        $this->mMainMinTemp = $this->mOwmResult['list'][$pDay]['main']["temp_min"];
        $this->mMainPressure = $this->mOwmResult['list'][$pDay]['main']["pressure"];
        $this->mMainHumidity = $this->mOwmResult['list'][$pDay]['main']["humidity"];
        $this->mWindSpeed = $this->mOwmResult['list'][$pDay]['wind']["speed"];
        $this->mWindDeg = $this->mOwmResult['list'][$pDay]['wind']["deg"];
        $this->mCloudsAll = $this->mOwmResult['list'][$pDay]['clouds']["all"];
        $this->mCountry = $this->mOwmResult['city']['country'];
        $this->mCity = $this->mOwmResult['city']['name'];
        $this->mIcon = $this->mOwmResult['list'][$pDay]['weather'][0]["icon"];
        $this->mSunrise=$this->mSunriseResult['results']['sunrise'];
        $this->mSunset=$this->mSunriseResult['results']['sunset'] ;
        
    }

//End API Abfrage

    public function getForcastD1() {
        $date = date('Y-m-d', strtotime('+1 days'));
        $forcast = new weatherforcast($this->mCoordLat, $this->mCoordLon, $this->mUnit, $date );
        $forcast->value(0);
        
        return $forcast;
    }

    public function getForcastD2() {
        $date = date('Y-m-d', strtotime('+2 days'));
        $forcast = new weatherforcast($this->mCoordLat, $this->mCoordLon, $this->mUnit, $date);
        $forcast->value(8);
        return $forcast;
    }

    public function getForcastD3() {
        $date = date('Y-m-d', strtotime('+3 days'));
        $forcast = new weatherforcast($this->mCoordLat, $this->mCoordLon, $this->mUnit, $date);
        $forcast->value(16);
        return $forcast;
    }

    public function getForcastD4() {
        $date = date('Y-m-d', strtotime('+4 days'));
        $forcast = new weatherforcast($this->mCoordLat, $this->mCoordLon, $this->mUnit,$date);
        $forcast->value(24);
        return $forcast;
    }

    public function getForcastD5() {
        $date = date('Y-m-d', strtotime('+5 days'));
        $forcast = new weatherforcast($this->mCoordLat, $this->mCoordLon, $this->mUnit,$date);
        $forcast->value(32);
        return $forcast;
    }

//Getter    
    public function getCoordLon() {
        return $this->mCoordLon;
    }

    public function getCoordLat() {
        return $this->mCoordLat;
    }

    public function getWeatherMain() {
        return $this->mWeatherMain;
    }

    public function getWeatherDescription() {
        return $this->mWeatherDescription;
    }

    public function getMainTemperatur() {
        return $this->mMainTemperatur;
    }

    public function getWeatherIcon() {
        return $this->mIcon;
    }

    public function getMainMaxTemp() {
        return $this->mMainMaxTemp;
    }

    public function getMainMinTemp() {
        return $this->mMainMinTemp;
    }

    public function getMainPressure() {
        return $this->mMainPressure;
    }

    public function getMainHumidity() {
        return $this->mMainHumidity;
    }

    public function getWindSpeed() {
        return $this->mWindSpeed;
    }

    public function getWindDeg() {
        return $this->mWindDeg;
    }

    public function getCloudsAll() {
        return $this->mCloudsAll;
    }

    public function getCity() {
        return $this->mCity;
    }

    public function getUrl() {
        return $this->mUrlcoordinates;
    }

    public function getCountry() {
        return $this->mCountry;
    }
public function getSunrise(){
    return $this->mSunrise;
}

public function getSunset(){
    return $this->mSunset;
}
}

//End Class weatherforcast
