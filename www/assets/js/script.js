$(document).ready(function()
{
    $('#showModal').modal('show');
    $.ajax({
        type: "post",
        url: "addTeacher.php",
        success:function (data)
        {
            console.log("1");
        }
    });

    $('#showModal2').modal('show');
    $.ajax({
        type: "post",
        url: "admin.php",
        success:function (data)
        {
            if(data)
            {
                console.log("1");
            }
        }
    });

    $('.toggle').on('click', function() {
        $('.container').stop().addClass('active');
    });

    $('.close').on('click', function() {
        $('.container').stop().removeClass('active');
    });
});



