<?php
session_start();
if (!isset($_SESSION["loggedin"]))
{
    header("location: index.php");
}
include "partials/header.php";
require_once("config/config.php");

$id = $_GET['id'];
$name = $_GET['name'];
$surname = $_GET['surname'];
$testId = $_GET['testId'];
$testCode = $_GET['testCode'];

?>

<main class="pt-4 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-10 col-md-8 offset-sm-1 offset-md-2">
                <div class="alert alert-danger alert-max" role="alert" style="display: none">
                    Je zadaných viac bodov ako je maximum
                </div>
                <div class="alert alert-danger alert-min" role="alert" style="display: none">
                    Je zadaných menej bodov ako je minimum
                </div>
                <?php

                echo '<a href="studentList.php?id=' . $testId . '&testCode=' . $testCode . '" class="btn btn-secondary mb-5">Späť na zoznam študentov</a><h2 class="mb-4">Odpovede študenta: ' . $name . ' ' . $surname . '</h2>';

                $sqlQuestion = "SELECT ROUND(SUM(a.points), 2) as points, q.name, a.question_id, q.type, q.total_points FROM answer a
                    JOIN question q ON q.id = a.question_id
                    JOIN user u ON u.id = a.user_id
                    WHERE a.user_id = '$id'
                    GROUP BY a.question_id";

                $resultQuestion = $conn->query($sqlQuestion);

                if ($resultQuestion->num_rows > 0)
                {
                    while ($rowQuestion = $resultQuestion->fetch_assoc())
                    {
                        if ($rowQuestion['type'] == 'checkbox')
                        {
                            $questionId = $rowQuestion['question_id'];
                            echo '<div class="form-group question-container" data-type="checkbox" data-id="' . $questionId . '">
                                <div class="form-group">
                                    <label class="mb-0">Je možné získať: ' . $rowQuestion['total_points'] . '</label>
                                </div>
                                <label class="d-block col-form-label col-form-label-lg">' . $rowQuestion['name'] . '</label>';

                            $sqlOptions = "SELECT a.question_id, a.text, a.isCorrect as aCorrect, a.points FROM answer a
                            WHERE a.question_id = '$questionId' AND a.user_id = '$id'";
                            $resultOptions = $conn->query($sqlOptions);
                            while ($rowOption = $resultOptions->fetch_assoc())
                            {
                                echo '<div class="form-group form-check d-flex align-items-center">';
                                if ($rowOption['aCorrect'] == 1)
                                {
                                    echo '<input type="checkbox" class="form-check-input" checked onclick="return false;">';
                                }
                                else {
                                    echo '<input type="checkbox" class="form-check-input" onclick="return false;">';
                                }
                                echo '<label class="form-check-label">' . $rowOption['text'] . '</label>';

                                if (($rowOption['aCorrect'] == 1 && $rowOption['points'] > 0) || ($rowOption['aCorrect'] == 0 && $rowOption['points'] > 0))
                                {
                                    echo '<i class="bi bi-check-circle-fill correctAnswerCheck ml-3"></i>';
                                } else if(($rowOption['aCorrect'] == 1 && $rowOption['points'] == 0)) {
                                    echo '<i class="bi bi-x-circle-fill wrongAnswerCheck ml-3"></i>';
                                }
                                echo '</div>';
                            }
                            echo '<div class="form-group">
                                <label>Získané body</label>
                                <input type="number" class="form-control w-50"placeholder="Počet bodov" value="' . $rowQuestion['points'] . '" readonly>
                            </div>';
                            echo '</div>';
                        }
                        else if ($rowQuestion['type'] == 'short')
                        {
                            $questionId = $rowQuestion['question_id'];
                            $sqlOptions = "SELECT text, points FROM answer WHERE question_id = '$questionId' AND user_id = '$id'";
                            $questionId = $rowQuestion['question_id'];
                            $resultOptions = $conn->query($sqlOptions);
                            $row = $resultOptions->fetch_assoc();
                            echo '<div class="form-group question-container" data-type="short" data-id="' . $questionId . '">
                                <div class="form-group">
                                    <label class="mb-0">Je možné získať: ' . $rowQuestion['total_points'] . '</label>
                                </div>
                                <label class="d-block col-form-label col-form-label-lg">' . $rowQuestion['name'] . '</label>
                                <div class="form-group">
                                    <label>Študentova odpoveď</label>
                                    <input type="text" class="form-control" value="' . $row['text'] . '" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Body na udelenie</label>
                                    <input type="number" class="form-control w-50 points" placeholder="Počet bodov" value="' . $row['points'] . '" min="0" max="' . $rowQuestion['total_points'] . '">
                                </div>
                                </div>';
                        }
                        else if ($rowQuestion['type'] == 'draw')
                        {
                            $questionId = $rowQuestion['question_id'];
                            $sqlOptions = "SELECT text, points FROM answer WHERE question_id = '$questionId' AND user_id = '$id'";
                            $questionId = $rowQuestion['question_id'];
                            $resultOptions = $conn->query($sqlOptions);
                            $row = $resultOptions->fetch_assoc();
                            echo '<div class="form-group question-container" data-type="short" data-id="' . $questionId . '">
                                <div class="form-group">
                                    <label class="mb-0">Je možné získať: ' . $rowQuestion['total_points'] . '</label>
                                </div>
                                <label class="d-block col-form-label col-form-label-lg">' . $rowQuestion['name'] . '</label>
                                <div class="form-group">
                                    <label>Študentova odpoveď</label>
                                    <img src="' . $row['text'] . '" width="100%">
                                </div>
                                <div class="form-group">
                                    <label>Body na udelenie</label>
                                    <input type="number" class="form-control w-50 points" placeholder="Počet bodov" value="' . $row['points'] . '" min="0" max="' . $rowQuestion['total_points'] . '">
                                </div>
                                </div>';
                        }
                        else if ($rowQuestion['type'] == 'math')
                        {
                            $questionId = $rowQuestion['question_id'];
                            $sqlOptions = "SELECT text, points, image_path FROM answer WHERE question_id = '$questionId' AND user_id = '$id'";
                            $questionId = $rowQuestion['question_id'];
                            $resultOptions = $conn->query($sqlOptions);
                            $row = $resultOptions->fetch_assoc();
                            echo '<div class="form-group question-container" data-type="short" data-id="' . $questionId . '">
                                <div class="form-group">
                                    <label class="mb-0">Je možné získať: ' . $rowQuestion['total_points'] . '</label>
                                </div>
                                <math-field disabled>' . $rowQuestion['name'] . '</math-field>
                                <div class="form-group">
                                    <label>Študentova odpoveď</label>';
                            if ($row['text'] == NULL)
                            {
                                echo '<img src="' . $row['image_path'] . '" width="100%">';
                            }
                            else {
                                echo '<math-field disabled>' . $row['text'] . '</math-field>';
                            }
                            echo '</div>
                                <div class="form-group">
                                    <label>Body na udelenie</label>
                                    <input type="number" class="form-control w-50 points" placeholder="Počet bodov" value="' . $row['points'] . '" min="0" max="' . $rowQuestion['total_points'] . '">
                                </div>
                                </div>';
                        }
                        else {
                            $questionId = $rowQuestion['question_id'];
                            echo '<div class="form-group question-container" data-type="short" data-id="' . $questionId . '">
                                <div class="form-group">
                                    <label class="mb-0">Je možné získať: ' . $rowQuestion['total_points'] . '</label>
                                </div>
                                <label class="d-block col-form-label col-form-label-lg">' . $rowQuestion['name'] . '</label>
                                <div class="form-group">
                                    <label>Študentova odpoveď</label>';

                            $sqlConnect = "SELECT qo.name, a.text FROM answer a 
                            JOIN questionOption qo ON qo.question_id = a.question_id
                            WHERE a.question_id = '$questionId' AND a.user_id = '$id' AND a.question_option_id = qo.id";
                            $resultConnect = $conn->query($sqlConnect);

                            while ($rowConnect = $resultConnect->fetch_assoc())
                            {
                                echo '<div class="d-flex align-items-center align-items-center mb-2">
                                <input type="text" class="form-control answer" readonly value="' . $rowConnect['name'] . '" >
                                <input type="text" class="form-control pair" readonly value="' . $rowConnect['text'] . '">
                                </div>';
                            }

                            echo '</div>
                                    <div class="form-group">
                                    <label>Získané body</label>
                                    <input type="number" class="form-control w-50"placeholder="Počet bodov" value="' . $rowQuestion['points'] . '" readonly>
                                </div>
                                </div>';
                        }
                    }
                    echo '<button type="submit" id="btnSend" class="btn btn-primary w-100">Zadať body</button>';
                }

                ?>
            </div>
        </div>
    </div>

</main>

<script src="https://unpkg.com/mathlive/dist/mathlive.min.js"></script>

<script>
    $(document).ready(function()
    {
        $('#btnSend').on('click', function(e)
        {
            e.preventDefault();
            $('.question-container').each(function()
            {
                if ($(this).data('type') === 'checkbox')
                {
                    return;
                }
                $questionId = $(this).data('id');
                $points = $(this).find('.points').val();

                if ($points > $(this).find('.points').attr('max'))
                {
                    $("html, body").animate
                    ({
                        scrollTop: 0
                    }, "slow");

                    $(".alert-max").fadeTo(2000, 500).slideUp(500, function()
                    {
                        $(".alert-max").slideUp(500);
                    })

                    return false;
                }
                else if ($points < $(this).find('.points').attr('min'))
                {
                    $("html, body").animate
                    ({
                        scrollTop: 0
                    }, "slow");

                    $(".alert-min").fadeTo(2000, 500).slideUp(500, function()
                    {
                        $(".alert-min").slideUp(500);
                    })
                    return false;
                }

                $.ajax
                ({
                    url: 'controllers/addPoints.php',
                    method: 'post',
                    data: {
                        questionId: $questionId,
                        userId: <?php echo $id ?>,
                        points: $points
                    },
                    success: function(result)
                    {
                        if (result == 1)
                        {
                            window.location.replace('studentList.php?id=<?php echo $testId ?>&testCode=<?php echo $testCode ?>');
                        }
                    }
                })
            })
        })
    })
</script>



<?php
include "partials/footer.php";
?>