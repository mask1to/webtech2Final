<?php
session_start();
include "partials/header.php";
include "queries/queries.php";
include "config/config.php";


$link = new mysqli(servername, username, password, database);


?>

<?php
    $row = mysqli_fetch_assoc(getTestTime($link,$_SESSION['testCode']));
    $time = $row['total_time'];

    $_SESSION['countdown'] = $time;

    $_SESSION['time_started'] = time();

    $now = time();

    $timeSince = $now - $_SESSION['time_started'];

    $remainingSeconds = ($_SESSION['countdown'] - $timeSince);
?>

    <p>HELLO BITCHES</p>

<div id="countdown"></div>
<script type="text/javascript">
    var iTime = <?php echo $remainingSeconds; ?>;
    function countdown()
    {
        var i = setInterval(function(){
            document.getElementById("countdown").innerHTML = iTime;
            if(iTime===0){
                alert('Countdown timer finished!');
                <?php session_destroy();
                header("location:../../index.php");
                ?>
                clearInterval(i);
            } else {
                iTime--;
            }
        },1000);
    }
    countdown();
</script>

<form name="test" method="post">
    Enter time: <input type="text" name="time" />
    <input type="submit" name="submit" value="Submit" />
</form>


<?php

include "partials/footer.php";
?>