<?php
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

//Überprüfe, dass der User eingeloggt ist
//Der Aufruf von check_user() muss in alle internen Seiten eingebaut sein
$user = check_user();

include("templates/header.inc.php");
?>

<style>

    table#weather-table th, td{
  
    }
    
    p {
   font-weight: bold;
   font-size: 16px;
}
    .box {
        width: 300px;
        height: 160px;
        background-color: #0C5D8A;
/*        background-color: transparent;*/
        color: #fff;
        margin: 3em auto;
        border: 3px;
        border-color: #000000;
        padding-left: 10px;
        padding-top: 5px;
    }

    .rounded {
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
    }


</style>




<div class="container main-container">

    <h1>Herzlich Willkommen!</h1>

    Hallo <?php echo htmlentities($user['vorname']); ?>,<br>
    Herzlich Willkommen im internen Bereich!<br><br>


    <?php
    $statement = $pdo->prepare("SELECT * FROM favorits ORDER BY id");
    $result = $statement->execute();
    $count = 1;
    while ($row = $statement->fetch()) {
        echo'<div class="rounded box" onclick="test()">';
        echo' <table id="weather-table">';
        echo '<tr>';
        echo '<td colspan="2"><p>' . $row['weathermain'] . '</p></td>';
        echo'   </tr>';
        echo' <tr>';
        echo' <td rowspan="2"><img id="weather-icon" src=/images/' . $row['icon'] . '.png></td>';
        echo' </tr>';
        echo' <tr>';
        echo' <td colspan="2">';
        echo'  <table id="weather-table-details">';
        echo '<tr>';
        echo '<td colspan="2"><p>' . $row['cityname'] . '</p></td>';
        echo '  </tr>';
        echo ' <tr>';
        echo ' <td>Temperatur:</td>';
        echo '<td style="padding-left: 7px">' . $row['temperatur'] . 'C°</td>';
        echo ' </tr>';
        echo '    <tr>';
        echo ' <td>Luftfechtigkeit:</td>';
        echo ' <td style="padding-left: 7px">' . $row['humidity'] . '%</td>';
        echo ' </tr>';
        echo ' <tr>';
        echo '  <td>Luftdruck:</td>';
        echo '   <td style="padding-left: 7px"> ' . $row['pressure'] . 'MPa</td>';
        echo ' </tr>';
        echo ' </table>';
        echo ' </td>';
        echo '  </tr>';
        echo '  </table>';
        echo '  </div>';
    }
    ?>

</div>



<?php
include("templates/footer.inc.php")
?>
