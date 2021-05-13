<?php
session_start();
if (!isset($_SESSION["loggedin"])) {
    header("location: index.php");
}
include "partials/header.php";
require_once("config/config.php");
?>
<main class="pt-4 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-10 col-md-8 offset-sm-1 offset-md-2">
<?php
$testId = $_GET['id'];
$testCode = $_GET['testCode'];
echo '<h2 class="mb-5">Zoznam študentov ktorý ešte píšu test s kódom ' . $testCode . '</h2>';
$writingStudents = $conn->query("SELECT * FROM user WHERE currentTestCode = '$testCode' AND type='student' and isWritingExam='1'");
if ($writingStudents->num_rows === 0) {
    echo '<div class="test-item d-flex align-items-center justify-content-between">
                    <p class="name">Nikto už nepíše test</p>
                    
                </div>';
}
else{
    while ($student = $writingStudents->fetch_assoc()) {
        echo '<div class="test-item d-flex align-items-center justify-content-between">
                            <p class="name">' . $student['name'] . ' ' . $student['surname'] . '</p></div>';
    }
}
?>
            </div>
        </div>
    </div>

</main>

<?php
include "partials/footer.php";
?>
