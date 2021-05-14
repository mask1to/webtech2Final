<?php
session_start();

if (!isset($_SESSION["student"])) {
    header("location: index.php");
}

//file
$student_name = $_SESSION['studentName'];
$student_surname = $_SESSION['studentSurname'];
$test_code = $_SESSION['testCode'];


if (isset($_POST['img_draw']) && $student_name && $student_surname && $test_code) {
    file_put_contents("images/drawing_questions/" . $_SESSION['studentName'] . "_" . $_SESSION['studentSurname'] . "_" . $_SESSION['testCode'] . '_' . $_POST['id'] . ".jpg", file_get_contents($_POST['img_draw']));
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
    echo '
<form action="" method="POST" enctype="multipart/form-data">
<div class="wrapper bg-white rounded">
            <div class="content">
            <p class="text-muted"><b>Kód testu: ' . $sessionTestCode . '</b></p>
            <p class="text-muted"><b>Počet bodov v teste: ' . $selectedData["total_points"] . '</b></p>
            <hr>';

    while ($questions = $selectTypeOfQuestion->fetch_assoc()) {
        $questionId = $questions['id'];

        $selectOptions = $link->query("SELECT * FROM questionOption WHERE question_id = '$questionId'");
        if ($questions['type'] == 'checkbox') {
            echo '
                   <p class="text-muted""><b>Body: ' . $questions['total_points'] . '</b></p>
                   <p class="text-justify h5 pb-2 font-weight-bold">' . $questions['name'] . '</p>';
            while ($option = $selectOptions->fetch_assoc()) {
                if ($option['question_id'] == $questionId) {
                    echo '<div class="options py-3 "> 
                     <label class="rounded p-2 option"> ' . $option['name'] . '
                     <input id="' . $option['name'] . '" type="checkbox" name="' . $questionId  . ' " class="testInput checkbox">
                     <span class="crossmark"></span>
                     </label>
                     ';
                    echo '</div>';
                }
            }
            echo '<hr>';
        }
        if ($questions['type'] == 'short') {
            echo '
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

        if ($questions['type'] == 'connect') {
            echo ' <script type="text/javascript" src="assets/js/jsplumb.min.js"></script>';
            echo '<div class="connect">
                   <p class="text-muted""><b>Body: ' . $questions['total_points'] . '</b></p>
                   <p class="text-justify h5 pb-2 font-weight-bold">' . $questions['name'] . '</p>';

            echo '<div class="page_connections">
              <div id="select_list_left-' . $questionId . '">
                <ul class="connect_ul_left">                  
                </ul>
              </div>
              <div id="select_list_right-' . $questionId . '">
                <ul class="connect_ul_right">                 
                </ul>
              </div>
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
                        connector: ["Bezier", {
                            curviness: 50
                        }],
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
                        connector: ["Bezier", {
                            curviness: 50
                        }],
                        setDragAllowedWhenFull: true
                    };

                    jsPlumb.importDefaults({
                        ConnectionsDetachable: true,
                        ReattachConnections: true,
                        maxConnections: 1,
                        Container: "page_connections"
                    });

                    var questionEndpoints = [];

                    $("#select_list_left-<?php echo $questionId ?> ul > li").click(function() {
                        var con = jsPlumb.getConnections({
                            source: $(this)
                        });
                        if (con.length !== 0) {
                            jsPlumb.removeAllEndpoints($(this));
                        }
                        con = jsPlumb.getConnections({
                            target: $(this)
                        });
                        if (con.length !== 0) {
                            jsPlumb.removeAllEndpoints($(this));
                        }
                        questionEndpoints[0] = jsPlumb.addEndpoint($(this), sourceOption);
                        connectEndpoints();
                    });
                    $("#select_list_right-<?php echo $questionId ?> ul > li").click(function() {
                        if (!questionEndpoints[0]) return;
                        var con = jsPlumb.getConnections({
                            target: $(this)
                        });
                        if (con.length !== 0) {
                            jsPlumb.removeAllEndpoints($(this));
                        }
                        con = jsPlumb.getConnections({
                            source: $(this)
                        });
                        if (con.length !== 0) {
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
                        var xx = jsPlumb.getConnections();
                        xx.forEach(function(item, index) {
                            $('.connect_left')[index].value = item.source.innerHTML;
                            $('.connect_right')[index].value = item.target.innerHTML;
                        })
                    }
                });
            </script>
            <?php
            while ($option = $selectOptions->fetch_assoc()) {
                if ($option['question_id'] == $questionId) {
                    echo "<input id=" . $questionId . " type='hidden' name='left[]' class='connect_left testInput connect'>";
                    $opname = $option['name'];
            ?>
                    <script>
                        $("#select_list_left-<?php echo $questionId ?> .connect_ul_left").append('<li><?php echo "$opname"; ?></li>');
                    </script>
                    <?php
                    $optionpairId = $option['id'];
                    $pairOptions = $link->query("SELECT * FROM OptionsPair WHERE questionOption_id = '$optionpairId'");
                    while ($pair = $pairOptions->fetch_assoc()) {
                        echo "<input id=" . $optionpairId . "  type='hidden' name='right[]' class='connect_right' value='" . $pair['name'] . "'>";
                        $pname = $pair['name'];
                    ?>
                        <script>
                            $("#select_list_right-<?php echo $questionId ?> .connect_ul_right").append('<li><?php echo "$pname"; ?></li>');
                        </script>
            <?php
                    }
                }
            }
            ?>
            <script>
                var parent = $("#select_list_right-<?php echo $questionId ?> .connect_ul_right");
                var divs = parent.children();
                while (divs.length) {
                    parent.append(divs.splice(Math.floor(Math.random() * divs.length), 1)[0]);
                }
            </script>
        <?php
            echo '<hr>';
        }

        if ($questions['type'] == 'draw') {
            echo '<div class="draw-parent">
                   <p class="text-muted""><b>Body: ' . $questions['total_points'] . '</b></p>
                   <p class="text-justify h5 pb-2 font-weight-bold">' . $questions['name'] . '</p>
                   <div id="' . $questionId . '" class="testInput draw canvasDiv canvas" name="' . $questionId . '"></div>';

            echo '
                <div class="form-group upl-draw" style="display: none">
                    <div class="input-group">
                      <input type="text" class="form-control" readonly>
                    <div class="input-group-btn">
                      <span class="fileUpload">
                          <span class="upl" id="upload">Upload file</span>
                          <input name="file-draw" type="file" class="upload up file-btn-draw" id="drawUp" accept="image/jpeg" />
                        </span><!-- btn-orange -->
                     </div><!-- btn -->
                     </div><!-- group -->
                     </div><!-- form-group -->
                
                    <div class="dropdown show">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Typ odpovede
                        </a>
                        <button class="demoToolList btn btn-primary" onclick="c' . $questionId . '(' . $questionId . ')" id="clearCanvasSimple" type="button">Odznovu</button>
                        <br>
        
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item nahrat-subor-draw" href="#">Nahrat subor</a>
                            <a class="dropdown-item skryt" href="#">Kreslenie</a>
                        </div>
                        <br>
                    </div>
                </div>
            ';
        ?>
            <script>
                build_canvas();
                var clickX<?php echo $questionId ?> = new Array();
                var clickY<?php echo $questionId ?> = new Array();
                var clickDrag<?php echo $questionId ?> = new Array();
                var paint<?php echo $questionId ?>;

                function build_canvas() {
                    var canvasDiv = document.getElementById('<?php echo $questionId ?>');
                    canvas<?php echo $questionId ?> = document.createElement('canvas');
                    canvas<?php echo $questionId ?>.setAttribute('width', 550);
                    canvas<?php echo $questionId ?>.setAttribute('height', 220);
                    canvas<?php echo $questionId ?>.setAttribute('id', 'canvas-' + <?php echo $questionId ?>);
                    canvas<?php echo $questionId ?>.style = "border:thin solid black; border-radius: 20px";
                    canvasDiv.appendChild(canvas<?php echo $questionId ?>);
                    if (typeof G_vmlCanvasManager != 'undefined') {
                        canvas<?php echo $questionId ?> = G_vmlCanvasManager.initElement(canvas);
                    }
                }

                function c<?php echo $questionId ?>(id) {
                    var context = document.getElementById('canvas-' + id).getContext('2d');
                    context.clearRect(0, 0, canvas<?php echo $questionId ?>.width, canvas<?php echo $questionId ?>.height);
                    //context.closePath();

                    clickX<?php echo $questionId ?> = new Array();
                    clickY<?php echo $questionId ?> = new Array();
                    clickDrag<?php echo $questionId ?> = new Array();

                    context.save();
                }

                $('#<?php echo $questionId ?>').mousedown(function(e) {
                    var mouseX = e.pageX - this.offsetLeft;
                    var mouseY = e.pageY - this.offsetTop;
                    var id = <?php echo $questionId ?>;

                    paint<?php echo $questionId ?> = true;
                    addClick<?php echo $questionId ?>(e.pageX - this.offsetLeft, e.pageY - this.offsetTop);
                    redraw<?php echo $questionId ?>(id);
                });

                $('#<?php echo $questionId ?>').mousemove(function(e) {
                    var id = <?php echo $questionId ?>;
                    if (paint<?php echo $questionId ?>) {
                        addClick<?php echo $questionId ?>(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, true);
                        redraw<?php echo $questionId ?>(id);
                    }
                });

                $('#<?php echo $questionId ?>').mouseup(function() {
                    paint<?php echo $questionId ?> = false;
                });

                $('#<?php echo $questionId ?>').mouseleave(function() {
                    paint<?php echo $questionId ?> = false;
                });

                function addClick<?php echo $questionId ?>(x, y, dragging) {
                    clickX<?php echo $questionId ?>.push(x);
                    clickY<?php echo $questionId ?>.push(y);
                    clickDrag<?php echo $questionId ?>.push(dragging);
                }

                function redraw<?php echo $questionId ?>(id) {
                    var context = document.getElementById('canvas-' + id).getContext('2d');
                    context.clearRect(0, 0, canvas<?php echo $questionId ?>.width, canvas<?php echo $questionId ?>.height); // Clears the canvas

                    context.strokeStyle = "#718aab";
                    context.lineJoin = "round";
                    context.lineWidth = 5;

                    for (var i = 0; i < clickX<?php echo $questionId ?>.length; i++) {
                        context.beginPath();
                        if (clickDrag<?php echo $questionId ?>[i] && i) {
                            context.moveTo(clickX<?php echo $questionId ?>[i - 1], clickY<?php echo $questionId ?>[i - 1]);
                        } else {
                            context.moveTo(clickX<?php echo $questionId ?>[i] - 1, clickY<?php echo $questionId ?>[i]);
                        }
                        context.lineTo(clickX<?php echo $questionId ?>[i], clickY<?php echo $questionId ?>[i]);
                        context.closePath();
                        context.stroke();
                    }
                    context.save();
                }
            </script>

        <?php

            echo '<hr>';
        }
        if ($questions['type'] == 'math') {
            $questionT = $questions['id'];
            echo '<div class="math-parent">
                   <p class="text-muted""><b>Body: ' . $questions['total_points'] . '</b></p>
                   <p class="text-muted text-justify h5 pb-2 font-weight-bold" /hidden>' . $questions['name'] . '</p>
                      <math-field id="math-q" disabled>' . $questions['name'] . '</math-field>
                   <math-field id="' . $questions['id'] . '" virtual-keyboard-mode="manual" class="testInput math border mb-3"></math-field>
                ';
            echo '      <script src="https://unpkg.com/mathlive/dist/mathlive.min.js"></script>';
            echo '<div class="form-group upl" style="display: none">
                    <div class="input-group">
                      <input type="text" class="form-control" readonly>
                    <div class="input-group-btn">
                      <span class="fileUpload">
                          <span class="upl" id="upload">Upload file</span>
                          <input name="file-math" type="file" class="upload up file-btn-math" id="mathUp" accept="image/jpeg" />
                        </span><!-- btn-orange -->
                     </div><!-- btn -->
                     </div><!-- group -->
                   </div><!-- form-group -->

                    <div class="dropdown show">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Typ odpovede
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item nahrat-subor" href="#">Nahranim suboru</a>
                            <a class="dropdown-item vyraz" href="#">Matematickým výrazom</a>
                        </div>
                    </div>
                    </div>';
        ?>

<?php
            echo '<hr>';
        }
    }
    echo '</div>
                <button data-clicked="false" type="submit" id="sendTheTest" name="sendTheTest" class="send_answers odoslat">Odoslať test</button>
          </div>
    </form>';
}

?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.nahrat-subor').on('click', function(e) {
            e.preventDefault();
            $(this).parents('.math-parent').find('.upl').show();
            $(this).parents('.math-parent').find('.testInput').hide();
            if ($('.connect').length) {
                jsPlumb.repaintEverything();
            }
        });

        $('.vyraz').on('click', function(e) {
            e.preventDefault();
            $(this).parents('.math-parent').find('.upl').hide();
            $(this).parents('.math-parent').find('.testInput').show();
            $(this).parents('math-parent').find('.file-btn-math').val('');
            if ($('.connect').length) {
                jsPlumb.repaintEverything();
            }
        });

        $('.nahrat-subor-draw').on('click', function(e) {
            e.preventDefault();
            $(this).parents('.draw-parent').find('.upl-draw').show();
            $(this).parents('.draw-parent').find('.canvas').hide();
            $(this).parents('.draw-parent').find('.demoToolList').hide();
            if ($('.connect').length) {
                jsPlumb.repaintEverything();
            }

        });
        $('.skryt').on('click', function(e) {
            e.preventDefault();
            $(this).parents('.draw-parent').find('.upl-draw').hide();
            $(this).parents('.draw-parent').find('.canvas').show();
            $(this).parents('.draw-parent').find('.demoToolList').show();
            $(this).parents('.draw-parent').find('.file-btn-draw').val('');
            if ($('.connect').length) {
                jsPlumb.repaintEverything();
            }
        });

        $(window).on('resize', function() {
            if ($('.connect').length) {
                jsPlumb.repaintEverything();
            }
        })

        $(".odoslat").click(function(e) {
            $(this).attr('data-clicked', 'true');
            e.preventDefault();
            if ($(".odoslat").attr('data-clicked') === 'true') {
                <?php if (!file_exists('images/math_questions')) {
                    mkdir('images/math_questions', 0777, true);
                }
                if (!file_exists('images/drawing_questions')) {
                    mkdir('images/drawing_questions', 0777, true);
                } ?>
                sendTheTest();
                clearInterval(i);
                delete_cookie('timerMinutes');
                delete_cookie('timerSeconds');
                $('#showModal8').modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');
            }
        });

        function sendTheTest() {
            var data = new Array()
            data.push({
                "meno": "<?php echo $_SESSION['studentName'] ?>"
            })
            data.push({
                "priezvisko": "<?php echo $_SESSION['studentSurname'] ?>"
            })
            var left = new Array();
            var right = new Array();
            var rightText = new Array()

            $('.connect_right').each(function() {
                right.push($(this).val())
                left.push($(this).attr("id"))
            })
            let i = 0
            $('.testInput').each(function() {
                if ($(this)[0].classList.contains('checkbox')) {
                    data.push({
                        "zaznam": [{
                            "text": $(this).attr("id") + ""
                        }, {
                            data: $(this)[0].checked
                        }, {
                            "type": "checked"
                        }, {
                            "questionId": $(this).attr("name") + ""
                        }]
                    })
                }
                if ($(this)[0].classList.contains('connect')) {

                    data.push({
                        "zaznam": [{
                            "id": $(this).attr("id") + ""
                        }, {
                            "left": left[i]
                        }, {
                            "type": "connect"
                        }, {
                            "right": right[i]
                        }
                        ]
                    })
                    i++
                }
                if ($(this)[0].classList.contains('math')) {
                    if ($(this).css('display') == 'block') {
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
                    } else {
                        data.push({
                            "zaznam": [{
                                "id": $(this).attr("id") + ""
                            }, {
                                data: "images/math_questions/" + <?php echo  json_encode($_SESSION['studentName']) ?> + "_" + <?php echo json_encode($_SESSION['studentSurname']) ?> + "_" + <?php echo json_encode($_SESSION['testCode']) ?> + '_' + $(this).attr("id") + ".jpg"

                            }, {
                                "type": "img"
                            }]
                        })
                        var math = $(this).parents('.math-parent').find('.file-btn-math').prop('files')[0];
                        if (math) {
                            var form_data_math = new FormData();
                            form_data_math.append('file', math);
                            form_data_math.append('id', $(this).attr("id"));
                        }
                        if (form_data_math) {
                            $.ajax({
                                url: 'uploadFileMath.php',
                                method: "POST",
                                contentType: false,
                                processData: false,
                                data: form_data_math,
                                success: function(data) {}
                            })
                        }
                    }
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
                            data: "images/drawing_questions/" + <?php echo  json_encode($_SESSION['studentName']) ?> + "_" + <?php echo json_encode($_SESSION['studentSurname']) ?> + "_" + <?php echo json_encode($_SESSION['testCode']) ?> + '_' + $(this).attr("name") + ".jpg"
                        }]
                    })

                    var draw = $(this).parents('.draw-parent').find('.file-btn-draw').prop('files')[0];
                    if (draw) {
                        var form_data_draw = new FormData();
                        form_data_draw.append('file', draw);
                        form_data_draw.append('id', $(this).attr("id"));
                    }

                    if (form_data_draw) {
                        $.ajax({
                            url: 'uploadFileDraw.php',
                            method: "POST",
                            contentType: false,
                            processData: false,
                            data: form_data_draw,
                            success: function(data) {}
                        })
                    } else {
                        var dataURL = $('#canvas-'+ $(this).attr("id"))[0].toDataURL("image/jpeg", 1);
                        var id = $(this).attr("id");
                        $.ajax({
                            type: "post",
                            url: "student.php",
                            data: {
                                img_draw: dataURL,
                                id: id
                            },
                            success: function(data) {}
                        })
                    }
                }

            })

            $.ajax({
                url: "controllers/addTestAnswer.php",
                method: "POST",
                cache: false,
                data: JSON.stringify(data),
                success: function(result) {}
            });
        }

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
            date.setTime(date.getTime() + (iTimeMinutes + 10) * 60 * 1000);
            var expires = "; expires= " + date.toGMTString();
            document.cookie = name + "=" + value + expires + "; path=/";
        }

        if (checkCookie('timerMinutes') && checkCookie("timerSeconds")) {
            iTimeMinutes = parseInt(getCookie('timerMinutes'), 10);
            iTimeSeconds = parseInt(getCookie('timerSeconds'), 10);
        }

        var i = setInterval(function() {
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
            if ((iTimeMinutes === 0 && iTimeSeconds === 0)) {
                delete_cookie('timerMinutes');
                delete_cookie('timerSeconds');
                sendTheTest();
                $('#showModal7').modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');

                clearInterval(i);
            } else {
                if (iTimeSeconds === 0) {
                    iTimeMinutes--;
                    iTimeSeconds = 60;
                }
                iTimeSeconds--;
            }

        }, 1000);


        $(document).on('change', '.up', function() {
            var names = [];
            var length = $(this).get(0).files.length;
            for (var i = 0; i < $(this).get(0).files.length; ++i) {
                names.push($(this).get(0).files[i].name);
            }
            if (length > 2) {
                var fileName = names.join(', ');
                $(this).closest('.form-group').find('.form-control').attr("value", length + " files selected");
            } else {
                $(this).closest('.form-group').find('.form-control').attr("value", names);
            }
        });
    });
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
                    <button class="btn btn-success btn-block text-center bestModalBtn" type="submit" id="theModalButton" name="theModalButton">Zavrieť</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="showModal8" class="modal fade text-center">
    <div class="modal-dialog modal-confirm text-center">
        <div class="modal-content text-center">
            <div class="modal-header text-center">
                <div class="icon-box2">
                    <i class="bi bi-check2"></i>
                </div>
                <h4 class="modal-title text-center">Test bol odoslaný !</h4>
            </div>
            <div class="modal-body text-center">
                <p class="text-center">Váš test s odpoveďami bol odoslaný !</p>
            </div>
            <div class="modal-footer text-center">
                <form method="post" action="">
                    <button class="btn btn-success btn-block bestModalBtn" type="submit" id="theModalButtonTest" name="theModalButtonTest">Zavrieť</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php

include "partials/footer.php";
?>