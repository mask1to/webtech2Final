<?php

session_start();
if (!isset($_SESSION["loggedin"])) {
    header("location: index.php");
}

include "partials/header.php";
include "config/config.php";

?>

<main class="pt-4 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-10 col-md-8 offset-sm-1 offset-md-2">
                <div class="alert alert-success" role="alert" style="display: none">
                    Nahratie testu prebehlo úspešne!
                </div>
                <div class="alert alert-danger alert-error" role="alert" style="display: none">
                    Nahratie testu neprebehlo úspešne!
                </div>
                <div class="alert alert-danger alert-question" role="alert" style="display: none">
                    Je potrebná otázka
                </div>
                <div class="alert alert-danger alert-option" role="alert" style="display: none">
                    Je potrebné zadať možnosti
                </div>
                <div class="fixed-element">
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="mb-0">Vytváranie testu</h1>
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Pridať otázku
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#" id="checkboxQuestion">Otázka s možnosťami</a>
                                <a class="dropdown-item" href="#" id="shortanswerQuestion">Otázka s krátkou odpoveďou</a>
                                <a class="dropdown-item" href="#" id="drawQuestion">Otázka s nakreslením obrázku</a>
                                <a class="dropdown-item" href="#" id="mathQuestion">Otázka s napísaním matematického výrazu</a>
                            </div>
                        </div>
                    </div>
                </div>
                <form class="test-form mt-5 needs-validation" novalidate>
                    <div class="form-row mb-5">
                        <div class="col-12 col-sm-7 col-md-8 col-lg-9 d-flex flex-column">
                            <label for="testName" class="col-form-label col-form-label-lg">Názov testu</label>
                            <input type="text" class="form-control form-control-lg mt-auto" id="testName" name="testName" placeholder="Názov" required>
                        </div>
                        <div class="col-12 col-sm-5 col-md-4 col-lg-3 d-flex flex-column">
                            <label for="testTime" class="col-form-label col-form-label-lg">Trvanie v minútach</label>
                            <input type="number" class="form-control form-control-lg mt-auto" id="testTime" name="testTime" placeholder="Trvanie" required>
                        </div>
                    </div>
                    <p class="totalTime"></p>
                    <button type="submit" class="btn btn-success w-100 btn-lg" id="addTest" name="addTest">Pridať test</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script src="assets/js/addTest.js"></script>

<?php
include "partials/footer.php";
?>