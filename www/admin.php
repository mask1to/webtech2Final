<?php
    session_start();
    if(!empty($_SESSION["loggedin"]))
    {

    }
    include "partials/header.php";
    include "config/config.php";

    $link = new mysqli(servername, username, password, database);
    $teacherEmail = $teacherPassword = "";

?>

<main class="pt-4 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-10 col-md-8 offset-sm-1 offset-md-2">
                <div class="test-item d-flex align-items-center justify-content-between">
                    <p class="name">
                        Menotestu
                    </p>
                    <div class="d-flex align-items-center">
                        <a href="addTest.php"><i class="bi bi-plus-circle"></i></a>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
    include "partials/footer.php";
?>