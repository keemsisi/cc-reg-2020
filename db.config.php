<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cc_reg_2020";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
if ($conn->query("SELECT * FROM cc_reg_2020.attendees")) {
} else {
    echo "ERROR_OCCURED_WHILE_QUERYING ::: The table does not exists<br/> " . $conn->error;
}