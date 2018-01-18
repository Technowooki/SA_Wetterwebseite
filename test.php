<?php

require_once("inc/config.inc.php");
?>


<?php

//ID der Datenbank wird via Post übergeben
if ($_POST['action'] == 'remove') {//wenn der Remove Knopf gedrückt wird
    $statement = $pdo->prepare("SELECT id, username FROM favorits WHERE id='" . $_POST['id'] . "'");
    $result = $statement->execute();
    $row = $statement->fetch();

    if ($_POST['username'] == $row['username']) {//wenn der einzige user, dann wird der eintrag aus der db geläscht
        $statement = $pdo->prepare("DELETE FROM favorits WHERE id='" . $_POST['id'] . "'");
        $result = $statement->execute();
        echo "stimmt";
    } else {//Username wird entfernt von der Datenbank
        //Username Feld wird aus DB gelesen und eingelogter user wird in String entfernt
        $subject = $row['username'];
        $search = $_POST['username'];
        $trimmed = str_replace($search, '', $subject);

//Eingeloggter user wird aus DB von id entfernt
        $sql = "UPDATE favorits SET username = '" . $trimmed . "'  WHERE id='" . $_POST['id'] . "'";
        $statement = $pdo->prepare($sql);
        $result = $statement->execute();
        echo "falsch";
    }//End else
}//end if

if ($_POST['action'] == 'homebase') {//wenn der Remove Knopf gedrückt wird
    $statement = $pdo->prepare("SELECT id, homebase, username FROM favorits");
    $result = $statement->execute();

    while ($row = $statement->fetch()) {//prüfen ob homebase bereits gesetzt, wenn ja wird der Wert gelöscht
        if (strpos($row['homebase'], $_POST['username']) !== false) {
            echo "bereits gesetzt";
            $subject = $row['homebase'];
            $search = $_POST['username'];
            $trimmed = str_replace($search, '', $subject);

//hombase wird aus DB von id entfernt
            $sql = "UPDATE favorits SET homebase = '" . $trimmed . "'  WHERE id='" . $row['id'] . "'";
            $statement = $pdo->prepare($sql);
            $result = $statement->execute();
        } 
    }//end while
    echo "nicht gesetzt";
            //Neue Homebase wird gesetzt
            $sql = "UPDATE favorits SET homebase = '" . $_POST['username'] . "'  WHERE id='" . $_POST['id'] . "'";
            $statement = $pdo->prepare($sql);
            $result = $statement->execute();
        
}//end if