
$(document).ready(function () {
    //window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                if (form.checkValidity() === false) {
                    event.stopPropagation();
                } else {
                    $('.question-container').each(function () {
                        if ($(this).data('type') === 'checkbox' && $(this).find('.form-option').length == 0) {
                            $("html, body").animate({
                                scrollTop: 0
                            }, "slow");
                            $(".alert-option").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-option").slideUp(500);
                            })
                            return;
                        }
                    })
                    addTest();
                }
                form.classList.add('was-validated');
            }, false);
        });
    //}, false);
    $('.totalTime').text("Celkový počet bodov: " + countPoints());

    $(document).on('input', '.points', function () {
        $('.totalTime').text("Celkový počet bodov: " + countPoints());
    })

    $('#checkboxQuestion').on('click', function (e) {
        e.preventDefault();
        checkboxCreate();
        $('.needs-validation').removeClass('was-validated');
    });

    $('#shortanswerQuestion').on('click', function (e) {
        e.preventDefault();
        shortanswerCreate();
        $('.needs-validation').removeClass('was-validated');
    });

    $('#connectQuestion').on('click', function (e) {
        e.preventDefault();
        connectCreate();
        $('.needs-validation').removeClass('was-validated');
    });

    $('#drawQuestion').on('click', function (e) {
        e.preventDefault();
        drawquestionCreate();
        $('.needs-validation').removeClass('was-validated');
    });

    $('#mathQuestion').on('click', function (e) {
        e.preventDefault();
        mathquestionCreate();
        $('.needs-validation').removeClass('was-validated');
    });

    $(document).on('click', '.correctAnswer', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
    });

    $(document).on('click', '.deleteQuestion', function (e) {
        e.preventDefault();
        $(this).parents('.question-container').slideToggle("fast", function () {
            $(this).remove();
            $('.totalTime').text("Celkový počet bodov: " + countPoints());
        });
    });

    $(document).on('click', '#addAnswer', function (e) {
        e.preventDefault();
        addOption($(this));
        $('.needs-validation').removeClass('was-validated');
    });

    $(document).on('click', '.deleteOption', function () {
        $(this).parents('.form-option').slideToggle("fast", function () {
            $(this).remove();
        })
    })

    $(document).on('click', '#addPair', function (e) {
        e.preventDefault();
        addPair($(this));
        $('.needs-validation').removeClass('was-validated');
    });

    $(document).on('click', '.deletePair', function () {
        $(this).parents('.form-option').slideToggle("fast", function () {
            $(this).remove();
        })
    })

    function addTest() {
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
            success: function (result) {
                if (result > 0) {
                    addQuestion(result);
                }
            },
        });
    }

    function addQuestion(testId) {
        if ($('.question-container').length) {
            $('.question-container').each(function () {
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
                    success: function (result) {
                        if (result > 0) {
                            $this.find('.form-option').each(function () {
                                var $this1 = $(this),
                                    $option = $this1.find('.answer').val();
                                if (($this1.find('.correctAnswer').hasClass('active')) || ($this1.find('.answer').hasClass('spravna'))) {
                                    var $correct = 1;
                                } else {
                                    var $correct = 0;
                                }
                                var question_id=result;
                                $.ajax({
                                    url: "controllers/addOptionController.php",
                                    method: "POST",
                                    cache: false,
                                    data: {
                                        questionId: result,
                                        option: $option,
                                        correct: $correct
                                    },
                                    success: function (result) {
                                        if($this1.find('.pair').length) {
                                            $pair = $this1.find('.pair').val();
                                            $.ajax({
                                                url: "controllers/addPairController.php",
                                                method: "POST",
                                                cache: false,
                                                data: {
                                                    questionId: question_id,
                                                    option: $pair,
                                                    questionOptionId: result
                                                },
                                                success: function (resultt) {}
                                            });
                                        }
                                        $("html, body").animate({
                                            scrollTop: 0
                                        }, "slow");
                                        if (result > 0) {
                                            $(".alert-success").fadeTo(2000, 500).slideUp(500, function () {
                                                $(".alert-success").slideUp(500);
                                                window.location.replace('admin.php');
                                            })
                                        } else {
                                            $(".alert-error").fadeTo(2000, 500).slideUp(500, function () {
                                                $(".alert-error").slideUp(500);
                                            });
                                        }
                                    }
                                });
                            })
                        } else {
                            $("html, body").animate({
                                scrollTop: 0
                            }, "slow");
                            $(".alert-error").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-error").slideUp(500);
                            })
                        }
                    }
                });
            });
            alert('lol')
        }
        else {
            $("html, body").animate({
                scrollTop: 0
            }, "slow");
            $(".alert-question").fadeTo(2000, 500).slideUp(500, function () {
                $(".alert-question").slideUp(500);
            })
        }
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

    function shortanswerCreate() {
        var question = ($('<div class="form-group question-container" data-type="short" style="display: none">' +
            '<div class="d-flex align-items-center justify-content-between">' +
            '<input type="number" class="form-control w-25 points" name="points" placeholder="Počet bodov" required>' +
            '<a href="#" class="d-inline-block deleteQuestion"><i class="bi bi-x-circle-fill"></i></a></div>' +
            '<label class="d-block col-form-label col-form-label-lg">Znenie otázky</label>' +
            '<input type="text" class="form-control form-control-lg mb-4 questionInput" name="questionTitle" placeholder="Otázka" required>' +
            '<div class="form-group form-option">' +
            '<label class="d-block col-form-label col-form-label-lg">Správna odpoveď</label>' +
            '<input type="text" class="form-control answer spravna" name="answer" placeholder="Odpoveď" required>' +
            '</div>' +
            '</div>'));
        question.insertBefore('#addTest').slideDown("fast");
    }

    function connectCreate() {
        var question = ($('<div class="form-group question-container" data-type="connect" style="display: none">' +
            '<div class="d-flex align-items-center justify-content-between">' +
            '<input type="number" class="form-control w-25 points" name="points" placeholder="Počet bodov" required>' +
            '<a href="#" class="d-inline-block deleteQuestion"><i class="bi bi-x-circle-fill"></i></a></div>' +
            '<label class="d-block col-form-label col-form-label-lg">Znenie otázky</label>' +
            '<input type="text" class="form-control form-control-lg mb-4 questionInput" name="questionTitle" placeholder="Otázka" required>' +
            '<button id="addPair" class="btn btn-secondary">Pridať dvojice</button>' +
            '</div>'));
        question.insertBefore('#addTest').slideDown("fast");
    }

    function drawquestionCreate() {
        var question = ($('<div class="form-group question-container" data-type="draw" style="display: none">' +
            '<div class="d-flex align-items-center justify-content-between">' +
            '<input type="number" class="form-control w-25 points" name="points" placeholder="Počet bodov" required>' +
            '<a href="#" class="d-inline-block deleteQuestion"><i class="bi bi-x-circle-fill"></i></a></div>' +
            '<label class="d-block col-form-label col-form-label-lg">Znenie otázky</label>' +
            '<input type="text" class="form-control form-control-lg mb-4 questionInput" name="questionTitle" placeholder="Otázka" required>' +
            '</div>'));
        question.insertBefore('#addTest').slideDown("fast");
    }

    function mathquestionCreate() {
        var question = ($('<div class="form-group question-container" data-type="math" style="display: none">' +
            '<div class="d-flex align-items-center justify-content-between">' +
            '<input type="number" class="form-control w-25 points" name="points" placeholder="Počet bodov" required>' +
            '<a href="#" class="d-inline-block deleteQuestion"><i class="bi bi-x-circle-fill"></i></a></div>' +
            '<label class="d-block col-form-label col-form-label-lg">Znenie otázky</label>' +
            '<input type="text" class="form-control form-control-lg mb-4 questionInput" name="questionTitle" placeholder="Otázka" required>' +
            '</div>'));
        question.insertBefore('#addTest').slideDown("fast");
    }

    function addOption(btn) {
        var option = ($('<div class="form-group form-option" style="display: none">' +
            '<label>Možnosť</label>' +
            '<div class="d-flex align-items-center align-items-center">' +
            '<input type="text" class="form-control answer" required>' +
            '<i class="bi bi-check-circle-fill correctAnswer"></i>' +
            '<i class="bi bi-x-circle-fill deleteOption"></i>' +
            '</div>' +
            '</div>'));
        option.insertBefore(btn).slideDown("fast");
    }

    function addPair(btn) {
        var option = ($('<div class="form-group form-option" style="display: none">' +
            '<label>Správne dvojice</label>' +
            '<div class="d-flex align-items-center align-items-center">' +
            '<input type="text" class="form-control answer" required>' +
            '<input type="text" class="form-control pair" required>' +
            '<i class="bi bi-x-circle-fill deletePair"></i>' +
            '</div>' +
            '</div>'));
        option.insertBefore(btn).slideDown("fast");
    }

    function countPoints() {
        var points = 0;

        $('.points').each(function () {
            if ($(this).val() != '') {
                points += parseInt($(this).val());
            }
        });

        return points;
    }

    $(window).scroll(function () {
        if ($(window).scrollTop() >= 100) {
            $('.fixed-element').addClass("fixed");

        } else {
            $('.fixed-element').removeClass("fixed");
        }
    });
})