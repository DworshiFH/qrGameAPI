<?php
/*// Initialize the session

// Check if the user is already logged in, if yes then redirect him to index page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if($_SESSION["istArbeiter"] == "1" && $_SESSION["istAdmin"] == "0"){
        header("location: ../zeiterfassung/manuelleEintraegeEintragungsmaske.php");
    } else if(!$_SESSION["istArbeiter"] =="0" && $_SESSION["istAdmin"] == "1"){
        header("location: ../administration/index.php");
    } else {
        header("location: ../zeiterfassung/index.php");
    }
    exit;
}*/

require_once "../res/config.php";

// Define variables and initialize with empty values
$usrName = $usrName_err = $login_err = "";
$usrPwd = $usrPwd_hash_err = $usrPwd_hash = "";

function returnJson($name, $data){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array($name => $data));
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "GET"){

    // Check if username is empty
    if(empty(trim($_GET["usrName"]))){
        $usrName_err = "Please enter username.";
        returnJson("error", $usrName_err);
    } else{
        $usrName = trim($_GET["usrName"]);
    }

    // Check if password is empty
    if(empty(trim($_GET["usrPwd"]))){
        $password_err = "Please enter your password.";
        returnJson("error", $password_err);
    } else{
        $usrPwd = trim($_GET["usrPwd"]);
    }

    // Validate credentials
    if(empty($usrName_err) && empty($password_err)){
        $sql = "SELECT * FROM user WHERE usrName = ?";

        if($stmt = mysqli_prepare($link, $sql)){

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $usrName);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $usrID, $usrName, $hashed_password);

                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($usrPwd, $hashed_password)){

                            //if correct, encrypt the userName, the encrypted userName acts as an API key.
                            require_once "../res/usernameCipherVals.php";
                            $encryption = openssl_encrypt($usrName, $ciphering, $encryption_key, $options, $encryption_iv);

                            returnJson("token", $encryption);

                        } else{
                            // Password is not valid, return a generic error message
                            returnJson("error", "Invalid username or password1.");
                        }
                    }
                } else{
                    // Username doesn't exist, return a generic error message
                    returnJson("error", "Invalid username or password2.");
                }
            } else{
                returnJson("error", "Oops! Something went wrong. Please try again later.");
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}


?>
