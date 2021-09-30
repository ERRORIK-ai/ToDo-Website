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

    //Falls nicht angemeldet oder kein Admin dann wird man rausgeworfen
    if (!isset($_SESSION["id_user"]) or $_SESSION["admin"] == 0) {
        header("Location: logout.php");
    }

    //Die Liste von usern & admins wird hier erstellt
    $userlist = create_list($host, $username, $password, $database, $ausgabe);

    //falls der Delete Knopf gedrückt wurde wird das hier ausgeführt
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['delete'])) {
            $delete_user_id = $_POST['delete'];
            $ausgabe = delete_user($host, $username, $password, $database, $delete_user_id, $ausgabe);
        }
    }



    ?>

    <!------------------------------------------NAVBAR------------------------------------------>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand">Admin Bereich</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="overview.php"><span class="glyphicon glyphicon-home"></span> ToDo Overview</a></li>
                <li><a href="categories.php"><span class="glyphicon glyphicon-th"></span> Kategorien Management</a></li>
                <li><a href="create_user.php"><span class="glyphicon glyphicon-wrench"></span> Neuer Benutzer erstellen</a></li>

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
                    <th scope="col">Admin</th>
                    <th scope="col">Benutzername</th>
                    <th scope="col">Vorname</th>
                    <th scope="col">Nachname</th>
                    <th scope="col">Kategorien</th>
                    <th scope="col">Ändern:</th>
                    <th scope="col">Löschen:</th>
                </tr>
            </thead>
            <tbody>
                <!-- Hier wird die Liste der Usern aufgelistet -->
                <?php echo $userlist; ?>
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
//Hier wird die Liste von Usern erstellt
function create_list($host, $username, $password, $database, $ausgabe)
{



    $row_counter = 1;
    $user_category_name = "";
    $output = "";
    $conn = new mysqli($host, $username, $password, $database);



    $sql3 = "SELECT * FROM users ORDER BY admin DESC, id_user ASC";
    $result3 = $conn->query($sql3);
    if ($result3->num_rows == 0) {
        $output .= "Es wurden keine User gefunden";
    } else {


        while ($row3 = $result3->fetch_assoc()) {
            $id_user = $row3['id_user'];

            //Die kategorie von den usern & admins finden
            $sql2 = "SELECT * FROM users_categories AS uc INNER JOIN categories AS c ON uc.fk_id_category=c.id_category WHERE uc.fk_id_users='$id_user'";
            $result2 = $conn->query($sql2);
            if ($result2->num_rows == 0) {
                $user_category_name .= "Keine Kategorie";
            } else {
                while ($row2 = $result2->fetch_assoc()) {
                    $user_category_name .= $row2["name"] . ", ";
                }
            }
            substr($user_category_name, 0, -2);
            if ($row3['admin'] == 1) {
                $admin = "Ja";
            } else {
                $admin = "Nein";
            }

            //Ausdruck von den einzelnen usern
            $output .= "
 <tr class='table-default'>
 <th scope='row'>" . $row_counter . "</th>
 <td>" . $admin . "</td>
 <td>" . htmlspecialchars($row3['username'])  . "</td>
 <td>" . htmlspecialchars($row3['fname']) . "</td>
 <td>" . htmlspecialchars($row3['lname']) . "</td>
 <td>" . htmlspecialchars($user_category_name) . "</td>
 
 <td>
 <form action='create_user.php' method='GET'>
 <button type='submit' name='user' value='" . $row3['id_user'] . "' class='btn btn-info'>Ändern</button>
 </form>
 </td>
 <td>
 <form action='' method='POST'>
 <button type='submit' name='delete' value='" . $row3['id_user'] . "' class='btn btn-warning'>Löschen</button>
 </form>
 </td>
 </tr>";

            $row_counter++;
            $user_category_name = "";
        }
    }

    return $output;
}

//Wird ausgeführt wenn der Delete knopf gedrückt wurde
function delete_user($host, $username, $password, $database, $delete_user_id, $ausgabe)
{

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Verbindungsfehler: " . $conn->connect_error);
    }

    //Zuerst wird die Referenz zu den Kategorien gelöscht
    $sql = "DELETE FROM users_categories WHERE fk_id_users=$delete_user_id";

    if ($conn->query($sql) === TRUE) {
        $ausgabe .= "Erfolgreich Referenz zu den Kategorie mit der User ID: " . $delete_user_id . " gelöscht.";
    } else {
        $ausgabe .= "Error Löschung von der Referenz zu den Kategorien fehlgeschlagen: " . $conn->error;
    }

    //Dannach die referenz zu den Todos
    $sql = "DELETE FROM todos WHERE fk_id_user=$delete_user_id";

    if ($conn->query($sql) === TRUE) {
        $ausgabe .= "Erfolgreich Referenz zu den ToDos mit der User ID: " . $delete_user_id . " gelöscht.";
    } else {
        $ausgabe .= "Error Löschung von der Referenz zu den ToDos fehlgeschlagen: " . $conn->error;
    }

    //und zu guter letzt den User an sich
    $sql = "DELETE FROM users WHERE id_user=$delete_user_id";


    if ($conn->query($sql) === TRUE) {
        $ausgabe .= "Erfolgreich ID: " . $delete_user_id . " gelöscht.";
        header("refresh: 3");
    } else {
        $ausgabe .= "Error Löschung fehlgeschlagen: " . $conn->error;
    }
    return $ausgabe;
}
?>