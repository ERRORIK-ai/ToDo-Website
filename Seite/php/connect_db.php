<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
</head>

<body>

  <?php

  //Die Festlegung der allgemeinen variablen
  $host     = 'localhost';       // host PORT MUSS folgender sein: 3306
  $username = 'root';
  $password = '';
  $database = 'todo_db';   // database

  $ausgabe = "";
  $tabelle_db = "";


  //Session wird gestartet
  session_start();
  session_regenerate_id();
  $id_user = $_SESSION["id_user"];
  $admin = $_SESSION["admin"];
  $firstname = $_SESSION['firstname'];
  $lastname = $_SESSION['lastname'];
  $pass = $_SESSION['pass'];
  $user = $_SESSION['user'];


  //Start der Funktion welche sich mit der DB verbindet
  $ausgabe .= start($firstname, $lastname, $pass, $user, $host, $username, $password, $database, $ausgabe);



  //------------------------------------------------------PHP FUNKTIONEN------------------------------------------------------
  function start($firstname, $lastname, $pass, $user, $host, $username, $password, $database, $ausgabe)
  {

    $ausgabe .=  " Host:" . $host . " user:" . $username . " pass:" . $password . " database:" . $database;
    // mit Datenbank verbinden
    $conn = new mysqli($host, $username, $password, $database);

    // fehlermeldung, falls die Verbindung fehl schlägt.
    if ($conn->connect_error) {
      die('Verbindungsfehler (' . $conn->connect_error . ') ' . $conn->connect_error);
    }
    $ausgabe .= "Verbindung erfolgreich";
    return $ausgabe;
  }


  ?>

</body>

</html>