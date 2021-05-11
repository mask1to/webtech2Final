<?php
session_start();


if(!isset($_SESSION["student"]))
{
    header("location: index.php");
}

include "partials/header.php";
include "queries/queries.php";
require_once("config/config.php");
include "uploadFile.php";

$link = $conn;

$sessionTestCode = $_SESSION['testCode'];
$row = mysqli_fetch_assoc(getTestTime($link,$_SESSION['testCode']));
$time = $row['total_time'];

$selectTestCode = $link->query("SELECT id, test_code, total_points FROM test WHERE test_code = '$sessionTestCode'");
$selectedData = mysqli_fetch_assoc($selectTestCode);

//test id for entered test code
$testId = $selectedData['id'];

$selectTypeOfQuestion = $link->query("SELECT * FROM question WHERE test_id = '$testId'");

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
                    echo '<div class="options py-3 "> 
                     <label class="rounded p-2 option"> '.$option['name'].'
                     <input id="'. $questionId .'" type="checkbox" name="radio" class="testInput checkbox">
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
                     <input id="'. $questionId . '" class="testInput short" type="text">   
                     </label>
                     ';
                    echo '</div>';
                }

            }
            echo '<hr>';
        }

        if($questions['type'] == 'connect')
        {
            echo '<link rel="stylesheet" href="assets/css/fieldsLinker.css">';
            echo '<script src="assets/js/fieldsLinker.js"></script>';
            echo '<p class="text-muted"><b>Otázka s párovaním správnych odpovedí</b></p>
                   <p class="text-muted""><b>Body: '.$questions['total_points'].'</b></p>
                   <p class="text-justify h5 pb-2 font-weight-bold">'.$questions['name'].'</p>';
            while($option = $selectOptions->fetch_assoc())
            {
                if($option['question_id'] == $questionId)
                {
                    $opname=$option['name'];
                    echo"<p>$opname";
                    $optionpairId=$option['id'];
                    $pairOptions = $link->query("SELECT * FROM OptionsPair WHERE questionOption_id = '$optionpairId'");
                    while($pair = $pairOptions->fetch_assoc()){

                        $pname=$pair['name'];
                        echo"   $pname</p>";
                    }

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
                    canvas.setAttribute('width', 550);
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
                   <div id="'. $questionId .'"  class="testInput math " > </div> 
                ';
            echo '      <script src="https://unpkg.com/mathlive/dist/mathlive.min.js"></script>
            <script>
            
            var els = document.getElementsByClassName("math");

            Array.prototype.forEach.call(els, function(el) {
                 MathLive.makeMathField(el,  {
              virtualKeyboardMode: "manual",
              virtualKeyboards: "numeric symbols"
            });
            });
            document.getElementsByClassName("math")
           
            </script>   
               ';
            ?>
            <form action="" method="POST" enctype="multipart/form-data" id="typ-odpovede" style="display:none">
                <p><input type="submit" name="upload" value="Vložiť"></p>
                <label class="upload-label" for="file-btn">Vybrať súbor na upload</label>
                <p><input type="file" id="file-btn" name="file" /hidden></p>
            </form>

            <div class="dropdown show">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Typ odpovede
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" id="nahrat-subor" href="#">Nahranim suboru</a>
                    <a class="dropdown-item" href="#">bla</a>
                    <a class="dropdown-item" href="#">bla</a>
                </div>
            </div>

            <script>
                document.getElementById('nahrat-subor').onclick = function(){
                    document.getElementById('typ-odpovede').style.display = "block";
                };
            </script>

            <?php

            echo '<hr>';
        }

    }
    echo '</div> <input type="submit" value="Odoslať test" class="mx-sm-0 mx-1 submit">
    </div>';
}

?>

    <script type="text/javascript">
        $(document).ready(function() {
            $(".submit").click(function () {
                var data= new Array()
                data.push({ "meno":"<?php echo $_SESSION['studentName']?>"})
                data.push({ "priezvisko":"<?php echo $_SESSION['studentSurname']?>"})
                $('.testInput').each(function () {

                    if ($(this)[0].classList.contains('math')) {
                        var str = $(this)[0].innerText
                        var n = str.search("\n");
                        var res = str.substr(0, n);
                        // console.log(res)$(this).attr("id")
                        data.push( { "zaznam": [{ "id" : $(this).attr("id") +""} , { data: res }]})
                    }
                    if ($(this)[0].classList.contains('short')) {
                        data.push( { "zaznam": [{ "id" : $(this).attr("id") +""} , { data: $(this)[0].value }]})
                    }

                })
                console.log(data)
                $.ajax({
                    url: "controllers/addTestAnswer.php",
                    method: "POST",
                    cache: false,
                    data: JSON.stringify(data),
                    success: function (result) {
                        console.log(result)
                    }
                });
            });
        });

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
        <?php //toto bude treba poriesit, pretoze toto vypne session, teda sa obrazok neuploadne session_destroy(); ?>
    </script>

    <div id="fixedTimer" class="fancy"></div>

<?php

include "partials/footer.php";
?>