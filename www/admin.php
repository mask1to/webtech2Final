<?php
session_start();
if (!isset($_SESSION["loggedin"]))
{
    header("location: index.php");
}
include "partials/header.php";
require_once("config/config.php");
?>

<main class="pt-4 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-10 col-md-8 offset-sm-1 offset-md-2">
                <div class="w-100 mb-4 text-right addTestBtn">
                    <a href="addTest.php" class="btn btn-primary d-inline-block">Pridať test</a>
                </div>
                <?php
                $id = $_SESSION['id'];
                $sql = "SELECT * FROM test WHERE user_id = '$id'";

                $result = $conn->query($sql);

                if ($result->num_rows > 0)
                {
                    while ($row = $result->fetch_assoc())
                    {
                        echo '<div class="test-item d-flex align-items-center justify-content-between" data-id="' . $row['id'] . '">
                        <div>
                            <p class="name">' . $row['name'] . '</p>
                            <p class="code mb-0">Kód: ' . $row['test_code'] . '</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <label class="switch">' . ($row['isActive'] ? ('<input type="checkbox" checked>') : ('<input type="checkbox">')) . '<span class="slider round"></span>
                            </label>
                            <a href="WritingStudents.php?id=' . $row['id'] . '&testCode=' .$row['test_code'] .'" class="ml-4 student-list"><i class="bi bi-pencil"></i></a>
                            <a href="studentList.php?id=' . $row['id'] . '&testCode=' .$row['test_code'] .'" class="ml-4 mr-4 student-list"><i class="bi bi-person-lines-fill"></i></a>
                            <a href="#" class="delete"><i class="bi bi-trash-fill"></i></a>
                        </div>
                    </div>';
                    }
                }
                else {
                    echo '<div class="test-item d-flex align-items-center justify-content-between">
                        <p class="name">Nie je vložený žiadny test</p>
                    </div>';
                }
                ?>
            </div>
        </div>
    </div>
</main>

<script src="assets/js/admin.js"></script>

<?php
include "partials/footer.php";
?>