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
                <form class="test-form mt-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="form-group mb-5 questionTitle pr-2 w-75">
                            <label for="testName" class="col-form-label col-form-label-lg">Názov testu</label>
                            <input type="text" class="form-control form-control-lg" id="testName" name="testName" placeholder="Názov">
                        </div>
                        <div class="form-group mb-5 questionTitle pl-2 w-25">
                            <label for="testTime" class="col-form-label col-form-label-lg">Trvanie v minútach</label>
                            <input type="number" class="form-control form-control-lg" id="testTime" name="testTime" placeholder="Trvanie">
                        </div>
                    </div>
                    <p class="totalTime"></p>
                    <button type="submit" class="btn btn-success w-100 btn-lg" id="addTest" name="addTest">Pridať test</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('.totalTime').text("Celkový počet bodov: " + countPoints());

        $(document).on('input', '.points', function() {
            $('.totalTime').text("Celkový počet bodov: " + countPoints());
        })

        $('#checkboxQuestion').on('click', function() {
            checkboxCreate();
        });

        $(document).on('click', '.correctAnswer', function() {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            } else {
                $(this).addClass('active');
            }
        });

        $(document).on('click', '.deleteQuestion', function(e) {
            e.preventDefault();
            $(this).parents('.question-container').slideToggle("fast", function() {
                $(this).remove();
            });
        });

        $(document).on('click', '#addAnswer', function(e) {
            e.preventDefault();
            addOption($(this));
        });

        $(document).on('click', '.deleteOption', function() {
            $(this).parents('.form-option').slideToggle("fast", function() {
                $(this).remove();
            })
        })

        $(document).on('click', '#addTest', function(e) {
            e.preventDefault();
            var $name = $('#testName').val(),
                $points = countPoints(),
                $time = $('#testTime').val();
            $.ajax({
                url: "controllers/addTestController.php",
                method: "POST",
                cache: false,
                data: {
                    name: $name,
                    points: $points,
                    time: $time
                },
                success: function(result) {
                    if (result > 0) {
                        addQuestion(result);
                    } else {
                        console.log('dd');
                    }
                }
            });
        })

        function addQuestion(testId) {
            $('.question-container').each(function() {
                var $this = $(this),
                    $type = $this.data('type'),
                    $question = $this.find('.questionInput').val(),
                    $points = $this.find('.points').val();

                $.ajax({
                    url: "controllers/addQuestionController.php",
                    method: "POST",
                    cache: false,
                    data: {
                        testId: testId,
                        question: $question,
                        type: $type,
                        points: $points
                    },
                    success: function() {
                        if(result > 0) {

                        }
                    }
                });
            })
        }

        function checkboxCreate() {
            var question = ($('<div class="form-group question-container" data-type="checkbox" style="display: none">' +
                '<div class="d-flex align-items-center justify-content-between">' +
                '<input type="number" class="form-control w-25 points" name="points" placeholder="Počet bodov" required>' +
                '<a href="#" class="d-inline-block deleteQuestion"><i class="bi bi-x-circle-fill"></i></a></div>' +
                '<label class="d-block col-form-label col-form-label-lg">Znenie otázky</label>' +
                '<input type="text" class="form-control form-control-lg mb-4 questionInput" name="questionTitle" placeholder="Otázka" required>' +
                '<button id="addAnswer" class="btn btn-secondary">Pridať možnosť</button>' +
                '</div>'));
            question.insertBefore('#addTest').slideDown("fast");
        }

        function addOption(btn) {
            var option = ($('<div class="form-group form-option" style="display: none">' +
                '<label>Možnosť</label>' +
                '<div class="d-flex align-items-center align-items-center">' +
                '<input type="text" class="form-control answer">' +
                '<i class="bi bi-check-circle-fill correctAnswer"></i>' +
                '<i class="bi bi-x-circle-fill deleteOption"></i>' +
                '</div>' +
                '</div>'));
            option.insertBefore(btn).slideDown("fast");
        }

        function countPoints() {
            var points = 0;

            $('.points').each(function() {
                points += parseInt($(this).val());
            });

            return points;
        }

        function makeid(length) {
            var result = [];
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result.push(characters.charAt(Math.floor(Math.random() *
                    charactersLength)));
            }
            return result.join('');
        }
    })
</script>

<?php
include "partials/footer.php";
?>