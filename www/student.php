<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['testCode'])){
    header("location: index.php");
}

include "partials/header.php";
include "queries/queries.php";
include "config/config.php";

$link = new mysqli(servername, username, password, database);

$row = mysqli_fetch_assoc(getTestTime($link,$_SESSION['testCode']));
$time = $row['total_time'];

?>

<script type="text/javascript">
    var iTime = <?php echo $time; ?>;
    function countdown()
    {
        var i = setInterval(function(){
            document.getElementById("fixedTimer").innerHTML = "Zostávajúci čas: " + iTime;
            if(iTime===0){
                alert('Cas na test vyprsal!');
                clearInterval(i);
                <?php
                session_destroy(); ?>
                location.reload();
            } else {
                iTime--;
            }
        },1000);
    }
    countdown();
</script>

    <div id="fixedTimer" class="fancy"></div>


<?php

include "partials/footer.php";
?>