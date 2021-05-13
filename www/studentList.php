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
                <form class="form-inline" method="post" action="pdfExport.php?testCode=' . $testCode . '">
                    <button type="submit" id="pdf" name="generate_pdf" class="btn btn-primary"><i class="fa fa-pdf" aria-hidden=" true"></i>
                        Export pdf</button>
                </form>
                <a href="csvExport.php?testCode=' . $testCode . '" class="btn btn-primary ml-3">Export cvs</a>
            </div>';

                $sqlUsers = "SELECT * FROM user WHERE currentTestCode = '$testCode' AND isWritingExam = 0";
                $resultUsers = $conn->query($sqlUsers);

                if ($resultUsers->num_rows > 0 && $resultUsers) {
                    while ($rowUser = $resultUsers->fetch_assoc()) {
                        $userId = $rowUser['id'];

                        $sqlAnswerNum = "SELECT * FROM answer WHERE user_id = '$userId'";
                        $resultAnswerNum = $conn->query($sqlAnswerNum);

                        if ($resultAnswerNum->num_rows > 0 && $resultAnswerNum) {
                            $sqlPoints = "SELECT COUNT(a.question_id) as count, q.total_points, a.question_id FROM answer a
                            JOIN user u ON u.id = a.user_id
                            JOIN question q ON q.id = a.question_id
                            WHERE q.type = 'checkbox' AND a.user_id = '$userId'
                            GROUP BY a.question_id";

                            $resultPoints = $conn->query($sqlPoints);

                            while ($row = $resultPoints->fetch_assoc()) {
                                $questionId = $row['question_id'];
                                $totalPoints = $row['total_points'];
                                $count = $row['count'];
                                $pointPerAnswer = $totalPoints / $count;

                                $sqlCheckAnswer = "SELECT a.id, a.question_id, a.text, a.isCorrect as aCorrect, q.isCorrect as qCorrect FROM answer a
                            JOIN questionOption q ON q.question_id = a.question_id
                            WHERE q.name = a.text AND q.question_id = '$questionId' AND a.user_id = '$userId'";

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


                            $sqlSumPoints = "SELECT ROUND(SUM(points), 2) as points FROM answer
                            WHERE user_id = '$userId'
                            GROUP BY user_id";

                            $sqlShortPoints = "SELECT a.id, q.total_points FROM answer a
                            JOIN question q ON q.id = a.question_id
                            JOIN questionOption qo ON qo.question_id = a.question_id
                            WHERE a.text = qo.name AND q.type = 'short' AND a.user_id = '$userId'";

                            $resultShort = $conn->query($sqlShortPoints);

                            if ($resultShort->num_rows > 0) {
                                $rowShort = $resultShort->fetch_assoc();
                                $points = $rowShort['total_points'];
                                $id = $rowShort['id'];
                                $sqlUpdate = "UPDATE answer SET points = '$points' WHERE id = '$id'";
                                $conn->query($sqlUpdate);
                            }

                            $sqlPointsConnect = "SELECT COUNT(a.question_id) as count, q.total_points, a.question_id FROM answer a
                            JOIN user u ON u.id = a.user_id
                            JOIN question q ON q.id = a.question_id
                            WHERE q.type = 'connect'
                            GROUP BY a.question_id";

                            $resultPoints = $conn->query($sqlPointsConnect);

                            while ($row = $resultPoints->fetch_assoc()) {
                                $questionId = $row['question_id'];
                                $totalPoints = $row['total_points'];
                                $count = $row['count'];
                                $pointPerAnswer = $totalPoints / $count;

                                $sqlCheckAnswerConnect = "SELECT a.id FROM answer a
                                JOIN OptionsPair op ON a.question_id = op.question_id
                                WHERE a.user_id = '$userId' AND a.question_id = '$questionId' AND a.question_option_id = op.questionOption_id AND a.text = op.name";

                                $resultAnswer = $conn->query($sqlCheckAnswerConnect);
                                while ($rowCorrect = $resultAnswer->fetch_assoc()) {
                                    $id = $rowCorrect['id'];
                                    $sqlUpdate = "UPDATE answer SET points = '$pointPerAnswer' WHERE id = '$id'";
                                    $conn->query($sqlUpdate);
                                }
                            }

                            $resultPoints = $conn->query($sqlSumPoints)->fetch_assoc();

                            if (!isset($resultPoints)) {
                                $resultPoints['points'] = 0;
                            }

                            $sqlExclamation = "SELECT * FROM answer a
                            JOIN question q ON a.question_id = q.id
                            WHERE q.type != 'checkbox' AND q.type != 'connect' AND q.type != 'short' AND a.isCorrect = 0 and a.user_id = '$userId'";

                            $resultEx = $conn->query($sqlExclamation);

                            echo '<div class="test-item d-flex align-items-center justify-content-between">
                            <p class="name">' . $rowUser['name'] . ' ' . $rowUser['surname'] . '</p>
                            <div class="d-flex align-items-center">
                                <a href="studentTestResult.php?testId=' . $testId . '&id=' . $userId . '&name=' . $rowUser['name'] . '&surname=' . $rowUser['surname'] . '&testCode=' . $testCode . '" class="mr-4 student-test"><i class="bi bi-list-check"></i></a>
                                <p class="points-total">' . $resultPoints['points'] . '/' . $totalPointsTest . '</p>';

                            if ($resultEx->num_rows > 0) {
                                echo '<i class="bi bi-exclamation-circle exclamation ml-4"></i>';
                            }

                            echo '</div>
                        </div>';
                        } else {
                            echo '<div class="test-item d-flex align-items-center justify-content-between">
                                <p class="name">Študent odovzdal prázdny test</p>
                                
                            </div>';
                        }
                    }
                } else {
                    echo '<div class="test-item d-flex align-items-center justify-content-between">
                    <p class="name">Nikto ešte nepísal test</p>
                    
                </div>';
                }

                ?>
                <a href="admin.php" class="btn btn-secondary">Späť na zoznam testov</a>
            </div>
        </div>
    </div>

</main>



<?php
include "partials/footer.php";
?>