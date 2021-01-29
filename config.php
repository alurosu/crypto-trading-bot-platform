<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'c1pitmanbot');
define('DB_PASSWORD', 'smARN5!j3Ma');
define('DB_NAME', 'c1_pitmanbot');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$SMTPname = "Pitman Bot";
$SMTPserver = "smtp.gmail.com";
$SMTPport = 587;
$SMTPuser = "pitmanbot@gmail.com";
$SMTPpass = "1oeUG0qsR#d4";


?>
