<?php

include "partials/header.php";
include "config/config.php";

?>

<main class="pt-4 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-10 col-md-8 offset-sm-1 offset-md-2">
                <div class="d-flex align-items-center justify-content-between">
                    <h1 class="mb-3">Vytváranie testu</h1>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Pridať otázku
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" id="checkboxQuestion">Otázka s možnosťami</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <form action="" class="test-form mt-5">
                    <div class="form-group mb-5 questionTitle">
                        <label for="testName" class="col-form-label col-form-label-lg">Názov testu</label>
                        <input type="text" class="form-control form-control-lg" id="testName" name="testName" placeholder="Názov">
                    </div>
                    <button type="submit" class="btn btn-success w-100 btn-lg" id="addTest" name="addTest">Pridať test</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#checkboxQuestion').on('click', function() {
            checkboxCreate();
        });

        function checkboxCreate() {
            var question = ($('<div class="form-group question-container" style="display: none">' +
                '<div class="ml-auto w-100 text-right"><a href="#" id="deleteQuestion" class="d-inline-block"><i class="bi bi-x-circle-fill"></i></a></div>' +
                '<label class="d-block col-form-label col-form-label-lg">Znenie otázky</label>' +
                '<input type="text" class="form-control form-control-lg mb-4" name="questionTitle" placeholder="Otázka">' +
                '<div class="form-group">' +
                '<label>Možnosť</label>' +
                '<div class="d-flex align-items-center align-items-center">' +
                '<input type="text" class="form-control" class="answer">' +
                '<i class="bi bi-check-circle-fill correctAnswer"></i>' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label>Možnosť</label>' +
                '<div class="d-flex align-items-center align-items-center">' +
                '<input type="text" class="form-control" class="answer">' +
                '<i class="bi bi-check-circle-fill correctAnswer"></i>' +
                '</div>' +
                '</div>' +
                '<button id="addAnswer" class="btn btn-secondary">Pridať možnosť</button>' +
                '</div>'));
            question.insertBefore('#addTest').slideDown("fast");
        }

        $(document).on('click', '.correctAnswer', function() {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            } else {
                $(this).addClass('active');
            }
        });

        $(document).on('click', '#deleteQuestion', function(e) {
            e.preventDefault();
            $(this).parents('.question-container').slideToggle("fast", function() {
                $(this).remove();
            });
        });

        $(document).on('click', '#addAnswer', function(e) {
            e.preventDefault();
            var option = ($('<div class="form-group">' +
                '<label>Možnosť</label>' +
                '<div class="d-flex align-items-center align-items-center">' +
                '<input type="text" class="form-control" class="answer">' +
                '<i class="bi bi-check-circle-fill correctAnswer"></i>' +
                '</div>' +
                '</div>'));
            option.insertBefore($(this)).slideDown("fast");
        });
    })
</script>

<?php
include "partials/footer.php";
?>