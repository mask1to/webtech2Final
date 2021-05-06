<?php

include "partials/header.php";

?>

<div class="container theContainer">
    <div class="card"></div>
    <div class="card">
        <h1 class="title">Examify STU | Študent <br> Prihlásenie</h1>
        <form>
            <div class="input-container">
                <input type="text" id="#{label}" required="required" name="studentName"/>
                <label for="#{label}">Meno</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" id="#{label}" required="required" name="studentSurname"/>
                <label for="#{label}">Priezvisko</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" id="#{label}" required="required" name="testCode"/>
                <label for="#{label}">Kód testu</label>
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


