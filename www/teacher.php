<?php

session_start();
if(isset($_SESSION["loggedin"]))
{
    header("location: admin.php");
    exit;
}

include "config/config.php";
include "queries/queries.php";

$link = new mysqli(servername, username, password, database);
$teacherEmail = $teacherPassword = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    /*
     * Registrácia
     */
    if(isset($_POST['registerTeacherBtn']))
    {
        $teacherName = $_POST['teacherName'];
        $teacherSurname = $_POST['teacherSurname'];
        $teacherEmail = $_POST['teacherEmail'];
        $teacherPassword = $_POST['teacherPassword'];
        $userType = 'teacher';

        $teacherPassword = password_hash($teacherPassword, PASSWORD_DEFAULT);

        $result = insertNewTeacher($link, $userType, $teacherName, $teacherSurname, $teacherPassword, $teacherEmail);
        if($result)
        {
            echo '<div id="showModal" class="modal fade text-center">
	            <div class="modal-dialog modal-confirm text-center">
		            <div class="modal-content text-center">
			            <div class="modal-header text-center">
				            <div class="icon-box2">
					            <i class="bi bi-check2"></i>
				            </div>				
				            <h4 class="modal-title text-center">Vynikajúco !</h4>	
			            </div>
			        <div class="modal-body text-center">
				        <p class="text-center">Registrácia prebehla úspešne.</p>
			        </div>
			        <div class="modal-footer text-center">
				    <a class="btn2 btn-success btn-block" id="theButton" href="teacher.php">Späť na prihlásenie</a>
			        </div>
		        </div>
	           </div>
            </div>';
        }
    }

    /*
     * Overenie údajov pri prihlásení
     */

    $teacherEmail = $_POST['loginEmailTeacher'];
    $teacherPassword = $_POST['loginPasswordTeacher'];
    $selectData = $link->query("SELECT id, name, surname, password FROM user WHERE email = '$teacherEmail'");
    $selectedData = mysqli_fetch_assoc($selectData);


    if($selectData->num_rows > 0)
    {
        $dbId = $selectedData['id'];
        $dbPassword = $selectedData['password'];
        $dbName = $selectedData['name'];
        $dbSurname = $selectedData['surname'];

        if(password_verify($teacherPassword, $dbPassword))
        {
            $_SESSION["id"] = $dbId;
            $_SESSION["firstName"] = $dbName;
            $_SESSION["lastName"] = $dbSurname;
            $_SESSION["loggedin"] = true;
            if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
            {
                header("location: admin.php");
                exit;
            }
        }
    }
    else
    {
        echo '<div id="showModal3" class="modal show text-center">
	            <div class="modal-dialog modal-confirm text-center">
		            <div class="modal-content text-center">
			            <div class="modal-header text-center">
				            <div class="icon-box">
					            <i class="bi bi-emoji-dizzy"></i>
				            </div>				
				            <h4 class="modal-title text-center">Neplatné údaje</h4>	
			            </div>
			        <div class="modal-body text-center">
				        <p class="text-center">Zadali ste neplatné prihlasovacie údaje.</p>
			        </div>
			        <div class="modal-footer text-center">
				    <button class="btn btn-success btn-block" id="theButtonTeacher" data-dismiss="modal">Rozumiem</button>
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
        <h1 class="title">Examify STU | Učiteľ <br> Prihlásenie</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-container">
                <input type="email" id="loginEmailTeacher" required="required" name="loginEmailTeacher"/>
                <label for="loginEmailTeacher">E-mail</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="password" id="loginPasswordTeacher" required="required" name="loginPasswordTeacher"/>
                <label for="loginPasswordTeacher">Heslo</label>
                <div class="bar"></div>
            </div>
            <div class="button-container">
                <button name="loginTeacherBtn" type="submit"><span>Prihlásiť sa</span></button>
        </form>
    </div>
    <div class="card alt">
        <div class="toggle"></div>
        <h1 class="title">Examify STU | Učiteľ <br> Registrácia
            <div class="close letsClose"></div>
        </h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-container">
                <input type="text" id="teacherName" required="required" name="teacherName"/>
                <label for="teacherName">Meno</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" id="teacherSurname" required="required" name="teacherSurname"/>
                <label for="teacherSurname">Priezvisko</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="email" id="teacherEmail" required="required" name="teacherEmail"/>
                <label for="teacherEmail">Email</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="password" id="teacherPassword" required="required" name="teacherPassword"/>
                <label for="teacherPassword">Heslo</label>
                <div class="bar"></div>
            </div>
            <div class="button-container">
                <button name="registerTeacherBtn"><span>Registrovať sa</span></button>
            </div>
        </form>
    </div>
</div>

<?php

include "partials/footer.php";

?>
