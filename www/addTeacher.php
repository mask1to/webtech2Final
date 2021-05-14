<?php

include "partials/header.php";
require_once("config/config.php");
include "queries/queries.php";

$link = $conn;

if(isset($_POST['registerTeacherBtn']))
{
    $teacherName = $_POST['teacherName'];
    $teacherSurname = $_POST['teacherSurname'];
    $teacherEmail = $_POST['teacherEmail'];
    $teacherPassword = $_POST['teacherPassword'];
    $userType = 'teacher';

    $pattern = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';
    if(preg_match($pattern, $teacherPassword))
    {
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
    else
    {
        echo '<div id="showModal5" class="modal fade text-center">
	            <div class="modal-dialog modal-confirm text-center">
		            <div class="modal-content text-center">
			            <div class="modal-header text-center">
				            <div class="icon-box">
					            <i class="bi bi-emoji-dizzy"></i>
				            </div>				
				            <h4 class="modal-title text-center">Nesplnené podmienky pre heslo!</h4>	
			            </div>
			        <div class="modal-body text-center">
				        <p class="text-center">Podmienky hesla pre <br> úspešnú registráciu:</p>
				        <ul class="text-center">
				              <li>Minimálna dĺžka - 8 znakov</li>
				              <li>Maximálna dĺžka - 20 znakov</li>
				              <li>Obsahuje aspoň 1 veľké písmeno</li>
				              <li>Obsahuje aspoň 1 špeciálny znak</li>
				              <li>Obsahuje aspoň 1 číslo</li>
                        </ul>
			        </div>
			        <div class="modal-footer text-center">
				    <a class="btn btn-success btn-block" id="theButton" href="teacher.php">Späť na registráciu</a>
			        </div>
		        </div>
	           </div>
            </div>';
    }

}
?>

<?php

include "partials/footer.php";

?>