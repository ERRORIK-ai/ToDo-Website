<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDOs</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>

<body>
    <?php

    //Verbindung mit der DB
    require 'connect_db.php';

    //Falls nicht angemeldet, wird man direkt zur LogOut Seite geschickt
    if (!isset($_SESSION["id_user"])) {
        header("Location: logout.php");
    }


    //Hier wird die Option Liste erstellt, diese wird abhängig von den Zugriffsrechten der Kategoiren des aktuellen Benutzers erstellt
    $category_user = allcategories($host, $username, $password, $database, $id_user, $ausgabe);

    //Hier werden die wichtigen Variablen schonmal definiert
    $id_td = Null;
    $name_td = Null;
    $category_name_td = "";
    $category_td = "";
    $priority_name_td = "";
    $priority_td = "";
    $date_td = Null;
    $content_td = Null;
    $archiv_td = Null;
    $done_td = Null;


    //Falls jemand bei admin.php den Knopf ändern gdrückt hat, werden Daten mit der Funtkion Get verschickt
    //Und das wird hier überprüft

    //Falls das der Fall ist, werden die Daten aus der DB ausgelesen um den Form voraus auszufüllen
    if (isset($_GET['ToDo'])) {

        $id_td = $_GET['ToDo'];

        $conn = new mysqli($host, $username, $password, $database);
        $sql3 = "SELECT * FROM todos WHERE id_todo='$id_td'";
        $result3 = $conn->query($sql3);
        if ($result3->num_rows == 0) {
            $ausgabe .= "Es gibt kein ToDo Eintrag für die ID: " . $id_td;
        } else {

            while ($row3 = $result3->fetch_assoc()) {
                $name_td = $row3['title'];
                $category_td = $row3['fk_id_category'];
                $priority_td = $row3['priority'];
                $priority_name_td = $row3['priority'] . ". Priorität";
                $date_td = $row3['date_expiration'];
                $content_td = $row3['content'];
                $archiv_td = $row3['archive'];
                if ($row3['date_done'] == Null) {
                    $done_td = 0;
                } else {
                    $done_td = 1;
                }
            }

            //Hier wird noch der Name der Kategorie ausgelesen, denn $category_td ist nur eine nummer
            $sql2 = "SELECT name FROM categories WHERE id_category='$category_td'";
            $result2 = $conn->query($sql2);
            if ($result2->num_rows == 0) {
                $ausgabe .= "Es gibt keinen Namen für die Kategorie Nummer: " . $category_td;
            } else {
                while ($row2 = $result2->fetch_assoc()) {
                    $category_name_td = $row2['name'];
                }
            }
        }
    }

    //Falls man auf der Seite den Submit/Bestätigen Knopf gedrückt hat, dann werden die neu ausgefüllte Form in Variablen abgespeichert
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $name_td = $_POST['name'];
        $category_td = $_POST['category'];
        $priority_td = $_POST['priority'];
        $date_td = $_POST['exp_date'];
        $content_td = $_POST['content'];
        $archiv_td = $_POST['archiv'];
        $done_td = $_POST['done'];

        //Und diese Variablen werden in die funktion save_todo weitergeschickt
        if (isset($_POST['name']) && isset($_POST['category']) && isset($_POST['priority']) && isset($_POST['archiv']) && isset($_POST['done'])) {
            $ausgabe .= save_todo($host, $username, $password, $database, $id_user, $ausgabe, $id_td, $name_td, $category_td, $priority_td, $date_td, $content_td, $archiv_td, $done_td);
        } else {
            $ausgabe .= " Bitte geben Sie alle Notwendigen Daten an.";
        }
    }

    ?>

    <!------------------------------------------NAVBAR------------------------------------------>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand">Erik's Creater</a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="overview.php"><span class="glyphicon glyphicon-home"></span> Overview</a></li>
                <!--<p class="navbar-text"></p>-->
                <li>
                    <p class="navbar-text"><span class="glyphicon glyphicon-user"></span> <?php echo $firstname; ?> <?php echo $lastname; ?></p>
                </li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Log Out</a></li>
            </ul>
        </div>
    </nav>

    <!------------------------------------------Create ToDos------------------------------------------>
    <div class="container">
        <form role="form" action="" method="post">
            <div class="form-group row">
                <label for="name" class="col-2 col-form-label">Name</label>
                <div class="col-10">
                    <input id="name" name="name" value="<?php echo htmlspecialchars($name_td); ?>" placeholder="Name" type="text" required="required" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="category" class="col-2 col-form-label">Kategorie</label>
                <div class="col-10">
                    <select id="category" name="category" class="custom-select" required="required">
                        <option value="<?php echo $category_td; ?>" selected hidden><?php echo htmlspecialchars($category_name_td); ?></option>
                        <?php
                        echo $category_user
                        ?>

                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="priority" class="col-2 col-form-label">Priorität</label>
                <div class="col-10">
                    <select id="priority" name="priority" class="form-control">
                        <option value="<?php echo $priority_td; ?>" selected hidden><?php echo htmlspecialchars($priority_name_td); ?></option>
                        <option value="1">1. Priorität</option>
                        <option value="2">2. Priorität</option>
                        <option value="3">3. Priorität</option>
                        <option value="4">4. Priorität</option>
                        <option value="5">5. Priorität</option>
                    </select>
                    <span id="priorityHelpBlock" class="form-text text-muted">Priorität des ToDos</span>
                </div>
            </div>
            <div class="form-group row">
                <label for="date" class="col-2 col-form-label">Datum</label>
                <div class="col-10">
                    <input id="exp_date" name="exp_date" type="date" value="<?php echo $date_td; ?>" required="required" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="content" class="col-2 col-form-label">Inhalt</label>
                <div class="col-10">
                    <textarea id="content" name="content" rows="12" class="form-control"><?php echo htmlspecialchars($content_td); ?></textarea>
                    <span id="contentHelpBlock" class="form-text text-muted">Inhalt des ToDos</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-2">Archivierung</label>
                <div class="col-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input name="archiv" id="archiv_0" type="radio" required="required" class="custom-control-input" value="1">
                        <label for="archiv_0" class="custom-control-label">Ja</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input name="archiv" id="archiv_1" type="radio" required="required" class="custom-control-input" value="0">
                        <label for="archiv_1" class="custom-control-label">Nein</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-2">Fertig</label>
                <div class="col-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input name="done" id="done_0" type="radio" required="required" class="custom-control-input" value="1">
                        <label for="done_0" class="custom-control-label">Ja</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input name="done" id="done_1" type="radio" required="required" class="custom-control-input" value="0">
                        <label for="done_1" class="custom-control-label">Nein</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-2 col-10">
                    <button name="submit" type="submit" class="btn btn-primary">Bestätigen</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Hier werden Fehler und Erfolge ausgegeben -->
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
//Diese Funktion speichert die im Form ausgefüllte Todo in der DB ab
function save_todo($host, $username, $password, $database, $id_user, $ausgabe, $id_td, $name_td, $category_td, $priority_td, $date_td, $content_td, $archiv_td, $done_td)
{
    //Hier werden die Daten (mehrzahl Datum) berechnet/erstellt
    $date_today = date("Y.m.d");

    if ($done_td == 1) {
        $done_td = date("Y.m.d");
    } else {
        $done_td = Null;
    }

    //verbindung zur BD
    $conn = new mysqli($host, $username, $password, $database);

    //Falls es schon ein Todo mit dieser Todo ID gibt, wird diese gelöscht
    $sql3 = "SELECT * FROM todos WHERE id_todo='$id_td'";
    $result3 = $conn->query($sql3);
    if ($result3->num_rows == 0) {
    } else {
        while ($row3 = $result3->fetch_assoc()) {
        }
    }

    if (mysqli_num_rows($result3) > 0) {
        if ($conn->connect_error) {
            die("Verbindungsfehler: " . $conn->connect_error);
        }

        $sql = "DELETE FROM todos WHERE id_todo=$id_td";

        if ($conn->query($sql) === TRUE) {
            $ausgabe .= "Erfolgreich ID: " . $id_td . " gelöscht.";
        } else {
            $ausgabe .= "Error Löschung fehlgeschlagen: " . $conn->error;
        }
    }

    //HIer werden die vom User eingegebenen Daten unschädlich gemacht, falls diese Befehle beinhalten welche die DB schaden
    $name_td = mysqli_real_escape_string($conn, $name_td);
    $content_td = mysqli_real_escape_string($conn, $content_td);

    //Hier wird die TOdo in die DB gespeichert
    $sql = "INSERT INTO todos (id_todo, archive, priority, title, date_create, date_expiration, date_done, content, fk_id_category, fk_id_user)
       VALUES ('$id_td', '$archiv_td', '$priority_td', '$name_td', '$date_today', '$date_td', '$done_td', '$content_td', '$category_td', '$id_user')";


    if ($conn->query($sql) === TRUE) {
        $ausgabe .= "<br> Erfolgreich neue Todo mit der ID: " . $id_td . " erstellt.";
    } else {
        $ausgabe .= "<br> Error: " . $sql . "<br>" . $conn->error;
    }
    return $ausgabe;
}

//Diese Funktion erstellt die Liste mit den für den aktuellen User verfügbaren Kategorien
function allcategories($host, $username, $password, $database, $id_user)
{
    $conn = new mysqli($host, $username, $password, $database);
    $output = "";
    //Alle kategorien vom User Herausfinden
    $sql = "SELECT fk_id_category FROM users_categories WHERE fk_id_users='$id_user'";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        $output = "Sie haben keine Kategorie.";
    } else {

        while ($row = $result->fetch_assoc()) {

            $category_nr = $row["fk_id_category"];

            //HIer wird die Liste mit den Kategorie namen erstellt
            $sql2 = "SELECT name FROM categories WHERE id_category='$category_nr'";
            $result2 = $conn->query($sql2);
            if ($result2->num_rows == 0) {
                $category_name = "Es wurde kein Name für die Kategorie Nummer: [" . $category_nr . "] erstellt.";
            } else {
                while ($row2 = $result2->fetch_assoc()) {
                    $category_name = $row2["name"];
                    $output .= " <option value='" . $category_nr . "'>" . htmlspecialchars($category_name) . "</option>";
                }
            }
        }
    }
    return $output;
}
?>