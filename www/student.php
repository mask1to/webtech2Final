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
        function checkCookie()
        {
            var f = getCookie("timerMinutes");
            var g = getCookie("timerSeconds");
            return f !== null && g !== null;
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


        var iTimeMinutes = <?php echo $time; ?>;
        var iTimeSeconds = iTimeMinutes % 60;

        if(checkCookie('timerMinutes') && checkCookie("timerSeconds"))
        {
            iTimeMinutes = getCookie('timerMinutes');
            iTimeSeconds = getCookie('timerSeconds');
        }

        function countdown()
        {
            console.log("typeMinuty1: ", typeof iTimeMinutes);
            console.log("typeSekundy1: ", typeof iTimeSeconds);

            console.log("Minuty: ", iTimeMinutes);
            console.log("Sekundy: ", iTimeSeconds);

            var i = setInterval(function()
            {
                document.cookie = "timerMinutes=" + encodeURIComponent(iTimeMinutes);
                document.cookie = "timerSeconds=" + encodeURIComponent(iTimeSeconds);
                console.log("typeMinuty1: ", typeof iTimeMinutes);
                console.log("typeSekundy1: ", typeof iTimeSeconds);
                if(iTimeMinutes < 10 && iTimeSeconds < 10)
                {
                    document.getElementById("fixedTimer").innerHTML = "Zostávajúci čas: " + "0" + iTimeMinutes + ":" + "0" + iTimeSeconds;
                }
                else
                {
                    if(iTimeMinutes < 10)
                    {
                        document.getElementById("fixedTimer").innerHTML = "Zostávajúci čas: " + "0" + iTimeMinutes + ":" + iTimeSeconds;
                    }
                    else if(iTimeSeconds < 10)
                    {
                        document.getElementById("fixedTimer").innerHTML = "Zostávajúci čas: " + iTimeMinutes + ":" + "0" + iTimeSeconds;
                    }
                    else
                    {
                        document.getElementById("fixedTimer").innerHTML = "Zostávajúci čas: " + iTimeMinutes + ":" + iTimeSeconds;
                    }

                }

                if(iTimeMinutes === 0 && iTimeSeconds === 0)
                {
                    alert('Cas na test vyprsal!');
                    delete_cookie('timerMinutes');
                    delete_cookie('timerSeconds');
                    clearInterval(i);
                    location.reload();
                }
                else
                {
                    if(iTimeSeconds === 0)
                    {
                        iTimeMinutes--;
                        iTimeSeconds = 60;
                    }
                    iTimeSeconds--;

                }
            },1000);
        }

        countdown();
        <?php session_destroy(); ?>
    </script>

    <div id="fixedTimer" class="fancy"></div>


<?php

include "partials/footer.php";
?>