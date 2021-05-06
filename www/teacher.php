<?php

include "partials/header.php";

?>

<div class="container theContainer">
    <div class="card"></div>
    <div class="card">
        <h1 class="title">Examify STU | Učiteľ <br> Prihlásenie</h1>
        <form>
            <div class="input-container">
                <input type="email" id="#{label}" required="required" name="loginEmailTeacher"/>
                <label for="#{label}">E-mail</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="password" id="#{label}" required="required" name="loginPasswordTeacher"/>
                <label for="#{label}">Heslo</label>
                <div class="bar"></div>
            </div>
            <div class="button-container">
                <button name="loginTeacherBtn"><span>Prihlásiť sa</span></button>
        </form>
    </div>
    <div class="card alt">
        <div class="toggle"></div>
        <h1 class="title">Examify STU | Učiteľ <br> Registrácia
            <div class="close letsClose"></div>
        </h1>
        <form>
            <div class="input-container">
                <input type="text" id="#{label}" required="required" name="teacherName"/>
                <label for="#{label}">Meno</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="text" id="#{label}" required="required" name="teacherSurname"/>
                <label for="#{label}">Priezvisko</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="email" id="#{label}" required="required" name="teacherEmail"/>
                <label for="#{label}">Email</label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="password" id="#{label}" required="required" name="teacherPassword"/>
                <label for="#{label}">Heslo</label>
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
