<?php


$username = "root";
$password = "";
$servername = "localhost";
$dbname = "lms";
$url = "localhost/LMS";
$conn = mysqli_connect($servername,$username,$password,$dbname);
if(!$conn)
    die("Error in Database Coonectivity");

?>