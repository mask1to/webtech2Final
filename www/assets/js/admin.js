$(document).ready(function() {
    $('.switch input').on('change', function() {
        var check = 0,
            id;
        id = $(this).parents('.test-item').data('id');
        if ($(this).is(':checked')) {
            check = 1;
        }

        console.log(id);

        $.ajax({
            url: 'controllers/updateActive.php',
            method: 'POST',
            cache: false,
            data: {
                id: id,
                check: check
            }
        })
    });

    $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        var parent = $(this).parents('.test-item'),
            id = parent.data('id');
        $.ajax({
            url: 'controllers/deleteTest.php',
            method: 'POST',
            cache: false,
            data: {
                id: id
            },
            success: function(result) {
                if (result > 0) {
                    parent.slideToggle("fast", function() {
                        parent.remove();
                        if ($('.test-item').length == 0) {
                            console.log('ddd');
                            var item = ($('<div class="test-item d-flex align-items-center justify-content-between">' +
                                '<p class="name">Nie je vložený žiadny test</p>' +
                                '</div>'));
                            item.insertAfter('.addTestBtn').slideDown("fast");
                        }
                    })
                }
            }
        });
    })
})