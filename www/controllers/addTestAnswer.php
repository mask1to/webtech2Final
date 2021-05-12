<?php
session_start();
include "../config/config.php";
include "../queries/queries.php";
$strRequest = file_get_contents('php://input');
$Request = json_decode($strRequest);

// Prve dve su meno a priezvisko
$user_id = getUserId($conn, $Request[0]->meno, $Request[1]->priezvisko, $_SESSION['testCode']);
$selectedData = mysqli_fetch_assoc($user_id);

for ($i = 2; $i < count($Request); $i++) {
        if (isset($Request[$i]->zaznam[2]->type)){
            $text = $Request[$i]->zaznam[0]->text;// text
            $checked = $Request[$i]->zaznam[1]->data;// checked

            if ($checked){
                $checked = 1;
            }else{
                $checked =0;
            }
            $questionId = $Request[$i]->zaznam[3]->questionId;// checked
//            var_dump($Request);

            insertAnswer( $conn ,
                intval( $questionId),
                $text,
                $checked ,
                intval($selectedData['id']),
                0);

        }else{
            var_dump($Request[$i]->zaznam[0]->id);// id
            var_dump($Request[$i]->zaznam[1]->data);// value
            insertAnswer( $conn ,
                intval($Request[$i]->zaznam[0]->id),
                $Request[$i]->zaznam[1]->data,
                0 ,
                intval($selectedData['id']),
                0);
        }

}

