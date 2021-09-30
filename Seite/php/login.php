<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LogIn</title>
</head>

<body>
  <?php

  //Variablen für die verbindung zu DB
  $host     = 'localhost';       // host PORT MUSS folgender sein: 3306
  $username = 'root';
  $password = '';
  $database = 'todo_db';   // database

  $ausgabe = "";
  $tabelle_db = "";

  //Falls der Submit Knopf gedrückt wurde werden die eingegebenen Daten zur Funktion anmelden weitergeleitet
  if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $pass = $_POST['password'];
    $user = $_POST['username'];

    $ausgabe .= start($pass, $user, $host, $username, $password, $database, $ausgabe);
    if (isset($_POST['username']) && isset($_POST['password'])) {
      if (isset($_POST['btnAnmelden'])) {
        $ausgabe .= anmelden($pass, $user, $host, $username, $password, $database, $ausgabe);
      }
    }
  }

  ?>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">



  <!------------------------------------------Log In------------------------------------------>
  <div class="container">
    <div class="row centered-form">
      <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Please log in to use this service <small>Staff only</small></h3>
          </div>
          <div class="panel-body">
            <form role="form" action="" method="post">
              <div class="form-group">
                <input type="text" name="username" id="username" class="form-control input-sm" placeholder="Enter Username">
              </div>
              <div class="form-group">
                <input type="password" name="password" id="password" class="form-control input-sm" placeholder="Enter Password">
              </div>
              <button type="submit" name="btnAnmelden" value="submit" class="btn btn-info">Anmelden</button>
              <button type="reset" name="button" value="reset" class="btn btn-warning">Löschen</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Ausgabe von Fehlern und Erfolgen -->
  <div class="alert alert-info">
    <strong>Report: </br></strong>
    <?php
    echo $ausgabe;
    ?>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>

</html>


<?php
//------------------------------------------------------PHP FUNKTIONEN------------------------------------------------------
//Hier wird die Verbindung zu DB erstellt
function start($pass, $user, $host, $username, $password, $database, $ausgabe)
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

//Hier wird mit den eingegebenen Daten überprüft ob diese mit den einem eines USer übereinstimmt
function anmelden($pass, $user, $host, $username, $password, $database, $ausgabe)
{
  $conn = new mysqli($host, $username, $password, $database);
  $sql = "SELECT id_user, admin, fname, lname, password FROM users WHERE username='$user'";
  $result = $conn->query($sql);
  if ($result->num_rows == 0) {
    $ausgabe .= "<br> Username nicht gefunden";
  } else {

    while ($row = $result->fetch_assoc()) {
      //Hier wird das Password überprüft und falls dieses stimmt, wird man (nach dem alle wichtigen daten in die Session variablen
      //abgespeichert wurden) auf die seite admin.php (falls man ein Admin ist) oder auf die Seite overview.php (falls man ein User ist) weitergeleitet
      if (password_verify($pass, $row["password"])) {
        $ausgabe .= "<br> Erfolgreiche Anmeldung";
        session_start();
        session_regenerate_id();
        $_SESSION["id_user"] = $row["id_user"];
        $_SESSION["admin"] = $row["admin"];
        $_SESSION["user"] = $user;
        $_SESSION["firstname"] = $row["fname"];
        $_SESSION["lastname"] = $row["lname"];
        $_SESSION['pass'] = $_POST['password'];

        if ($row["admin"] == 1) {
          header('LOCATION: admin.php');
        } else {
          header('LOCATION: overview.php');
        }
      } else {
        $ausgabe .= "<br> Falsches Passwort";
      }
    }
  }
  return $ausgabe;
}
//----------------------------------------------------PHP FUNKTIONEN ENDE----------------------------------------------------
?>