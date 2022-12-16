<?php

require_once "../res/config.php";

$usrName = $usrName_err = "";
$password = $password_err = "";
$confirm_password = $confirm_password_err = "";
$log1 = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate username
    if(empty(trim($_POST["usrName"]))){
        $usrName_err = "Please enter a user name!";
    } elseif(strlen(trim($_POST["usrName"])) < 5 || strlen(trim($_POST["usrName"])) > 20 ){
        $usrName_err = "The user name must be between 5 and 20 characters long";
    } else{
        // Prepare a select statement
        $sql = "SELECT * FROM user WHERE usrName = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_usrName);

            // Set parameters
            $param_usrName = trim($_POST["usrName"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // store result
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $usrName_err = "This user name is already in use.";
                } else{
                    $usrName = trim($_POST["usrName"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate Username
    if(empty(trim($_POST["usrName"]))){
        $usrName_err = "Please Enter a Username.";
    } else{
        $usrName = trim($_POST["usrName"]);
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a Password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "The password needs to be at leas 6 characters long.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm your password";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "The passwords are not identical.";
        }
    }

    // Check input errors before inserting in database
    if(empty($usrName_err) && empty($password_err) && empty($confirm_password_err)){

        $param_usrName = $usrName;
        $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

        $query = "INSERT INTO user (
                                usrName,
                                usrPWD_hash) 
                              VALUES ('$param_usrName', 
                                      '$param_password')";

        if(!mysqli_query($link, $query)) {
            $log1 = "Es ist ein schwerwiegender Fehler aufgetreten, bitte Andy für Unterstützung anfordern.";
        } else {
            $log1 = "Register Successful.";
            //sleep(2);
            //echo "<meta http-equiv='refresh' content='0'>";
        }
    }
    
    // Close connection
    mysqli_close($link);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>

<body>
    <div class="wrapper">
        <h2>Register</h2>

        <form method="post">
            <table>
                <tr>
                    <td>User Name</td>
                    <td>
                        <label>
                            <input type="text" name="usrName" class="form-control <?php echo (!empty($usrName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $usrName; ?>">
                        </label>
                        <span class="invalid-feedback"><?php echo $usrName_err; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td>
                        <label>
                            <input type="text" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                        </label>
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td>
                        <label>
                            <input type="text" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                        </label>
                        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                    </td>
                </tr>
            </table>

            <input type="submit" class="btn btn-primary" value="Register User">
        </form>
        <p><?php echo $log1; ?></p>
    </div>    
</body>
</html>