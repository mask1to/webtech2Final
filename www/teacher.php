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
     * Overenie údajov pri prihlásení
     */

    $teacherEmail = $_POST['loginEmailTeacher'];
    $teacherPassword = $_POST['loginPasswordTeacher'];
    $selectData = $link->query("SELECT id, name, surname, password, email FROM user WHERE email = '$teacherEmail'");
    $selectedData = mysqli_fetch_assoc($selectData);


    if($selectData->num_rows > 0)
    {
        $dbId = $selectedData['id'];
        $dbPassword = $selectedData['password'];
        $dbName = $selectedData['name'];
        $dbSurname = $selectedData['surname'];
        $dbMail = $selectedData['email'];

        if($dbMail == $teacherEmail && !password_verify($teacherPassword, $dbPassword))
        {
            header("location: badLoginTeacher.php");
        }

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
    else if(isset($_POST['loginTeacherBtn']) && $selectData->num_rows == 0)
    {
        header("location: badLoginTeacher.php");
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
        <form action="addTeacher.php" method="post">
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
