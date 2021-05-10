<?php

session_start();
if(isset($_SESSION["student"]))
{
    header("location: student.php");
    exit;
} else if(isset($_SESSION["loggedin"]))
{
    header("Location: admin.php");
}

include "config/config.php";
include "queries/queries.php";

$link = new mysqli(servername, username, password, database);

$studentName = $studentSurname = $testCode = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $studentName = $_POST['studentName'];
    $studentSurname = $_POST['studentSurname'];
    $type = "student";
    $testCode = $_POST['testCode'];

    //TODO: osetrit existujuceho usera
    insertNewStudent($link, $type, $studentName, $studentSurname);

    $selectTestCode = $link->query("SELECT test_code FROM test WHERE test_code = '$testCode'");

    if($selectTestCode->num_rows > 0)
    {
        $selectedTestCode = mysqli_fetch_assoc($selectTestCode);
        $dbTestCode = $selectedTestCode['test_code'];

        $_SESSION['studentName'] = $studentName;
        $_SESSION['studentSurname'] = $studentSurname;
        $_SESSION['testCode'] = $testCode;
        $_SESSION['student'] = true;

        if(isset($_SESSION["student"]) && $_SESSION["student"] === true)
        {
            header("location: student.php");
            exit;
        }
    }
    else
    {
        echo '<div id="showModal2" class="modal fade text-center">
	            <div class="modal-dialog modal-confirm text-center">
		            <div class="modal-content text-center">
			            <div class="modal-header text-center">
				            <div class="icon-box">
					            <i class="bi bi-emoji-dizzy"></i>
				            </div>				
				            <h4 class="modal-title text-center">Neplatný kód</h4>	
			            </div>
			        <div class="modal-body text-center">
				        <p class="text-center">Zadali ste kód testu, ktorý neexistuje !</p>
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
        <h1 class="title">Examify STU | Študent <br> Prihlásenie</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-container">
                <input type="text" id="studentName" required="required" name="studentName"/>
                <label for="studentName">Meno</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" id="studentSurname" required="required" name="studentSurname"/>
                <label for="studentSurname">Priezvisko</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" id="testCode" required="required" name="testCode"/>
                <label for="testCode">Kód testu</label>
                <div class="bar"></div>
            </div>
            <div class="button-container">
                <button name="launchTestBtn"><span>Spustiť test</span></button>
            </div>
            <div class="footer"><a href="teacher.php">Ste učiteľ ?</a></div>
        </form>
    </div>
</div>

<?php

include "partials/footer.php";

?>


