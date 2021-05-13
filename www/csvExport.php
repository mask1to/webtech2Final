<?php

include 'config/config.php';

$testCode = $_GET['testCode'];

$sql = "SELECT a.user_id, u.name, u.surname, ROUND(SUM(a.points), 2) as points FROM answer a
JOIN user u ON u.id = a.user_id
WHERE u.currentTestCode = '$testCode'
GROUP BY a.user_id";

$result = $conn->query($sql);

$users = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=Výsledky_' . $testCode . '.csv');
echo "\xEF\xBB\xBF";
$output = fopen('php://output', 'w');
fputcsv($output, array('ID', 'Meno', 'Priezvisko', 'Body'));

if (count($users) > 0) {
    foreach ($users as $row) {
        fputcsv($output, $row);
    }
}