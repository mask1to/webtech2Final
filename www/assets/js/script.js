$(document).ready(function () {


    $('#showModal').modal({ backdrop: 'static', keyboard: false }, 'show');
    $.ajax({
        type: "post",
        url: "teacher.php",
        success: function (data)
        {

        }
    });

    $('#showModal2').modal({ backdrop: 'static', keyboard: false }, 'show');
    $.ajax({
        type: "post",
        url: "index.php",
        success: function (data)
        {

        }
    });

    $('#showModal3').modal({ backdrop: 'static', keyboard: false }, 'show');
    $.ajax({
        type: "post",
        url: "teacher.php",
        success: function (data)
        {

        }
    });

    $('#theButtonTeacher').on('click', function ()
    {
        location.href = "teacher.php";
    });


    $('.toggle').on('click', function () {
        $('.container').stop().addClass('active');
    });

    $('.close').on('click', function () {
        $('.container').stop().removeClass('active');
    });


});



