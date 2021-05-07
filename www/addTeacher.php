<?php

include "partials/header.php";
include "config/config.php";
include "queries/queries.php";

$link = new mysqli(servername, username, password, database);

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
				            <div class="icon-box">
					            <i class="bi bi-check2"></i>
				            </div>				
				            <h4 class="modal-title text-center">Vynikajúco !</h4>	
			            </div>
			        <div class="modal-body text-center">
				        <p class="text-center">Registrácia prebehla úspešne.</p>
			        </div>
			        <div class="modal-footer text-center">
				    <a class="btn btn-success btn-block" id="theButton" href="teacher.php">Späť na prihlásenie</a>
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