<?php
session_start();

if (isset($_SESSION["student"])) {
    header("location: student.php");
    exit;
} else if (isset($_SESSION["loggedin"])) {
    header("Location: admin.php");
}

require_once("config/config.php");
include "queries/queries.php";

$link = $conn;

$studentName = $studentSurname = $testCode = "";
$isWriting = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentName = $_POST['studentName'];
    $studentSurname = $_POST['studentSurname'];
    $type = "student";
    $testCode = $_POST['testCode'];

    $selectTestCode = $link->query("SELECT test_code, isActive FROM test WHERE test_code = '$testCode'");

    $selectUserStatus = $link->query(
        "SELECT id, name, surname, isWritingExam, currentTestCode 
            FROM user WHERE name = '$studentName' AND 
                            surname = '$studentSurname'
                            AND type = 'student'
                          "
    );
    $selectedUserStatus = mysqli_fetch_assoc($selectUserStatus);
    if ($selectTestCode) {
        if ($selectTestCode->num_rows > 0) {
            $selectedTestCode = mysqli_fetch_assoc($selectTestCode);
            if (isset($selectedUserStatus['currentTestCode'])) {
                $dbTestCode = $selectedUserStatus['currentTestCode'];
            }

            if ($selectedTestCode['isActive'] == 1) {
                if (isset($selectedUserStatus['isWritingExam'])) {
                    $isWriting = $selectedUserStatus['isWritingExam'];
                }
                if ($isWriting == null) {
                    $_SESSION['studentName'] = $studentName;
                    $_SESSION['studentSurname'] = $studentSurname;
                    $isWriting = 1;
                    insertNewStudent($link, $type, $studentName, $studentSurname, $isWriting, $testCode);
                    $_SESSION['student'] = true;
                    if (isset($_SESSION["student"]) && $_SESSION["student"] === true) {
                        $_SESSION['testCode'] = $testCode;
                        header("location: student.php");
                        exit;
                    }
                } else if ($isWriting == 0 && !strcmp($testCode, $dbTestCode)) {
                    if (isset($_POST['launchTestBtn'])) {
                        echo '<div id="showModal9" class="modal fade text-center">
                        <div class="modal-dialog modal-confirm text-center">
                            <div class="modal-content text-center">
                                <div class="modal-header text-center">
                                    <div class="icon-box">
                                        <i class="bi bi-emoji-dizzy"></i>
                                    </div>				
                                    <h4 class="modal-title text-center">Nastala chyba !</h4>	
                                </div>
                            <div class="modal-body text-center">
                                <p class="text-center">Zadan?? test ste u?? p??sali.<br>Nie je mo??n?? ho p??sa?? znovu.</p>
                            </div>
                            <div class="modal-footer text-center">
                            <button class="btn btn-success btn-block" id="theButton" data-dismiss="modal">Rozumiem</button>
                            </div>
                        </div>
                       </div>
                     </div>';
                    }
                } else if ($isWriting == 1 && !strcmp($testCode, $dbTestCode)) {
                    $_SESSION['studentName'] = $studentName;
                    $_SESSION['studentSurname'] = $studentSurname;
                    $_SESSION['student'] = true;
                    $_SESSION['testCode'] = $testCode;
                    header("location: student.php");
                    exit;
                } else {
                    echo '<div id="showModal6" class="modal fade text-center">
	            <div class="modal-dialog modal-confirm text-center">
		            <div class="modal-content text-center">
			            <div class="modal-header text-center">
				            <div class="icon-box">
					            <i class="bi bi-emoji-dizzy"></i>
				            </div>				
				            <h4 class="modal-title text-center">Nastala chyba !</h4>	
			            </div>
			        <div class="modal-body text-center">
				        <p class="text-center">U?? p????ete jeden test ! <br> Mus??te ho dokon??i?? a potom m????ete spusti?? ??al????.</p>
			        </div>
			        <div class="modal-footer text-center">
				    <button class="btn btn-success btn-block" id="theButton" data-dismiss="modal">Rozumiem</button>
			        </div>
		        </div>
	           </div>
            </div>';
                }
            } else {
                echo '<div id="showModal4" class="modal fade text-center">
	            <div class="modal-dialog modal-confirm text-center">
		            <div class="modal-content text-center">
			            <div class="modal-header text-center">
				            <div class="icon-box">
					            <i class="bi bi-emoji-dizzy"></i>
				            </div>				
				            <h4 class="modal-title text-center">Nespusten?? test</h4>	
			            </div>
			        <div class="modal-body text-center">
				        <p class="text-center">Test u?? bol vytvoren??, ale e??te nebol spusten?? u??ite??om. Pros??m po??kajte a sk??ste nesk??r.</p>
			        </div>
			        <div class="modal-footer text-center">
				    <button class="btn btn-success btn-block" id="theButton" data-dismiss="modal">Rozumiem</button>
			        </div>
		        </div>
	           </div>
            </div>';
            }
        }
    } else {
        echo '<div id="showModal2" class="modal fade text-center">
	            <div class="modal-dialog modal-confirm text-center">
		            <div class="modal-content text-center">
			            <div class="modal-header text-center">
				            <div class="icon-box">
					            <i class="bi bi-emoji-dizzy"></i>
				            </div>				
				            <h4 class="modal-title text-center">Neplatn?? k??d</h4>	
			            </div>
			        <div class="modal-body text-center">
				        <p class="text-center">Zadali ste k??d testu, ktor?? neexistuje !</p>
			        </div>
			        <div class="modal-footer text-center">
				    <button class="btn btn-success btn-block" id="theButton" data-dismiss="modal">Rozumiem</button>
			        </div>
		        </div>
	           </div>
            </div>';
    }
}

include "partials/header.php";

?>

<div class="container theContainer">
    <div class="card"></div>
    <div class="card">
        <h1 class="title">Examify STU | ??tudent <br> Prihl??senie</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-container">
                <input type="text" id="studentName" required="required" name="studentName" />
                <label for="studentName">Meno</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" id="studentSurname" required="required" name="studentSurname" />
                <label for="studentSurname">Priezvisko</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" id="testCode" required="required" name="testCode" />
                <label for="testCode">K??d testu</label>
                <div class="bar"></div>
            </div>
            <div class="button-container">
                <button name="launchTestBtn"><span class="btnRight">Spusti?? test</span></button>
            </div>
            <div class="footer"><a href="teacher.php" class="redirectRight">Ste u??ite?? ?</a></div>
        </form>
    </div>
</div>

<script>
    $.ajax({
        type: "post",
        url: "index.php",
        success: function() {
            $('#showModal9').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
        }
    })
</script>

<?php

include "partials/footer.php";

?>