<?php

require "res/config.php";

function returnJson($name, $data){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array($name => $data));
}

if($_SERVER["REQUEST_METHOD"] == "GET"){

    $sql = "SELECT usrName FROM user WHERE usrName = ?";

    if($stmt = mysqli_prepare($link, $sql)){

        require_once "res/usernameCipherVals.php";

        $token = $_GET["token"];

        $param_usrName = openssl_decrypt($token, $ciphering, $encryption_key, $options, $encryption_iv);

        mysqli_stmt_bind_param($stmt, "s", $param_usrName);

        if(mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) == 1) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $usrName);
                if(mysqli_stmt_fetch($stmt)) {
                    //if the user is in the DB, the user has been authenticated

                    $retreiveSql = "SELECT * FROM qr";

                    $query = mysqli_query($link, $retreiveSql);

                    echo json_encode(mysqli_fetch_array($query));
                }
            }
        }
    }
}