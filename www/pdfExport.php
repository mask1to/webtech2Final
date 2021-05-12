<?php

include 'config/config.php';
require_once('vendor/autoload.php');

use Spipu\Html2Pdf\Html2Pdf;

$testCode = $_GET['testCode'];

$sqlUsers = "SELECT * FROM user WHERE currentTestCode = '$testCode'";
$resultUsers = $conn->query($sqlUsers);

if ($resultUsers->num_rows > 0) {
    $html = '<h1 class="mb-4">Export pre test s kódom: ' . $testCode . '</h1>';
    while ($row = $resultUsers->fetch_assoc()) {

        $html .= '<div>
            <h2>' . $row['name'] . ' ' . $row['surname'] . '</h2>';

        $userId = $row['id'];

        $sqlQuestion = "SELECT q.name, q.id, q.type FROM question q
        JOIN answer a ON a.question_id = q.id
        WHERE user_id = '$userId'
        GROUP BY q.id";

        $resultQuestion = $conn->query($sqlQuestion);

        if ($resultQuestion && $resultQuestion->num_rows > 0) {
            while ($rowQuestion = $resultQuestion->fetch_assoc()) {
                $questionId = $rowQuestion['id'];
                $html .= '<div style="border: 1px solid black; padding: 5px; margin-bottom: 5px"><h3 style="margin-bottom: 5px">' . $rowQuestion['name'] . '</h3>';

                $sqlAnswer = "SELECT text, isCorrect FROM answer WHERE user_id = '$userId' AND question_id = '$questionId'";
                $resultAnswer = $conn->query($sqlAnswer);

                while ($rowAnswer = $resultAnswer->fetch_assoc()) {
                    if ($rowQuestion['type'] == 'checkbox') {
                        if ($rowAnswer['isCorrect'] == 1) {
                            $html .= '<p style="margin-bottom: 0; font-size: 20px;color:red">' . $rowAnswer['text'] . '</p>';
                        } else {
                            $html .= '<p style="margin-bottom: 0; font-size: 20px;">' . $rowAnswer['text'] . '</p>';
                        }
                    } else if ($rowQuestion['type'] == 'short') {
                        $html .= '<p style="margin-bottom: 0; font-size: 20px;">' . $rowAnswer['text'] . '</p>';
                    } else if ($rowQuestion['type'] == 'draw') {
                        /*$path =  $rowAnswer['text'];
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        //$html .= '<img src="' . $rowAnswer['text'] .'">';*/
                    }
                    else {
                        $html .= '<p style="margin-bottom: 0; font-size: 20px;">' . $rowAnswer['text'] . '</p>';
                    }
                }
                $html .= '</div>';
            }
        }
        $html .= '<hr style="margin-top: 10px; margin-bottom: 10px"></div>';
    }

    $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8');
    $html2pdf->writeHTML($html);
    $html2pdf->output();
}
