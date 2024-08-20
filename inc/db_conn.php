<?php
$servername = "hopeforevangelism.com.mysql";
$username = "hopeforevangelism_com";
$password = "help1HELP!";
$dbname = "hopeforevangelism_com";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>