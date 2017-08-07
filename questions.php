<?php 
session_start();


$servername = "localhost";
$admin = "root";
$password = "";
$dbname = "quiz_system";


$conn = new mysqli($servername, $admin, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully 	";


 ?>