<?php
//Database credentials.
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'vr');
define('DB_PASSWORD', 'REDACTED'); //Password redacted, for security purposes
define('DB_NAME', 'qr_game');

/* Attempt to connect to MySQL database */

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    echo("ERROR: Could not connect. " . mysqli_connect_error());
}
//echo "connected successfully";
