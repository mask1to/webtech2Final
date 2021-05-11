<?php


function base64_to_jpeg($base64_string, $output_file) {
    // open the output file for writing
    $ifp = fopen( $output_file, 'wb' );

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode( ',', $base64_string );

    // we could add validation here with ensuring count( $data ) > 1
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );

    // clean up the file resource
    fclose( $ifp );

    return $output_file;
}

$student_name = $_SESSION['studentName'];
echo $student_name;
$student_surname = $_SESSION['studentSurname'];
$test_code = $_SESSION['testCode'];


if(isset($_POST['img_draw']) && $student_name && $student_surname && $test_code){
    //base64_to_jpeg($_POST['img_draw'], "images/drawing_questions/");

    echo "dsadsadasas";
    file_put_contents("images/drawing_questions/".$_SESSION['studentName']."_".$_SESSION['studentSurname']."_".$_SESSION['testCode'], file_get_contents($_POST['img_draw']));
    //file_put_contents("images/drawing_questions/".$_SESSION['studentName']."_".$_SESSION['studentSurname']."_".$_SESSION['testCode'], file_get_contents($_POST['img_draw']));
}



/*if(isset($_POST['upload']) && isset($_SESSION['studentName']) && isset($_SESSION['studentSurname']) && isset($_SESSION['testCode'])){
    $file_name = $_SESSION['studentName']."_".$_SESSION['studentSurname']."_".$_SESSION['testCode'];
    $file_type = $_FILES['file']['type'];
    $file_size = $_FILES['file']['size'];

    $new_name = strtolower(substr($_FILES['file']['name'], strpos($_FILES['file']['name'], '.')));

    $file_store = "images/math_questions/".$file_name.$new_name;
    $file_tem_loc = $_FILES['file']['tmp_name'];
    move_uploaded_file($file_tem_loc, $file_store);
}*/