<?php
include "partials/header.php";

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
				    <a class="btn btn-success btn-block" id="theButtonTeacher" href="teacher.php">Späť na prihlásenie</a>
			        </div>
		        </div>
	           </div>
            </div>';

include "partials/footer.php";