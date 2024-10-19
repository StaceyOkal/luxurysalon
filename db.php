<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "salon";

//create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//connecion error
if ($conn->connect_error) {

    die("connecion failed:" . $conn->connect_error);
}
