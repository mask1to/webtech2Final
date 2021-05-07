<?php

include "partials/header.php";

?>

<div class="container theContainer">
    <div class="card"></div>
    <div class="card">
        <h1 class="title">Examify STU | Študent <br> Prihlásenie</h1>
        <form>
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


