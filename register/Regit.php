<?php

require_once '../config.php';

if($_POST['submit']){
    $email = getEscapedString($_POST['email']);
    $password = getEscapedString($_POST['password']);
    $name = getEscapedString($_POST['name']);
    $phone = getEscapedString($_POST['phone']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO `user` (`Email`, `NAME`, `PASSWORD`, `PHONE_NUMBER`) VALUES ( '$email', '$name', '$hashed_password','$phone')";
    if (mysqli_query($conn, $sql)) {
        header("Location: http://$url/Login/login.html"); 
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
}

mysqli_close($conn);
