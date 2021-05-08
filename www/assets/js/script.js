$(document).ready(function()
{

    $('#showModal').modal({backdrop: 'static', keyboard: false},'show');
    $.ajax({
        type: "post",
        url: "addTeacher.php",
        success:function (data)
        {
            console.log("1");
        }
    });

    $('.toggle').on('click', function() {
        $('.container').stop().addClass('active');
    });

    $('.close').on('click', function() {
        $('.container').stop().removeClass('active');
    });
});



