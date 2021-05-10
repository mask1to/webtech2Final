<?php
session_start();

if(!isset($_SESSION['testCode'])){
    header("location: index.php");
}

include "partials/header.php";
include "queries/queries.php";
include "config/config.php";

$sessionTestCode = $_SESSION['testCode'];

$link = new mysqli(servername, username, password, database);
$row = mysqli_fetch_assoc(getTestTime($link,$_SESSION['testCode']));
$time = $row['total_time'];

$selectTestCode = $link->query("SELECT id, test_code, total_points FROM test WHERE test_code = '$sessionTestCode'");
$selectedData = mysqli_fetch_assoc($selectTestCode);

//test id for entered test code
$testId = $selectedData['id'];


$selectTypeOfQuestion = $link->query("SELECT * FROM question WHERE test_id = $testId");

if($sessionTestCode == $selectedData['test_code'])
{
    echo '<div class="wrapper bg-white rounded">
            <div class="content">
            <p class="text-muted"><b>Kód testu: '.$sessionTestCode. '</b></p>
            <p class="text-muted"><b>Počet bodov v teste: '.$selectedData["total_points"].'</b></p>
            <hr>';

    while($questions = $selectTypeOfQuestion->fetch_assoc())
    {
        $questionId = $questions['id'];

        $selectOptions = $link->query("SELECT * FROM questionOption WHERE question_id = '$questionId'");
        if($questions['type'] == 'checkbox')
        {
            echo '<p class="text-muted"><b>Otázka s možnosťami</b></p>
                   <p class="text-muted""><b>Body: '.$questions['total_points'].'</b></p>
                   <p class="text-justify h5 pb-2 font-weight-bold">'.$questions['name'].'</p>';
            while($option = $selectOptions->fetch_assoc())
            {
                if($option['question_id'] == $questionId)
                {
                    echo '<div class="options py-3"> 
                     <label class="rounded p-2 option"> '.$option['name'].'
                     <input type="checkbox" name="radio">
                     <span class="crossmark"></span>
                     </label>
                     ';
                    echo '</div>';
                }

            }
            echo '<hr>';
        }
        if($questions['type'] == 'short')
        {
            echo '<p class="text-muted"><b>Otázka s krátkou odpoveďou</b></p>
                   <p class="text-muted""><b>Body: '.$questions['total_points'].'</b></p>
                   <p class="text-justify h5 pb-2 font-weight-bold">'.$questions['name'].'</p>';
            while($option = $selectOptions->fetch_assoc())
            {
                if($option['question_id'] == $questionId)
                {
//                    <label class="rounded p-2 option"> '.$option['name'].'
//                    <span class="crossmark"></span>
                    echo '<div class="options py-3">                    
                     <input type="text">   
                     </label>
                     ';
                    echo '</div>';
                }

            }
            echo '<hr>';
        }

        if($questions['type'] == 'draw')
        {
            echo '<p class="text-muted"><b>Otázka s nakreslením obrázku</b></p>
                   <p class="text-muted""><b>Body: '.$questions['total_points'].'</b></p>
                   <p class="text-justify h5 pb-2 font-weight-bold">'.$questions['name'].'</p>';

            ?>
            <p class="demoToolList"><button onclick="c(clickX,clickY,clickDrag);" id="clearCanvasSimple" type="button">Odznovu</button></p>
            <div id="canvasDiv"></div>
            <script>
                build_canvas();

                var clickX = new Array();
                var clickY = new Array();
                var clickDrag = new Array();
                var paint;

                function build_canvas() {
                    var canvasDiv = document.getElementById('canvasDiv');
                    canvas = document.createElement('canvas');
                    canvas.setAttribute('width', 490);
                    canvas.setAttribute('height', 220);
                    canvas.setAttribute('id', 'canvas');
                    canvas.style = "border:thin solid black";
                    canvasDiv.appendChild(canvas);
                    if(typeof G_vmlCanvasManager != 'undefined') {
                        canvas = G_vmlCanvasManager.initElement(canvas);
                    }
                    context = canvas.getContext("2d");
                }

                function c() {
                    context.clearRect(0, 0, canvas.width, canvas.height);
                    context.closePath();

                    clickX = new Array();
                    clickY = new Array();
                    clickDrag = new Array();
                }

                $('#canvas').mousedown(function(e){
                    var mouseX = e.pageX - this.offsetLeft;
                    var mouseY = e.pageY - this.offsetTop;

                    paint = true;
                    addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop);
                    redraw();
                });

                $('#canvas').mousemove(function(e){
                    if(paint){
                        addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, true);
                        redraw();
                    }
                });

                $('#canvas').mouseup(function(e){
                    paint = false;
                });

                $('#canvas').mouseleave(function(e){
                    paint = false;
                });



                function addClick(x, y, dragging)
                {
                    clickX.push(x);
                    clickY.push(y);
                    clickDrag.push(dragging);
                }

                function redraw(){
                    context.clearRect(0, 0, context.canvas.width, context.canvas.height); // Clears the canvas

                    context.strokeStyle = "#df4b26";
                    context.lineJoin = "round";
                    context.lineWidth = 5;

                    for(var i=0; i < clickX.length; i++) {
                        context.beginPath();
                        if(clickDrag[i] && i){
                            context.moveTo(clickX[i-1], clickY[i-1]);
                        }else{
                            context.moveTo(clickX[i]-1, clickY[i]);
                        }
                        context.lineTo(clickX[i], clickY[i]);
                        context.closePath();
                        context.stroke();
                    }
                }
            </script>

            <?php

            echo '<hr>';
        }
        if($questions['type'] == 'math')
        {
            echo '<p class="text-muted"><b>Otázka s matematickou odpoveďou</b></p>
                   <p class="text-muted""><b>Body: '.$questions['total_points'].'</b></p>  
                      <math-field disabled>'. $questions['name'] .'</math-field>
                   <div id="mathfield" style="max-height: 40px">'.  $questions['name'].' </div> 
                ';
            echo '      <script src="https://unpkg.com/mathlive/dist/mathlive.min.js"></script>
            <script>
            MathLive.makeMathField(document.getElementById("mathfield"),  {
              virtualKeyboardMode: "manual",
              virtualKeyboards: "numeric symbols"
            });
            </script>   
               ';
            echo '<hr>';
        }

    }
}

/*
echo '
        <p class="text-muted">Multiple Choice Question</p>
        <p class="text-justify h5 pb-2 font-weight-bold">What did Radha Krishnan (Cassius Clay at the time) wear while flying to Rome for the 1960 Games?</p>
        <div class="options py-3"> <label class="rounded p-2 option"> His boxing gloves <input type="radio" name="radio"> <span class="crossmark"></span> </label> <label class="rounded p-2 option"> A parachute <input type="radio" name="radio"> <span class="checkmark"></span> </label> <label class="rounded p-2 option"> Nothing <input type="radio" name="radio"> <span class="crossmark"></span> </label> <label class="rounded p-2 option"> A world little belt <input type="radio" name="radio"> <span class="crossmark"></span> </label> </div> <b>Correct Feedback</b>
        <p class="mt-2 mb-4 pl-2 text-justify"> Well done! He was scared of flying so picked up the parachute from an support store before the trip. He won gold </p> <b>Incorrect Feedback</b>
        <p class="my-2 pl-2"> That was incorrect. Try again </p>
    </div> <input type="submit" value="Add Question" class="mx-sm-0 mx-1">
</div>';
*/


?>

    <script type="text/javascript">
        function checkCookie()
        {
            var f = getCookie("timerMinutes");
            var g = getCookie("timerSeconds");
            return f !== null && g !== null;
        }

        function getCookie(name)
        {
            var cookieArr = document.cookie.split(";");

            for(var i = 0; i < cookieArr.length; i++)
            {
                var cookiePair = cookieArr[i].split("=");

                if(name === cookiePair[0].trim())
                {
                    return decodeURIComponent(cookiePair[1]);
                }
            }
            return null;
        }

        var delete_cookie = function(name)
        {
            document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        };


        function createCookie(name, value)
        {
            var date = new Date();
            date.setTime(date.getTime() + (30*1000));
            var expires = "; expires= " + date.toGMTString();

            document.cookie = name + "=" + value + expires + "; path=/";
        }

        var iTimeMinutes = <?php echo $time; ?>;
        var iTimeSeconds = iTimeMinutes % 60;

        if(checkCookie('timerMinutes') && checkCookie("timerSeconds"))
        {
            iTimeMinutes = parseInt(getCookie('timerMinutes'), 10);
            iTimeSeconds = parseInt(getCookie('timerSeconds'), 10);
        }

        function countdown()
        {
            var i = setInterval(function()
            {
                document.cookie = "timerMinutes=" + encodeURIComponent(iTimeMinutes);
                document.cookie = "timerSeconds=" + encodeURIComponent(iTimeSeconds);

                if((iTimeMinutes < 10 && iTimeSeconds < 10) || (iTimeMinutes < "10" && iTimeSeconds < "10"))
                {
                    document.getElementById("fixedTimer").innerHTML = "Zostávajúci čas: " + "0" + iTimeMinutes + ":" + "0" + iTimeSeconds;
                }
                else
                {
                    if((iTimeMinutes < 10) || (iTimeMinutes < "10"))
                    {
                        document.getElementById("fixedTimer").innerHTML = "Zostávajúci čas: " + "0" + iTimeMinutes + ":" + iTimeSeconds;
                    }
                    else if((iTimeSeconds < 10) || (iTimeSeconds < "10"))
                    {
                        document.getElementById("fixedTimer").innerHTML = "Zostávajúci čas: " + iTimeMinutes + ":" + "0" + iTimeSeconds;
                    }
                    else
                    {
                        document.getElementById("fixedTimer").innerHTML = "Zostávajúci čas: " + iTimeMinutes + ":" + iTimeSeconds;
                    }

                }

                if((iTimeMinutes === 0 && iTimeSeconds === 0) || (iTimeMinutes === "0" && iTimeSeconds === "0"))
                {
                    alert('Cas na test vyprsal!');
                    delete_cookie('timerMinutes');
                    delete_cookie('timerSeconds');
                    clearInterval(i);
                    location.reload();
                }
                else
                {
                    if(iTimeSeconds === 0 || iTimeSeconds === "0")
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