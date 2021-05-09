$(document).ready(function () {

    $('#showModal').modal({ backdrop: 'static', keyboard: false }, 'show');
    $.ajax({
        type: "post",
        url: "addTeacher.php",
        success: function (data) {
        }
    });

    $('.toggle').on('click', function () {
        $('.container').stop().addClass('active');
    });

    $('.close').on('click', function () {
        $('.container').stop().removeClass('active');
    });
});

