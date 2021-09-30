<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Out</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>

<body>

    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">DU WURDEST AUSGELOGT!</h4>
                <p>DU WIRST IN 5 SEKUNDEN ZUR LOGIN-SEITE WEITERGELEITET</p>
                <hr>
                <p class="mb-0">Die Session wird zerstört...</p>
            </div>
        </div>
    </div>


    <?php
    //----------PHP Code----------
    session_start();
    session_destroy();
    ?>

    <!-- Springt nach 5 sek nach login.php -->
    <meta http-equiv="refresh" content="5;url=login.php" />



</body>

</html>