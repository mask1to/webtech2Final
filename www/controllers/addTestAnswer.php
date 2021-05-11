<?php
include "../config/config.php";
include "../queries/queries.php";
$strRequest = file_get_contents('php://input');
$Request = json_decode($strRequest);

// Prve dve su meno a priezvisko
$user_id = getUserId($conn, $Request[0]->meno, $Request[1]->priezvisko  );
$selectedData = mysqli_fetch_assoc($user_id);

for ($i = 2; $i < count($Request); $i++) {
        // type setnuty zatial iba pri checked
        if (isset($Request[$i]->zaznam[2]->type)){
            var_dump($Request[$i]->zaznam[0]->id);// option_id
            var_dump($Request[$i]->zaznam[1]->data);// checked

        }
          var_dump($Request[$i]->zaznam[0]->id);// id
          var_dump($Request[$i]->zaznam[1]->data);// value
        insertAnswer( $conn ,
            intval($Request[$i]->zaznam[0]->id),
            $Request[$i]->zaznam[1]->data,
            0 ,
            intval($selectedData),
            0);
}
echo 'ok';