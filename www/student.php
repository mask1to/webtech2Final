<?php
session_start();

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
    function checkCookie() {
        // Get cookie using our custom function
        var f = getCookie("timer");

        return f !== null;
    }

    function getCookie(name) {
        // Split cookie string and get all individual name=value pairs in an array
        var cookieArr = document.cookie.split(";");

        // Loop through the array elements
        for(var i = 0; i < cookieArr.length; i++) {
            var cookiePair = cookieArr[i].split("=");

            /* Removing whitespace at the beginning of the cookie name
            and compare it with the given string */
            if(name === cookiePair[0].trim()) {
                // Decode the cookie value and return
                return decodeURIComponent(cookiePair[1]);
            }
        }

        // Return null if not found
        return null;
    }

    var delete_cookie = function(name) {
        document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    };




    function createCookie(name, value) {
        var date = new Date();
        date.setTime(date.getTime() + (30*1000));
        var expires = "; expires= " + date.toGMTString();

        document.cookie = name + "=" + value + expires + "; path=/";
    }


    var iTime = <?php echo $time; ?>;
    if(checkCookie('timer')){
        var iTime = getCookie('timer');
    }

    function countdown()
    {
        //var inTestTimeExpire = new Date(new Date().getTime() +  <?php echo $time ?> * 1000);
        var i = setInterval(function(){
            document.cookie = "timer=" + encodeURIComponent(iTime);
            document.getElementById("fixedTimer").innerHTML = "Zostávajúci čas: " + iTime;
            if(iTime===0){
                alert('Cas na test vyprsal!');
                delete_cookie('timer');
                clearInterval(i);
                location.reload();
            } else {
                iTime--;
            }
        },60000);
    }
    countdown();
    <?php session_destroy(); ?>
</script>

    <div id="fixedTimer" class="fancy"></div>


<?php

include "partials/footer.php";
?>