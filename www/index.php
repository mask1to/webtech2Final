<?php

include "partials/header.php";

?>

<div class="container">
    <div class="card"></div>
    <div class="card">
        <h1 class="title">Examify STU</h1>
        <form>
            <div class="input-container">
                <input type="text" id="#{label}" required="required"/>
                <label for="#{label}">Meno</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" id="#{label}" required="required"/>
                <label for="#{label}">Priezvisko</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" id="#{label}" required="required"/>
                <label for="#{label}">Kód testu</label>
                <div class="bar"></div>
            </div>
            <div class="button-container">
                <button><span>Spustiť test</span></button>
            </div>
            <div class="footer"><a href="#">Ste učiteľ ?</a></div>
        </form>
    </div>
</div>

<?php

include "partials/footer.php";

?>


