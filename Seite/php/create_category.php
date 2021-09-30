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

    //anmeldung an die DB
    require 'connect_db.php';

    if (!isset($_SESSION["id_user"]) or $_SESSION["admin"] == 0) {
        header("Location: logout.php");
    }


    $name_category = "";
    $id_category = "";

    //Falls die in der URL Steht ob eine bestehende Todo geändert werden muss
    //FAlls in der Url kein Wert steht, werden keine Daten aus der DB zur vorausfüllung des Forms ausgelesen
    if (isset($_GET['category'])) {
        $id_category = $_GET['category'];

        $conn = new mysqli($host, $username, $password, $database);
        $sql3 = "SELECT * FROM categories WHERE id_category='$id_category'";
        $result3 = $conn->query($sql3);
        if ($result3->num_rows == 0) {
            $ausgabe .= "Es gibt kein Kategorie Eintrag für die ID: " . $id_category;
        } else {
            while ($row3 = $result3->fetch_assoc()) {
                $name_category = $row3['name'];
            }
        }
    }


    //Falls der Bestätigen Knopf gedrückt wurde, dann werden die Daten an die Funktion save_category weitergeleitet.
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $name_category = $_POST['name'];
        $id_category = $_POST['id'];

        if (isset($_POST['name'])) {
            $ausgabe .= save_category($host, $username, $password, $database, $ausgabe, $name_category, $id_category);
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
                <li><a href="categories.php"><span class="glyphicon glyphicon-home"></span> Kategorien Management</a></li>
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
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id_category); ?>">
                    <input id="name" name="name" value="<?php echo htmlspecialchars($name_category); ?>" placeholder="Name" type="text" required="required" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-2 col-10">
                    <button name="submit" type="submit" class="btn btn-primary">Bestätigen</button>
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
//Hier werden die Daten die im Form ausgefüllt wurden in die Datenbank geschrieben
function save_category($host, $username, $password, $database, $ausgabe, $name_category, $id_category)
{

    $conn = new mysqli($host, $username, $password, $database);


    //Falls der Admin Code eingibt um die DB zu manipulieren werden diese Hier unschädlich gemacht
    $name_category = mysqli_real_escape_string($conn, $name_category);

    //Falls es schon eine Kategorie mit dieser ID existiert, dann wird diese umbenannt
    $sql3 = "SELECT * FROM categories WHERE id_category='$id_category'";
    $result3 = $conn->query($sql3);
    if ($result3->num_rows == 0) {
    } else {
        while ($row3 = $result3->fetch_assoc()) {
        }
    }

    //Falls es mehr als 0 Kategorien gibt mit der gleichen ID dann werden diese umbenannt
    if (mysqli_num_rows($result3) > 0) {
        if ($conn->connect_error) {
            die("Verbindungsfehler: " . $conn->connect_error);
        }
        //Die Kategorie wird hier geupdatet.
        $sql = "UPDATE categories SET name='$name_category' WHERE id_category='$id_category'";

        if ($conn->query($sql) === TRUE) {
            $ausgabe .= "Erfolgreich ID: " . $id_category . " geändert.";
        } else {
            $ausgabe .= "Error Änderung fehlgeschlagen: " . $conn->error;
        }
    } else {

        //Die Kategorie wird hier abgespeichert.
        $sql = "INSERT INTO categories (id_category, name)
       VALUES ('$id_category', '$name_category')";


        if ($conn->query($sql) === TRUE) {
            $ausgabe .= "<br> Erfolgreich neue Kategorie mit der ID: " . $id_category . " erstellt.";
        } else {
            $ausgabe .= "<br> Error: " . $sql . "<br>" . $conn->error;
        }
    }
    return $ausgabe;
}

?>