<?php
session_start();
if (!isset($_SESSION["loggedin"])) {
    header("location: index.php");
}
include "partials/header.php";
include "config/config.php";
?>

<main class="pt-4 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-10 col-md-8 offset-sm-1 offset-md-2">
                <div class="w-100 mb-4 text-right addTestBtn">
                    <a href="addTest.php" class="btn btn-primary d-inline-block">Pridať test</a>
                </div>
                <?php
                $sql = "SELECT * FROM test";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="test-item d-flex align-items-center justify-content-between" data-id="' . $row['id'] . '">
                        <div>
                            <p class="name">' . $row['name'] . '</p>
                            <p class="code mb-0">Kód: ' . $row['test_code'] . '</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <label class="switch">' . ($row['isActive'] ? ('<input type="checkbox" checked>') : ('<input type="checkbox">')) . '<span class="slider round"></span>
                            </label>
                            <a href="#" class="ml-4 mr-4 student-list"><i class="bi bi-person-lines-fill"></i></a>
                            <a href="#" class="delete"><i class="bi bi-trash-fill"></i></a>
                        </div>
                    </div>';
                    }
                } else {
                    echo '<div class="test-item d-flex align-items-center justify-content-between">
                        <p class="name">Nie je vložený žiadny test</p>
                    </div>';
                }
                ?>
            </div>
        </div>
    </div>
</main>

<script>
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
</script>

<?php
include "partials/footer.php";
?>