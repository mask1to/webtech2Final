<?php
    session_start();
    if(!empty($_SESSION["loggedin"]))
    {
        /*
        echo $_SESSION["firstName"]."<br>";
        echo $_SESSION["id"];
        */
    }
    include "partials/header.php";
    include "config/config.php";

    $link = new mysqli(servername, username, password, database);
    $teacherEmail = $teacherPassword = "";

/*
echo '<div id="showModal2" class="modal fade text-center">
	            <div class="modal-dialog modal-confirm text-center">
		            <div class="modal-content text-center">
			            <div class="modal-header text-center">
				            <div class="icon-box">
					            <i class="bi bi-check2"></i>
				            </div>
				            <h4 class="modal-title text-center">Vynikajúco !</h4>
			            </div>
			        <div class="modal-body text-center">
				        <p class="text-center">Prihlásenie prebehlo úspešne !</p>
			        </div>
			        <div class="modal-footer text-center">
				    <button class="btn btn-success btn-block" id="theButton" data-dismiss="modal">Zavrieť</button>
			        </div>
		        </div>
	           </div>
            </div>';
*/


?>

<main class="pt-4 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-10 col-md-8 offset-sm-1 offset-md-2">
                <div class="test-item d-flex align-items-center justify-content-between">
                    <p class="name">
                        Menotestu
                    </p>
                    <div class="d-flex align-items-center">
                        <a href="addTest.php"><i class="bi bi-plus-circle"></i></a>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
    include "partials/footer.php";
?>