<?php
if (!isset($_SESSION)) {
    session_start();
}

if(isset($_POST['logOut'])) {
    unset($_SESSION["id"]);
    unset($_SESSION["firstName"]);
    unset($_SESSION["lastName"]);
    unset($_SESSION["loggedin"]);

    session_destroy();

    header("Location: index.php");
}

?>
<!doctype html>
<html lang="sk">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/styles.css">

    <title>Finálne zadanie</title>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">Examify</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto align-items-center">
                    <?php

                    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                        echo '<li class="nav-item mr-3 welcome">
                            Vitaj ' . $_SESSION['firstName'] . '
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="">
                                <button type="submit" name="logOut" class="btn btn-danger">Odhlásiť sa</button>
                            </form>
                        </li>';
                    }
                    else if(isset($_SESSION["student"]) && $_SESSION["student"] === true)
                    {
                        echo '<li class="nav-item mr-3 welcome">
                            Vitaj ' . $_SESSION['studentName'] . '
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="">
                                <button type="submit" name="logOut" class="btn btn-danger">Odhlásiť sa</button>
                            </form>
                        </li>';
                    }
                    else {
                        echo '<li class="nav-item">
                            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>';
                    }
                    ?>
                </ul>
            </div>
        </nav>
    </header>