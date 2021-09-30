<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Seite</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


</head>

<body>
    <?php
    //anmeldung an die Datenbank
    require 'connect_db.php';

    //falls kein Admin oder nicht angemeldet
    if (!isset($_SESSION["id_user"]) or $_SESSION["admin"] == 0) {
        header("Location: logout.php");
    }

    //Liste der kategorien wird hier erstellt
    $categorylist = create_list($host, $username, $password, $database, $ausgabe);


    //falls der Delete Knopf gedrückt wurde
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['delete'])) {
            $delete_category_id = $_POST['delete'];
            $ausgabe = delete_category($host, $username, $password, $database, $delete_category_id, $ausgabe);
        }
    }

    ?>


    <!------------------------------------------NAVBAR------------------------------------------>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand">Erik's Kategorien</a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="admin.php"><span class="glyphicon glyphicon-th"></span> Benutzer Management</a></li>
                <li><a href="create_category.php"><span class="glyphicon glyphicon-wrench"></span> Neue Kategorie erstellen</a></li>

                <li>
                    <p class="navbar-text"><span class="glyphicon glyphicon-user"></span> <?php echo $firstname; ?> <?php echo $lastname; ?></p>
                </li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Log Out</a></li>
            </ul>
        </div>
    </nav>


    <!------------------------------------------ToDo List------------------------------------------>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead class="">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Ändern:</th>
                    <th scope="col">Löschen:</th>
                </tr>
            </thead>
            <tbody>
                <!-- Ausgabe der Kategorieliste -->
                <?php echo $categorylist; ?>
            </tbody>
        </table>
    </div>




    <!-- Ausgabe von Fehlern und Erfolgen -->
    <div class="alert alert-info">
        <strong>Report: </br></strong>
        <?php
        echo $ausgabe;
        ?>
    </div>






</body>

</html>


<?php
//------------------------------------------------------PHP FUNKTIONEN------------------------------------------------------
//Hier wird die Liste erstellt
function create_list($host, $username, $password, $database, $ausgabe)
{

    $row_counter = 1;
    $output = "";
    $conn = new mysqli($host, $username, $password, $database);



    $sql3 = "SELECT * FROM categories ORDER BY name";
    $result3 = $conn->query($sql3);
    if ($result3->num_rows == 0) {
        $output .= "Es wurden keine Kategorien gefunden";
    } else {


        while ($row3 = $result3->fetch_assoc()) {
            //ausgabe der einzelnen zeilen der kategorien in der Liste
            $output .= "
 <tr class='table-default'>
 <th scope='row'>" . $row_counter . "</th>

 <td>" . htmlspecialchars($row3['name'])  . "</td>
 
 <td>
 <form action='create_category.php' method='GET'>
 <button type='submit' name='category' value='" . $row3['id_category'] . "' class='btn btn-info'>Ändern</button>
 </form>
 </td>
 <td>
 <form action='' method='POST'>
 <button type='submit' name='delete' value='" . $row3['id_category'] . "' class='btn btn-warning'>Löschen</button>
 </form>
 </td>
 </tr>";

            $row_counter++;
        }
    }

    return $output;
}

//Falls der Delete Knopf gedrückt wurde wird das hier ausgeführt
function delete_category($host, $username, $password, $database, $delete_category_id, $ausgabe)
{

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Verbindungsfehler: " . $conn->connect_error);
    }

    //Zuerst wird die Referenz zu den Usern gelöscht
    $sql = "DELETE FROM users_categories WHERE fk_id_category=$delete_category_id";

    if ($conn->query($sql) === TRUE) {
        $ausgabe .= "Erfolgreich Referenz zu Usern mit der Kategorie Nummer ID: " . $delete_category_id . " gelöscht.";
    } else {
        $ausgabe .= "Error Löschung von Referenz zu Usern fehlgeschlagen: " . $conn->error;
    }

    //dannach die Referenz zu den Todos
    $sql = "DELETE FROM todos WHERE fk_id_category=$delete_category_id";

    if ($conn->query($sql) === TRUE) {
        $ausgabe .= "Erfolgreich Referenz zu ToDos mit der Kategorie Nummer ID: " . $delete_category_id . " gelöscht.";
    } else {
        $ausgabe .= "Error Löschung von Referenz zu den ToDos fehlgeschlagen: " . $conn->error;
    }

    //und zu guter letzt die kategorie an sich 
    $sql = "DELETE FROM categories WHERE id_category=$delete_category_id";

    if ($conn->query($sql) === TRUE) {
        $ausgabe .= "Erfolgreich ID: " . $delete_category_id . " gelöscht.";
        header("refresh: 3");
    } else {
        $ausgabe .= "Error Löschung fehlgeschlagen: " . $conn->error;
    }
    return $ausgabe;
}
?>