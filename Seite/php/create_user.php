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

    //Verbindung zur DB
    require 'connect_db.php';

    //Falls nicht angemeldet oder kein Admin dann weiterleitung zu LogOut
    if (!isset($_SESSION["id_user"]) or $_SESSION["admin"] == 0) {
        header("Location: logout.php");
    }

    //Hier werden die wichtigsten Variablen vordefiniert
    $admin_us = "";
    $username_us = "";
    $fname_us = "";
    $lname_us = "";
    $category_us = "";
    $password_fill = "";


    //Falls bei admin.php ein CHange knopf gdrückt wurde (methode GET) dann wird dieser IF ausgeführt
    if (isset($_GET['user'])) {



        $id_us = $_GET['user'];

        //Falls es ein User mit der von der Get Funktion mitgegebenen schon existiert dann werden seine Daten in diese Variablen abgespeichert
        $conn = new mysqli($host, $username, $password, $database);
        $sql3 = "SELECT * FROM users WHERE id_user='$id_us'";
        $result3 = $conn->query($sql3);
        if ($result3->num_rows == 0) {
            $ausgabe .= "Es gibt keinen User mit der ID: " . $id_td;
        } else {

            while ($row3 = $result3->fetch_assoc()) {
                $admin_us = $row3['admin'];
                $username_us = $row3['username'];
                $fname_us = $row3['fname'];
                $lname_us = $row3['lname'];
                $password_fill = "Neues Passwort?";
            }
        }
    } else {
        //Falls es kein GET gibt dann wird die Variable id_user auf "" gesetzt.
        $id_us = "";
    }

    //FAlls der submit knopf gedrückt wurde, werden die DAten aus dem Forms abgespeichert
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $admin_us = $_POST['admin'];
        $username_us = $_POST['username'];
        $fname_us = $_POST['fname'];
        $lname_us = $_POST['lname'];
        $password_us = $_POST['password'];

        //Die DAten aus dem Forms werden in Funktion save_user weitergegeben
        if (isset($_POST['admin']) && isset($_POST['username']) && isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['password'])) {
            $ausgabe .= save_user($host, $username, $password, $database, $id_us, $ausgabe, $admin_us, $username_us, $fname_us, $lname_us, $password_us);
        } else {
            $ausgabe .= " Bitte geben Sie alle Notwendigen Daten an.";
        }
    }

    ?>

    <!------------------------------------------NAVBAR------------------------------------------>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand">Erik's User Creater</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="admin.php"><span class="glyphicon glyphicon-home"></span> Admin Seite</a></li>
                <li>
                    <p class="navbar-text"><span class="glyphicon glyphicon-user"></span> <?php echo $firstname; ?> <?php echo $lastname; ?></p>
                </li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Log Out</a></li>
            </ul>
        </div>
    </nav>

    <!------------------------------------------Create User------------------------------------------>
    <div class="container">
        <form class="form-horizontal" action="" method="POST">
            <div class="form-group">
                <label for="admin" class="control-label col-xs-4">Admin</label>
                <div class="col-xs-8">
                    <?php echo adminchecker($admin_us); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="username" class="control-label col-xs-4">Benutzername</label>
                <div class="col-xs-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-address-book"></i>
                        </div>
                        <input id="username" value="<?php echo htmlspecialchars($username_us) ?>" name="username" placeholder="Login Name..." type="text" required="required" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="categories" class="control-label col-xs-4">Kategorien</label>
                <div class="col-xs-8">
                    <select multiple="multiple" id="categories" name="categories[]" class="select form-control">
                        <?php echo allcategories($host, $username, $password, $database); ?>
                    </select>
                    <span id="categoriesHelpBlock" class="help-block">Um mehrere Kategorien zu selektieren, die Ctrl- oder Shift-Taste gedrückt halten.</span>
                </div>
            </div>
            <div class="form-group">
                <label for="fname" class="control-label col-xs-4">Vorname</label>
                <div class="col-xs-8">
                    <input id="fname" name="fname" value="<?php echo htmlspecialchars($fname_us) ?>" type="text" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group">
                <label for="lname" class="control-label col-xs-4">Nachname</label>
                <div class="col-xs-8">
                    <input id="lname" name="lname" value="<?php echo htmlspecialchars($lname_us) ?>" type="text" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="control-label col-xs-4">Neues Passwort</label>
                <div class="col-xs-8">
                    <input id="password" name="password" placeholder="Falls Leer, kein Passwort" type="password" class="form-control">
                    <span id="passwordHelpBlock" class="help-block">Falls Sie dieses Feld Leer lassen, wird der dementsprechende Benutzer ohne ein Passwort abgespeichert.</span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-xs-offset-4 col-xs-8">
                    <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
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
//In dieser Funktion wird der User mit den DAten aus den Forms in die DB abgespeichert
function save_user($host, $username, $password, $database, $id_us, $ausgabe, $admin_us, $username_us, $fname_us, $lname_us, $password_us)
{
    $conn = new mysqli($host, $username, $password, $database);

    //Hier werden die Daten unschädlich für die DB gemacht
    $pass_hash = password_hash($password_us, PASSWORD_DEFAULT);
    $username_us = mysqli_real_escape_string($conn, $username_us);
    $fname_us = mysqli_real_escape_string($conn, $fname_us);
    $lname_us = mysqli_real_escape_string($conn, $lname_us);


    //Hier wird geprüft ob es schon eine User mit der mitgegebenen ID schon existiert
    $sql3 = "SELECT * FROM users WHERE id_user='$id_us'";
    $result3 = $conn->query($sql3);
    if ($result3->num_rows == 0) {
    } else {
        while ($row3 = $result3->fetch_assoc()) {
        }
    }

    //Falls das Resultat mehr als 0 (es existiert schon ein User, also folglich muss dieser geändert werden)
    //wird der User mit den neuen Daten geupdatet
    if (mysqli_num_rows($result3) > 0) {
        if ($conn->connect_error) {
            die("Verbindungsfehler: " . $conn->connect_error);
        }

        $sql = "UPDATE users SET id_user='$id_us', admin='$admin_us', username='$username_us', fname='$fname_us', lname='$lname_us', password='$pass_hash' WHERE id_user='$id_us'";

        if ($conn->query($sql) === TRUE) {
            $ausgabe .= "<br> Erfolgreich User mit der ID: " . $id_us . " geupdatet.";
        } else {
            $ausgabe .= "<br> Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        //Falls es noch keinen User mit der ID gibt, dann wird ein neuer erstellt
        $sql = "INSERT INTO users (id_user, admin, username, fname, lname, password)
        VALUES ('$id_us', '$admin_us', '$username_us', '$fname_us', '$lname_us', '$pass_hash')";

        if ($conn->query($sql) === TRUE) {
            $ausgabe .= "<br> Erfolgreich neuer User mit der ID: " . $id_us . " erstellt.";
        } else {
            $ausgabe .= "<br> Error: " . $sql . "<br>" . $conn->error;
        }
    }


    //löscht die vorher angegebenen Kategorien
    if ($id_us !== "") {
        //Alte REferenzen werden hier gelöscht
        $sql = "DELETE FROM users_categories WHERE fk_id_users=$id_us";
        if ($conn->query($sql) === TRUE) {
            $ausgabe .= "<br> Erfolgreich alle Referenzen zu den Kategorien welche mit der User ID: " . $id_us . " in verbindung standen gelöscht.";
        } else {
            $ausgabe .= "<br> Error: " . $sql . "<br>" . $conn->error;
        }

        //Hier werden neue Referenzen zu den Kategorien erstellt
        if (isset($_POST['categories'])) {
            foreach ($_POST['categories'] as $category_us) {
                $sql = "INSERT INTO users_categories (fk_id_users, fk_id_category)
                VALUES ('$id_us', '$category_us')";

                if ($conn->query($sql) === TRUE) {
                    $ausgabe .= "<br> Erfolgreich neuer User zu der Kategorie mit der ID: " . $category_us . " hinzugefügt.";
                } else {
                    $ausgabe .= "<br> Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    } else {

        if ($conn->query($sql) === TRUE) {
            $last_id = $conn->insert_id;

            if (isset($_POST['categories'])) {
                foreach ($_POST['categories'] as $category_us) {
                    $sql = "INSERT INTO users_categories (fk_id_users, fk_id_category)
                    VALUES ('$last_id', '$category_us')";

                    if ($conn->query($sql) === TRUE) {
                        $ausgabe .= "<br> Erfolgreich neuer User zu der Kategorie mit der ID: " . $category_us . " hinzugefügt.";
                    } else {
                        $ausgabe .= "<br> Error: " . $sql . "<br>" . $conn->error;
                    }
                }
            }
        } else {
            echo "Error beim herausfinden der letzten ID: " . $sql . "<br>" . $conn->error;
        }
    }



    return $ausgabe;
}

//Hier wird je nach dem falls ein User geändert werden soll die Radio button angepasst
function adminchecker($admin_us)
{
    //Falls der User ein Admin war wird der Admin radio button vorselektiert
    if ($admin_us == 1) {
        $output = '<label class="radio-inline">
        <input type="radio" checked name="admin" value="1">
        Ja
    </label>
    <label class="radio-inline">
        <input type="radio" name="admin" value="0">
        Nein
    </label>';
    } elseif ($admin_us == 0) {
        $output = '<label class="radio-inline">
        <input type="radio" name="admin" value="1">
        Ja
    </label>
    <label class="radio-inline">
        <input type="radio" checked name="admin" value="0">
        Nein
    </label>';
    } else {
        //Falls der User ein User war wird der User radio button vorselektiert
        $output = '<label class="radio-inline">
        <input type="radio" name="admin" value="1">
        Ja
    </label>
    <label class="radio-inline">
        <input type="radio" name="admin" value="0">
        Nein
    </label>';
    }
    return $output;
}

//Hier werden alle Kategorien die dem User zur verfügung gegeben wurde in die Form Categories (multiple option) abgespeichert
function allcategories($host, $username, $password, $database)
{
    $conn = new mysqli($host, $username, $password, $database);
    $output = "";

    //Alle kategorien aus DB holen
    $sql = "SELECT * FROM categories";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        $output = "Es gibt keine Kategorien";
    } else {

        while ($row = $result->fetch_assoc()) {
            $output .= '<option value="' . $row['id_category'] . '">' . htmlspecialchars($row['name']) . '</option>';
        }
    }
    return $output;
}

?>