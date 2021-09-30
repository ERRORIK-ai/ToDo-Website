<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDOs</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>



</head>

<body>

    <?php
    //meldet sich bei der datenbank an
    require 'connect_db.php';

    //falls nicht angemeldet
    if (!isset($_SESSION["id_user"])) {
        header("Location: logout.php");
    }



    //startet die Funtkionen um die Liste und Pagnition zu erstellen
    $this_page_first_result = first_result($host, $username, $password, $database, $id_user, $ausgabe);
    $todolist = create_list($host, $username, $password, $database, $id_user, $this_page_first_result);

    //falls der Delete Knopf gedrückt wurder
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['delete'])) {
            $todo_id = $_POST['delete'];
            $ausgabe = delete_todo($host, $username, $password, $database, $id_user, $todo_id, $ausgabe);
        }
    }



    ?>

    <!------------------------------------------NAVBAR------------------------------------------>
    <nav class="navbar navbar-expand-md bg-dark navbar-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand">Erik's Overview</a>
            </div>
            <form class="navbar-form navbar-left" action="" method="GET">
                <div class="input-group">
                    <input class="form-control mr-sm-2" type="search" name="Search" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Suche</button>
                    </div>
                </div>
            </form>
            <div class="navbar-collapse collapse flex-shrink-1 flex-grow-0 order-last">
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    if ($_SESSION["admin"] == 1) {
                        echo '<li>
                    <p class="navbar-text"><a href="admin.php">Admin Bereich </a>&nbsp;&nbsp;&nbsp;</p>
                </li>';
                    }
                    ?>
                    <li>
                        <p class="navbar-text"><a href="create_todo.php">Create ToDos</a></p>
                    </li>
                    <li>
                        <p class="navbar-text">&nbsp;&nbsp;&nbsp; <?php echo $firstname; ?> <?php echo $lastname; ?> &nbsp;&nbsp;&nbsp;</p>
                    </li>
                    <li>
                        <p class="navbar-text"><a href="logout.php"> Log Out</a></p>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!------------------------------------------ToDo List------------------------------------------>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead class="">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Priorität:</th>
                    <th scope="col">Kategorie:</th>
                    <th scope="col">Was:</th>
                    <th scope="col">Zuletzt Aktualisiert:</th>
                    <th scope="col">Fällig:</th>
                    <th scope="col">Ändern:</th>
                    <th scope="col">Löschen:</th>
                </tr>
            </thead>
            <tbody>
                <!-- Hier kommt die Liste -->
                <?php echo $todolist; ?>
            </tbody>
        </table>
    </div>



    <!-- Hier kommt die pagination -->
    <?php
    page_listed($host, $username, $password, $database, $id_user, $ausgabe);
    ?>


    <!-- Ausgabe von Fehlern oder Erfolgen -->
    <div class="alert alert-primary" role="alert">
        <h4 class="alert-heading">Report:</h4>
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

//Hier wird die ToDo liste erstellt
function create_list($host, $username, $password, $database, $id_user, $this_page_first_result)
{

    $row_counter = $this_page_first_result + 1;
    $output = "";
    $conn = new mysqli($host, $username, $password, $database);


    if (isset($_GET['Search'])) {
        $searchq = $_GET['Search'];

        $sql3 = "SELECT * FROM todos AS t INNER JOIN categories AS c ON t.fk_id_category=c.id_category INNER JOIN users_categories AS uc ON c.id_category=uc.fk_id_category WHERE t.fk_id_user='$id_user' AND uc.fk_id_users='$id_user' AND (t.title LIKE '%$searchq%' OR t.date_create LIKE '%$searchq%' OR t.date_expiration LIKE '%$searchq%' OR t.date_done LIKE '%$searchq%' OR t.content LIKE '%$searchq%' OR c.name LIKE '%$searchq%') ORDER BY priority DESC, date_expiration";
        $result3 = $conn->query($sql3);
        if ($result3->num_rows == 0) {
            $output .= "Sie haben keine ToDos";
        } else {

            while ($row3 = $result3->fetch_assoc()) {

                $category_nr = $row3['fk_id_category'];

                $datediff = floor((strtotime($row3['date_expiration']) - strtotime(date("Y-m-d"))) / (60 * 60 * 24));
                if ($datediff < 0) {
                    $datediff_text = '<div class="p-3 mb-2 bg-danger text-white">seit ' . abs($datediff) . ' Tagen</div>';
                } elseif ($datediff > 0) {
                    $datediff_text = '<div class="p-3 mb-2 bg-success text-white">in ' . abs($datediff) . ' Tagen</div>';
                } else {
                    $datediff_text = '<div class="p-3 mb-2 bg-warning  text-white">Heute!</div>';
                }





                if ($row3['archive'] == 1) {
                    //ToDO ist im Archiv
                    $output .= "
                        <tr class='table-warning'>
                        <th scope='row'>" . $row_counter . "</th>
                        <td>" . $row3['priority'] . "</td>
                        <td>" . htmlspecialchars($row3['name']) . "</td>
                        <td>" . htmlspecialchars($row3['title']) . "</td>
                        <td>" . $row3['date_create'] . "</td>
                        <td>" . $datediff_text . "</td>
 
                        <td>
                        <form action='create_todo.php' method='GET'>
                        <button type='submit' name='ToDo' value='" . $row3['id_todo'] . "' class='btn btn-info'>Ändern</button>
                        </form>
                        </td>
                        <td>
                        <form action='' method='POST'>
                        <button type='submit' name='delete' value='" . $row3['id_todo'] . "' class='btn btn-warning'>Löschen</button>
                        </form>
                        </td>
                        </tr>";
                } else {

                    if ($row3['date_done'] !== "0000-00-00") {
                        //ToDO ist abgeschlossen
                        $output .= "
                            <tr class='table-success'>
                            <th scope='row'>" . $row_counter . "</th>
                            <td>" . $row3['priority'] . "</td>
                            <td>" . htmlspecialchars($row3['name']) . "</td>
                            <td>" . htmlspecialchars($row3['title']) . "</td>
                            <td>" . $row3['date_create'] . "</td>
                            <td>" . $datediff_text . "</td>
                            
                            <td>
                            <form action='create_todo.php' method='GET'>
                            <button type='submit' name='ToDo' value='" . $row3['id_todo'] . "' class='btn btn-info'>Ändern</button>
                            </form>
                            </td>
                            <td>
                            <form action='' method='POST'>
                            <button type='submit' name='delete' value='" . $row3['id_todo'] . "' class='btn btn-warning'>Löschen</button>
                            </form>
                            </td>
                            </tr>";
                    } else {
                        if ($datediff > 0) {
                            //ToDO ist noch nicht abgeschlossen
                            $output .= "
                                    <tr class='table-info'>
                            <th scope='row'>" . $row_counter . "</th>
                            <td>" . $row3['priority'] . "</td>
                            <td>" . htmlspecialchars($row3['name']) . "</td>
                            <td>" . htmlspecialchars($row3['title']) . "</td>
                            <td>" . $row3['date_create'] . "</td>
                            <td>" . $datediff_text . "</td>
                            
                            <td>
                            <form action='create_todo.php' method='GET'>
                            <button type='submit' name='ToDo' value='" . $row3['id_todo'] . "' class='btn btn-info'>Ändern</button>
                            </form>
                            </td>
                            <td>
                            <form action='' method='POST'>
                            <button type='submit' name='delete' value='" . $row3['id_todo'] . "' class='btn btn-warning'>Löschen</button>
                            </form>
                            </td>
                            </tr>";
                        } else {
                            //ToDO ist abgelaufen
                            $output .= "
                                    <tr class='table-danger'>
                            <th scope='row'>" . $row_counter . "</th>
                            <td>" . $row3['priority'] . "</td>
                            <td>" . htmlspecialchars($row3['name']) . "</td>
                            <td>" . htmlspecialchars($row3['title']) . "</td>
                            <td>" . $row3['date_create'] . "</td>
                            <td>" . $datediff_text . "</td>
                            
                            <td>
                            <form action='create_todo.php' method='GET'>
                            <button type='submit' name='ToDo' value='" . $row3['id_todo'] . "' class='btn btn-info'>Ändern</button>
                            </form>
                            </td>
                            <td>
                            <form action='' method='POST'>
                            <button type='submit' name='delete' value='" . $row3['id_todo'] . "' class='btn btn-warning'>Löschen</button>
                            </form>
                            </td>
                            </tr>";
                        }
                    }
                }
                $row_counter++;
            }
        }
    } else {

        //Falls kein Search button gedrückt wurde, wird das hier ausgeführt
        $sql3 = "SELECT * FROM todos AS t INNER JOIN categories AS c ON t.fk_id_category=c.id_category INNER JOIN users_categories AS uc ON c.id_category=uc.fk_id_category WHERE uc.fk_id_users='$id_user' AND t.archive='0' ORDER BY priority DESC, t.date_expiration LIMIT " . $this_page_first_result . ',10';
        $result3 = $conn->query($sql3);
        if ($result3->num_rows == 0) {
            $output .= "Sie haben keine ToDos";
        } else {

            while ($row3 = $result3->fetch_assoc()) {

                $category_nr = $row3['fk_id_category'];

                $datediff = floor((strtotime($row3['date_expiration']) - strtotime(date("Y-m-d"))) / (60 * 60 * 24));
                if ($datediff < 0) {
                    $datediff_text = '<div class="p-3 mb-2 bg-danger text-white">seit ' . abs($datediff) . ' Tagen</div>';
                } elseif ($datediff > 0) {
                    $datediff_text = '<div class="p-3 mb-2 bg-success text-white">in ' . abs($datediff) . ' Tagen</div>';
                } else {
                    $datediff_text = '<div class="p-3 mb-2 bg-warning  text-white">Heute!</div>';
                }



                //Den Namen herausfinden
                $sql2 = "SELECT name FROM categories WHERE id_category='$category_nr'";
                $result2 = $conn->query($sql2);
                if ($result2->num_rows == 0) {
                    $output .= "Es wurde kein Name für die Kategorie Nummer: [" . $category_nr . "] erstellt.";
                } else {
                    while ($row2 = $result2->fetch_assoc()) {
                        $category_name = $row2["name"];
                    }

                    if ($row3['fk_id_user'] == $id_user) {
                        if ($row3['date_done'] !== "0000-00-00") {
                            //ToDO ist abgeschlossen
                            $output .= "
                            <tr class='table-success'>
                            <th scope='row'>" . $row_counter . "</th>
                            <td>" . $row3['priority'] . "</td>
                            <td>" . htmlspecialchars($category_name) . "</td>
                            <td>" . htmlspecialchars($row3['title']) . "</td>
                            <td>" . $row3['date_create'] . "</td>
                            <td>" . $datediff_text . "</td>";
                        } else {
                            if ($datediff > 0) {
                                //ToDO ist noch nicht abgeschlossen
                                $output .= "
                                    <tr class='table-info'>
                            <th scope='row'>" . $row_counter . "</th>
                            <td>" . $row3['priority'] . "</td>
                            <td>" . htmlspecialchars($category_name) . "</td>
                            <td>" . htmlspecialchars($row3['title']) . "</td>
                            <td>" . $row3['date_create'] . "</td>
                            <td>" . $datediff_text . "</td>";
                            } else {
                                //ToDO ist abgelaufen
                                $output .= "
                                    <tr class='table-danger'>
                            <th scope='row'>" . $row_counter . "</th>
                            <td>" . $row3['priority'] . "</td>
                            <td>" . htmlspecialchars($category_name) . "</td>
                            <td>" . htmlspecialchars($row3['title']) . "</td>
                            <td>" . $row3['date_create'] . "</td>
                            <td>" . $datediff_text . "</td>";
                            }
                        }
                        $output .= "<td>
                    <form action='create_todo.php' method='GET'>
                    <button type='submit' name='ToDo' value='" . $row3['id_todo'] . "' class='btn btn-info' >Ändern</button>
                    </form>
                    </td>
                    <td>
                    <form action='' method='POST'>
                    <button type='submit' name='delete' value='" . $row3['id_todo'] . "' class='btn btn-warning' >Löschen</button>
                    </form>
                    </td>
                    </tr>";
                    } else {
                        $output .= "
                    <tr class='table-secondary'>
            <th scope='row'>" . $row_counter . "</th>
            <td>" . $row3['priority'] . "</td>
            <td>" . htmlspecialchars($category_name) . "</td>
            <td>" . htmlspecialchars($row3['title']) . "</td>
            <td>" . $row3['date_create'] . "</td>
            <td>" . $datediff_text . "</td>
            <td>
            <form action='create_todo.php' method='GET'>
            <button type='submit' name='ToDo' value='" . $row3['id_todo'] . "' class='btn btn-secondary btn-lg' disabled>Ändern</button>
            </form>
            </td>
            <td>
            <form action='' method='POST'>
            <button type='submit' name='delete' value='" . $row3['id_todo'] . "' class='btn btn-secondary btn-lg' disabled>Löschen</button>
            </form>
            </td>
            </tr>";
                    }
                    $row_counter++;
                }
            }
        }
    }

    return $output;
}


//Hier wird die erste Todo berechnet die oben auf der Liste angezeigt wird (1-11-21-31-etc)
function first_result($host, $username, $password, $database, $id_user, $ausgabe)
{
    $results_per_page = 10;
    $number_of_results = 0;


    $conn = new mysqli($host, $username, $password, $database);

    //Anzahl an Resultaten
    $sql3 = "SELECT * FROM todos WHERE archive='0'";
    $result3 = $conn->query($sql3);
    if ($result3->num_rows == 0) {
    } else {
        while ($row3 = $result3->fetch_assoc()) {
        }
    }
    $number_of_results = $number_of_results + mysqli_num_rows($result3);


    //Anzahl max. Seiten
    $number_of_pages = ceil($number_of_results / $results_per_page);

    //Momentane Seite
    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }

    //Startnummerzahl für den SQL Befehl "LIMIT"
    $this_page_first_result = ($page - 1) * $results_per_page;



    return $this_page_first_result;
}



//Hier wird die Pagination erstellt
function page_listed($host, $username, $password, $database, $id_user, $ausgabe)
{

    $results_per_page = 10;
    $number_of_results = 0;

    $output = "";

    $conn = new mysqli($host, $username, $password, $database);

    //Anzahl an Resultaten
    $sql3 = "SELECT * FROM todos WHERE archive='0'";
    $result3 = $conn->query($sql3);
    if ($result3->num_rows == 0) {
        $output .= "Sie haben Keine TODOS";
    }
    $number_of_results = $number_of_results + mysqli_num_rows($result3);


    //Anzahl max. Seiten
    $number_of_pages = ceil($number_of_results / $results_per_page);

    //Momentane Seite
    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }

    //Alle Seiten Printen
    if (!isset($_GET['Search'])) {
        echo '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">
        <li class="page-item disabled">
        <a class="page-link" href="#" tabindex="-1">Seiten:</a>
      </li>';
        for ($page = 1; $page <= $number_of_pages; $page++) {
            echo '<li class="page-item"><a class="page-link" href="overview.php?page=' . $page . '">' . $page . '</a></li>';
        }
        echo '</ul></nav>';
    }
    return $output;
}



//Falls der Delete Knopf gedrückt wurde
function delete_todo($host, $username, $password, $database, $id_user, $todo_id, $ausgabe)
{

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Verbindungsfehler: " . $conn->connect_error);
    }

    $sql = "DELETE FROM todos WHERE id_todo=$todo_id";

    if ($conn->query($sql) === TRUE) {
        $ausgabe .= "Erfolgreich ID: " . $todo_id . " gelöscht.";
        header("refresh: 3");
    } else {
        $ausgabe .= "Error Löschung fehlgeschlagen: " . $conn->error;
    }
    return $ausgabe;
}

?>