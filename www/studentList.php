<?php
session_start();
if (!isset($_SESSION["loggedin"])) {
    header("location: index.php");
}
include "partials/header.php";
require_once("config/config.php");

$testId = $_GET['id'];
$testCode = $_GET['testCode'];

?>

<main class="pt-4 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-10 col-md-8 offset-sm-1 offset-md-2">
                <?php

                $sqlTest = "SELECT * FROM test WHERE id='$testId'";

                $rowTest = $conn->query($sqlTest)->fetch_assoc();
                $testCode = $rowTest['test_code'];
                $totalPointsTest = $rowTest['total_points'];

                echo '<h2 class="mb-5">Zoznam študentov pre test ' . $testCode . '</h2>';
                echo '<div class="d-flex align-items-center mb-4">
                <form class="form-inline" method="post" action="pdfExport.php?testCode=' .$testCode. '">
                    <button type="submit" id="pdf" name="generate_pdf" class="btn btn-primary"><i class="fa fa-pdf" aria-hidden=" true"></i>
                        Export pdf</button>
                </form>
                <a href="csvExport.php?testCode=' . $testCode . '" class="btn btn-primary ml-3">Export cvs</a>
            </div>';

                $sqlUsers = "SELECT * FROM user WHERE currentTestCode = '$testCode'";
                $sqlPoints = "SELECT COUNT(a.question_id) as count, q.total_points, a.question_id FROM answer a
                JOIN user u ON u.id = a.user_id
                JOIN question q ON q.id = a.question_id
                WHERE q.type = 'checkbox'
                GROUP BY a.question_id";
                $resultUsers = $conn->query($sqlUsers);
                $resultPoints = $conn->query($sqlPoints);

                while ($row = $resultPoints->fetch_assoc()) {
                    $questionId = $row['question_id'];
                    $totalPoints = $row['total_points'];
                    $count = $row['count'];
                    $pointPerAnswer = $totalPoints / $count;

                    $sqlCheckAnswer = "SELECT a.id, a.question_id, a.text, a.isCorrect as aCorrect, q.isCorrect as qCorrect FROM answer a
                    JOIN questionOption q ON q.question_id = a.question_id
                    WHERE q.name = a.text AND q.question_id = '$questionId'";

                    $resultAnswer = $conn->query($sqlCheckAnswer);

                    while ($rowCorrect = $resultAnswer->fetch_assoc()) {
                        $points = 0;
                        if (strcmp($rowCorrect['aCorrect'], $rowCorrect['qCorrect']) == 0) {
                            $points = $pointPerAnswer;
                        }
                        $id = $rowCorrect['id'];

                        $sqlUpdate = "UPDATE answer SET points = '$points' WHERE id = '$id'";
                        $conn->query($sqlUpdate);
                    }
                }

                if ($resultUsers->num_rows > 0) {
                    while ($rowUser = $resultUsers->fetch_assoc()) {
                        $userId = $rowUser['id'];
                        $sqlSumPoints = "SELECT ROUND(SUM(points), 2) as points FROM answer
                        WHERE user_id = '$userId'
                        GROUP BY user_id";

                        $resultPoints = $conn->query($sqlSumPoints)->fetch_assoc();

                        if (!isset($resultPoints)) {
                            $resultPoints['points'] = '-';
                        }

                        $sqlExclamation = "SELECT * FROM answer a
                        JOIN question q ON a.question_id = q.id
                        WHERE q.type != 'checkbox' AND a.isCorrect = 0 and a.user_id = '$userId'";

                        $resultEx = $conn->query($sqlExclamation);

                        echo '<div class="test-item d-flex align-items-center justify-content-between">
                            <p class="name">' . $rowUser['name'] . ' ' . $rowUser['surname'] . '</p>
                            <div class="d-flex align-items-center">
                                <a href="studentTestResult.php?testId=' . $testId . '&id=' . $userId . '&name=' . $rowUser['name'] . '&surname=' . $rowUser['surname'] . '" class="mr-4 student-test"><i class="bi bi-list-check"></i></a>
                                <p class="points-total">' . $resultPoints['points'] . '/' . $totalPointsTest . '</p>';

                        if ($resultEx->num_rows > 0) {
                            echo '<i class="bi bi-exclamation-circle exclamation ml-4"></i>';
                        }

                        echo '</div>
                        </div>';
                    }
                } else {
                    echo '<div class="test-item d-flex align-items-center justify-content-between">
                    <p class="name">Nikto ešte nepísal test</p>
                    
                </div>';
                }

                ?>
            </div>
        </div>
    </div>

</main>



<?php
include "partials/footer.php";
?>