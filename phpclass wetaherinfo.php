<?php

class weatherinfo {

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
    private $mUnit;
    private $mWeatherIcon;
    private $mSunset;
    private $mSunrise;


    function __construct($pLat, $pLon, $pUnit) {//Konstruktor der Klasse weatherinfo
        $this->mCoordLat = $pLat;
        $this->mCoordLon = $pLon;
        $this->mUnit = $pUnit;
        $this->mUrlcoordinates = "api.openweathermap.org/data/2.5/weather?lat=$this->mCoordLat&lon=$this->mCoordLon&units=$this->mUnit&lang=de&appid=$this->mApikey";
        $this->owmApiCall(); //Ruft die API Abfrage von OpenWeatherMap auf
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
        $obj = json_decode($result, true); //JSON Antwort von OpenWatherMap wird als Array gespeichert
        //
//Wertzuweisung der Varablen	
        $this->mCoordLon = $obj['coord']["lon"];
        $this->mCoordLat = $obj['coord']["lat"];
        $this->mWeatherMain = $obj['weather'][0]["main"];
        $this->mWeatherDescription = $obj['weather'][0]["description"];
        $this->mWeatherIcon = $obj['weather'][0]["icon"];
        $this->mMainTemperatur = $obj['main']["temp"];
        $this->mMainMaxTemp = $obj['main']["temp_max"];
        $this->mMainMinTemp = $obj['main']["temp_min"];
        $this->mMainPressure = $obj['main']["pressure"];
        $this->mMainHumidity = $obj['main']["humidity"];
        $this->mWindSpeed = $obj['wind']["speed"];
        $this->mWindDeg = $obj['wind']["deg"];
        $this->mCloudsAll = $obj['clouds']["all"];
        $this->mCountry = $obj['sys']["country"];
        $this->mSunrise = $obj['sys']["sunrise"];
        $this->mSunset = $obj['sys']["sunset"];
        $this->mCity = $obj['name'];
    }

//End API Abfrage
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

    public function getWeatherIcon() {
        return $this->mWeatherIcon;
    }

    public function getMainTemperatur() {
        return $this->mMainTemperatur;
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

    public function getSunrise() {
        return $this->mSunrise;
    }

    public function getSunset() {
        return $this->mSunset;
    }
}//End Class weatherinfo

