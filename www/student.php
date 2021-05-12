<?php
session_start();


//file
$student_name = $_SESSION['studentName'];
$student_surname = $_SESSION['studentSurname'];
$test_code = $_SESSION['testCode'];

if(isset($_POST['img_draw']) && $student_name && $student_surname && $test_code){
    file_put_contents("images/drawing_questions/".$_SESSION['studentName']."_".$_SESSION['studentSurname']."_".$_SESSION['testCode'] . '.png', file_get_contents($_POST['img_draw']));
}

//tuto to este blbne, nechcevojst do funkcie a ulozit obrazok
if(isset($_FILES['fileSend']) && $student_name && $student_surname && $test_code){
    $file_name = $_SESSION['studentName']."_".$_SESSION['studentSurname']."_".$_SESSION['testCode'];
    $file_type = $_FILES['fileSend']['type'];
    $file_size = $_FILES['fileSend']['size'];

    $new_name = strtolower(substr($_FILES['fileSend']['name'], strpos($_FILES['fileSend']['name'], '.')));

    $file_store = "images/math_questions/".$file_name.$new_name;
    $file_tem_loc = $_FILES['fileSend']['tmp_name'];
    move_uploaded_file($file_tem_loc, $file_store);
}


if (!isset($_SESSION["student"])) {
    header("location: index.php");
}

if(isset($_POST['sendTheTest']))
{
    //TODO: odoslanie odpovedi studenta
    //TODO: delete cookies z timeru
    //TODO: vsetko ostatne co treba
}

include "partials/header.php";
include "queries/queries.php";
require_once("config/config.php");

$link = $conn;

$sessionTestCode = $_SESSION['testCode'];
$row = mysqli_fetch_assoc(getTestTime($link, $_SESSION['testCode']));
$time = $row['total_time'];

$selectTestCode = $link->query("SELECT id, test_code, total_points FROM test WHERE test_code = '$sessionTestCode'");
$selectedData = mysqli_fetch_assoc($selectTestCode);

//test id for entered test code
$testId = $selectedData['id'];

$selectTypeOfQuestion = $link->query("SELECT * FROM question WHERE test_id = '$testId'");

if ($sessionTestCode == $selectedData['test_code']) {
    echo '<form method="post" action="">';
    echo '<div class="wrapper bg-white rounded">
            <div class="content">
            <p class="text-muted"><b>Kód testu: ' . $sessionTestCode . '</b></p>
            <p class="text-muted"><b>Počet bodov v teste: ' . $selectedData["total_points"] . '</b></p>
            <hr>';

    while ($questions = $selectTypeOfQuestion->fetch_assoc()) {
        $questionId = $questions['id'];

        $selectOptions = $link->query("SELECT * FROM questionOption WHERE question_id = '$questionId'");
        if ($questions['type'] == 'checkbox') {
            echo '<p class="text-muted"><b>Otázka s možnosťami</b></p>
                   <p class="text-muted""><b>Body: ' . $questions['total_points'] . '</b></p>
                   <p class="text-justify h5 pb-2 font-weight-bold">' . $questions['name'] . '</p>';
            while ($option = $selectOptions->fetch_assoc()) {
                if ($option['question_id'] == $questionId) {
                    echo '<div class="options py-3 "> 
                     <label class="rounded p-2 option"> ' . $option['name'] . '
                     <input id="' . $option['name'] . '" type="checkbox" name="'. $questionId  .' " class="testInput checkbox">
                     <span class="crossmark"></span>
                     </label>
                     ';
                    echo '</div>';
                }
            }
            echo '<hr>';
        }
        if ($questions['type'] == 'short') {
            echo '<p class="text-muted"><b>Otázka s krátkou odpoveďou</b></p>
                   <p class="text-muted""><b>Body: ' .   $questions['total_points'] . '</b></p>
                   <p class="text-justify h5 pb-2 font-weight-bold">' . $questions['name'] . '</p>';
            while ($option = $selectOptions->fetch_assoc()) {
                if ($option['question_id'] == $questionId) {
                    //                    <label class="rounded p-2 option"> '.$option['name'].'
                    //                    <span class="crossmark"></span>
                    echo '<div class="options py-3">                    
                     <input id="' . $questionId . '" class="testInput short" type="text">   
                     </label>
                     ';
                    echo '</div>';
                }
            }
            echo '<hr>';
        }

        if($questions['type'] == 'connect')
        {
            echo ' <script type="text/javascript" src="assets/js/jsplumb.min.js"></script>';
            echo '<p class="text-muted"><b>Otázka s párovaním správnych odpovedí</b></p>
                   <p class="text-muted""><b>Body: '.$questions['total_points'].'</b></p>
                   <p class="text-justify h5 pb-2 font-weight-bold">'.$questions['name'].'</p>';

            echo'<div class="page_connections">
              <div id="select_list_left">
                <ul class="connect_ul_left">                  
                </ul>
              </div>
              <div id="select_list_right">
                <ul class="connect_ul_right">                 
                </ul>
              </div>
            </div>';
            ?>
            <script>
                $(document).ready(function() {
                    var targetOption = {
                        anchor: "LeftMiddle",
                        maxConnections: 1,
                        isSource: false,
                        isTarget: true,
                        reattach: true,
                        endpoint: "Dot",
                        connector: [ "Bezier", { curviness: 50 } ],
                        setDragAllowedWhenFull: true
                    };
                    var sourceOption = {
                        tolerance: "touch",
                        anchor: "RightMiddle",
                        maxConnections: 1,
                        isSource: true,
                        isTarget: false,
                        reattach: true,
                        endpoint: "Dot",
                        connector: [ "Bezier", { curviness: 50 } ],
                        setDragAllowedWhenFull: true
                    };

                    jsPlumb.importDefaults({
                        ConnectionsDetachable: true,
                        ReattachConnections: true,
                        maxConnections: 1,
                        Container: "page_connections"
                    });

                    var questionEndpoints = [];

                    $("#select_list_left ul > li").click(function() {
                        var con=jsPlumb.getConnections({source:$(this)});
                        if(con.length!==0){
                            jsPlumb.removeAllEndpoints($(this));
                        }
                        con=jsPlumb.getConnections({target:$(this)});
                        if(con.length!==0){
                            jsPlumb.removeAllEndpoints($(this));
                        }
                        questionEndpoints[0] = jsPlumb.addEndpoint($(this), sourceOption);
                        connectEndpoints();
                    });
                    $("#select_list_right ul > li").click(function() {
                        if (!questionEndpoints[0]) return;
                        var con=jsPlumb.getConnections({target:$(this)});
                        if(con.length!==0){
                            jsPlumb.removeAllEndpoints($(this));
                        }
                        con=jsPlumb.getConnections({source:$(this)});
                        if(con.length!==0){
                            jsPlumb.removeAllEndpoints($(this));
                        }
                        questionEndpoints[1] = jsPlumb.addEndpoint($(this), targetOption);
                        connectEndpoints();
                    });

                    var connectEndpoints = function() {
                        jsPlumb.connect({
                            source: questionEndpoints[0],
                            target: questionEndpoints[1]
                        });
                        var xx=jsPlumb.getConnections();
                        xx.forEach(function(item, index){
                            $('.connect_left')[index].value=item.source.innerHTML;
                            $('.connect_right')[index].value=item.target.innerHTML;
                        })
                    }
                });

            </script>
            <?php
            while($option = $selectOptions->fetch_assoc())
            {
                if($option['question_id'] == $questionId)
                {
                    echo "<input id=".$questionId ." type='hidden' name='left[]' class='connect_left testInput connect '>";
                    $opname=$option['name'];
                    ?>
                    <script>
                        $(".connect_ul_left").append('<li><?php echo"$opname";?></li>');
                    </script>
                    <?php
                    $optionpairId=$option['id'];
                    $pairOptions = $link->query("SELECT * FROM OptionsPair WHERE questionOption_id = '$optionpairId'");
                    while($pair = $pairOptions->fetch_assoc()){
                        echo "<input type='hidden' name='right[]' class='connect_right'>";
                        $pname=$pair['name'];
                        ?>
                        <script>
                            $(".connect_ul_right").append('<li><?php echo"$pname";?></li>');
                        </script>
                        <?php
                    }
                }
            }
            echo '<hr>';

        }

        if ($questions['type'] == 'draw') {
            echo '<p class="text-muted"><b>Otázka s nakreslením obrázku</b></p>
                   <p class="text-muted""><b>Body: ' . $questions['total_points'] . '</b></p>
                   <p class="text-justify h5 pb-2 font-weight-bold">' . $questions['name'] . '</p>
                   <div class="testInput draw" name="'. $questionId.'" id="canvasDiv"></div>';

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
                    if (typeof G_vmlCanvasManager != 'undefined') {
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

                $('#canvas').mousedown(function(e) {
                    var mouseX = e.pageX - this.offsetLeft;
                    var mouseY = e.pageY - this.offsetTop;

                    paint = true;
                    addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop);
                    redraw();
                });

                $('#canvas').mousemove(function(e) {
                    if (paint) {
                        addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, true);
                        redraw();
                    }
                });

                $('#canvas').mouseup(function(e) {
                    paint = false;
                });

                $('#canvas').mouseleave(function(e) {
                    paint = false;
                });

                $(document).on('click', '.send_answers',  function(event) {
                    event.preventDefault();
                    var dataURL = canvas.toDataURL();
                    $.ajax({
                        type: "post",
                        url: "uploadFile.php",
                        data: {
                            img_draw: dataURL
                        },
                        success: function(data) {
                            console.log(data);
                            console.log(dataURL);
                        }

                    })
                })


                function addClick(x, y, dragging) {
                    clickX.push(x);
                    clickY.push(y);
                    clickDrag.push(dragging);
                }

                function redraw() {
                    context.clearRect(0, 0, context.canvas.width, context.canvas.height); // Clears the canvas

                    context.strokeStyle = "#df4b26";
                    context.lineJoin = "round";
                    context.lineWidth = 5;

                    for (var i = 0; i < clickX.length; i++) {
                        context.beginPath();
                        if (clickDrag[i] && i) {
                            context.moveTo(clickX[i - 1], clickY[i - 1]);
                        } else {
                            context.moveTo(clickX[i] - 1, clickY[i]);
                        }
                        context.lineTo(clickX[i], clickY[i]);
                        context.closePath();
                        context.stroke();
                    }
                }

                context.save();
            </script>

            <?php

            echo '<hr>';
        }
        if ($questions['type'] == 'math') {
            $questionT = $questions['id'];
            echo '<p class="text-muted"><b>Otázka s matematickou odpoveďou</b></p>
                   <p class="text-muted""><b>Body: ' . $questions['total_points'] . '</b></p>  
                      <math-field disabled>' . $questions['name'] . '</math-field>
                   <math-field id="'. $questions['id'] .'" virtual-keyboard-mode="manual" class="testInput math border mb-3" style="display: none"></math-field>
                ';
            echo '      <script src="https://unpkg.com/mathlive/dist/mathlive.min.js"></script>';

            echo '
            
            <label id="upload-label" class="upload-label" for="file-btn" style="display: none">Vybrať súbor</label>
            <p><input type="file" id="file-btn" name="fileSend" /hidden></p>

            <div class="dropdown show">
                <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Typ odpovede
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" id="nahrat-subor" href="#">Nahranim suboru</a>
                    <a class="dropdown-item" id="vyraz" href="#">Matematickým výrazom</a>
                </div>
            </div>
            
            ';
            ?>

            <script>
                document.getElementById('nahrat-subor').onclick = function() {
                    document.getElementById(<?php echo $questionT?>).style.display = "none";
                    document.getElementById('upload-label').style.display = "block";
                };

                document.getElementById('vyraz').onclick = function() {
                    document.getElementById('upload-label').style.display = "none";
                    document.getElementById(<?php echo $questionT?>).style.display = "block";
                };
            </script>

            <?php
            echo '<hr>';
        }
    }
    echo '</div>
                <button type="submit" id="sendTheTest" name="sendTheTest" class="send_answers odoslat">Odoslať test</button>
    </div>';
    echo '</form>';
}

?>

    <script type="text/javascript">
        $(document).ready(function() {
            $(".odoslat").click(function() {
                var data = new Array()
                data.push({
                    "meno": "<?php echo $_SESSION['studentName'] ?>"
                })
                data.push({
                    "priezvisko": "<?php echo $_SESSION['studentSurname'] ?>"
                })
                var left = new Array()
                var right = new Array()
                $('.connect_left').each(function() {
                     left.push( $(this)[0].value)
                })

                $('.connect_right').each(function() {
                    right.push( $(this)[0].value)
                })
                $('.testInput').each(function() {
                    if ($(this)[0].classList.contains('checkbox')) {
                        data.push({
                            "zaznam": [{
                                "text": $(this).attr("id") + ""
                            }, {
                                data: $(this)[0].checked
                            },{
                                "type": "checked"
                            },{
                                "questionId": $(this).attr("name") + ""
                            }]
                        })
                    }
                    let i = 0
                    if ($(this)[0].classList.contains('connect')) {

                        data.push({
                            "zaznam": [{
                                "id": $(this).attr("id") + ""
                            }, {
                                "left": left[i]
                            },{
                                "right": right[i]
                            }]
                        })
                        i++
                    }


                    if ($(this)[0].classList.contains('math')) {
                        var res = $(this).val();
                        var regex = /\\/g;
                        res = res.replace(regex, "\\\\");

                        data.push({
                            "zaznam": [{
                                "id": $(this).attr("id") + ""
                            }, {
                                data: res
                            }]
                        })
                    }
                    if ($(this)[0].classList.contains('short')) {
                        data.push({
                            "zaznam": [{
                                "id": $(this).attr("id") + ""
                            }, {
                                data: $(this)[0].value
                            }]
                        })
                    }
                    if ($(this)[0].classList.contains('draw')) {

                        data.push({
                            "zaznam": [{
                                "id": $(this).attr("name") + ""
                            }, {
                                data: "images/drawing_questions/" + <?php echo  json_encode($_SESSION['studentName']) ?> +"_" + <?php echo json_encode($_SESSION['studentSurname']) ?>+"_"+<?php echo json_encode($_SESSION['testCode']) ?> + ".png"
                            }]
                        })
                    }

                })

                $.ajax({
                    url: "controllers/addTestAnswer.php",
                    method: "POST",
                    cache: false,
                    data: JSON.stringify(data),
                    success: function(result) {
                        //console.log(result)
                    }
                });
            });
        });

        var iTimeMinutes = <?php echo $time; ?>;
        var iTimeSeconds = 0;

        function checkCookie() {
            var f = getCookie("timerMinutes");
            var g = getCookie("timerSeconds");
            return f !== null && g !== null;
        }

        function getCookie(name) {
            var cookieArr = document.cookie.split(";");

            for (var i = 0; i < cookieArr.length; i++) {
                var cookiePair = cookieArr[i].split("=");

                if (name === cookiePair[0].trim()) {
                    return decodeURIComponent(cookiePair[1]);
                }
            }
            return null;
        }

        var delete_cookie = function(name) {
            document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        };

        function createCookie(name, value) {
            var date = new Date();
            // zivotnost cookie nastavena na dlzku testu + 10 minut
            date.setTime(date.getTime() + (iTimeMinutes + 10) * 60 * 1000);
            var expires = "; expires= " + date.toGMTString();

            document.cookie = name + "=" + value + expires + "; path=/";
        }

        if (checkCookie('timerMinutes') && checkCookie("timerSeconds"))
        {
            iTimeMinutes = parseInt(getCookie('timerMinutes'), 10);
            iTimeSeconds = parseInt(getCookie('timerSeconds'), 10);
        }

        function setTimer() {
            var now = new Date();
            var time = now.getTime();
            var expireTime = time + 10*36000;
            now.setTime(expireTime);
            return now;
        }

        function countdown() {

            var i = setInterval(function() {
                //document.cookie = "timerMinutes=" + encodeURIComponent(iTimeMinutes);
                //document.cookie = "timerSeconds=" + encodeURIComponent(iTimeSeconds);
                createCookie("timerMinutes", iTimeMinutes);
                createCookie("timerSeconds", iTimeSeconds);

                if ((iTimeMinutes < 10 && iTimeSeconds < 10) || (iTimeMinutes < "10" && iTimeSeconds < "10")) {
                    document.getElementById("fixedTimer").innerHTML = "Zostávajúci čas " + "0" + iTimeMinutes + ":" + "0" + iTimeSeconds;
                } else {
                    if ((iTimeMinutes < 10) || (iTimeMinutes < "10")) {
                        document.getElementById("fixedTimer").innerHTML = "Zostávajúci čas " + "0" + iTimeMinutes + ":" + iTimeSeconds;
                    } else if ((iTimeSeconds < 10) || (iTimeSeconds < "10")) {
                        document.getElementById("fixedTimer").innerHTML = "Zostávajúci čas " + iTimeMinutes + ":" + "0" + iTimeSeconds;
                    } else {
                        document.getElementById("fixedTimer").innerHTML = "Zostávajúci čas " + iTimeMinutes + ":" + iTimeSeconds;
                    }
                }

                if ((iTimeMinutes === 0 && iTimeSeconds === 0) || (iTimeMinutes === "0" && iTimeSeconds === "0")) {
                    $('#showModal7').modal({ backdrop: 'static', keyboard: false }, 'show');
                    delete_cookie('timerMinutes');
                    delete_cookie('timerSeconds');
                    clearInterval(i);
                } else {
                    if (iTimeSeconds === 0 || iTimeSeconds === "0") {
                        iTimeMinutes--;
                        iTimeSeconds = 60;
                    }
                    iTimeSeconds--;
                }


            }, 1000);
        }

        countdown();

    </script>

    <div id="fixedTimer" class="fancy"></div>

    <div id="showModal7" class="modal fade text-center">
        <div class="modal-dialog modal-confirm text-center">
            <div class="modal-content text-center">
                <div class="modal-header text-center">
                    <div class="icon-box">
                        <i class="bi bi-alarm"></i>
                    </div>
                    <h4 class="modal-title text-center">Čas vypršal !</h4>
                </div>
                <div class="modal-body text-center">
                    <p class="text-center">Čas pre test vypršal ! <br> Vaše odpovede boli odoslané.</p>
                </div>
                <div class="modal-footer text-center">
                    <form method="post" action="">
                        <button class="btn btn-success btn-block" type="submit" id="theModalButton" name="theModalButton">Zavrieť</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php //toto bude treba poriesit, pretoze toto vypne session, teda sa obrazok neuploadne session_destroy();

?>

<?php

include "partials/footer.php";
?>