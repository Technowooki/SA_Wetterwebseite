<?php

require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

//Überprüfe, dass der User eingeloggt ist
//Der Aufruf von check_user() muss in alle internen Seiten eingebaut sein
$user = check_user();
$statement = $pdo->prepare("SELECT * FROM favorits ORDER BY homebase DESC");
$unit;

$result = $statement->execute();
$count = 1;
while ($row = $statement->fetch()) {
    if ($row['unit']=='metric'){
        $unit="°C";
    } else {
        $unit="°F";
    }
    if (strpos($row['username'], $user['vorname']) !== false) {
        echo '<div class="rounded box"  >
          <table id="weather-table">
          <tr>
          <td colspan="2"><p>' . $row['weathermain'] . '</p></td> 
             <td colspan="1"><p>' . $row['datetime'] . '</p></td> 
            </tr>
            
          <tr>
          
         <td rowspan="2" width="20px" class="buttonclick" ><img id=' . $row['id'] . ' src=/images/remove.png height="25" width="25" onclick="remove(this)">
        <img id=' . $row['id'] . ' src=/images/home.png height="25" width="25" onclick="home(this)"></td> 
          <td rowspan="2"><img id="' . $row['id'] . '" src="/images/' . $row['icon'] . '.png" height="120" width="120" onclick="details(this)"> 
          </tr> 
          <tr> 
          <td colspan="2"> 
           <table id="' . $row['id'] . '" onclick="details(this)"> 
         <tr> 
         <td colspan="2"><p>' . $row['cityname'] . '</p></td> 
           </tr> 
          <tr> 
          <td>Temperatur:</td> 
         <td style="padding-left: 7px">' . $row['temperatur'] . ''.$unit.'</td> 
          </tr> 
             <tr> 
          <td>Luftfechtigkeit:</td> 
          <td style="padding-left: 7px">' . $row['humidity'] . ' %</td> 
          </tr> 
          <tr> 
           <td>Luftdruck:</td> 
            <td style="padding-left: 7px"> ' . $row['pressure'] . ' hPa</td> 
          </tr> 
          </table> 
          </td> 
           </tr> 
           </table> 
           </div> ';
    }
}