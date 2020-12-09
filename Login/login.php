<?php

require_once '../config.php';

$email = getEscapedString($_POST['name']);
$password =getEscapedString($_POST['password']);
// sets flag is error occured.  TRUE => Error
$flag=True; 

$sql = "SELECT * FROM `user` WHERE `Email`='$email'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){
    $flag=True;
}else{
    
    while($row = mysqli_fetch_assoc($result)){
        
        // password_verify verifies the hashed password in db and the input password from user
        if(password_verify($password, $row['PASSWORD']) == true){ 
            $flag=False;
            break;
            //echo json_encode($row);
        }
    }
}

if($flag){
    $responseCode = array(
        "code"=>403,
        "message"=> $password
        );
}else{
    if($row['ROLE']=="1")
        $responseCode = array(
            "code"=>200,
            "message"=> array(
                "id"=>$row["USER_ID"],
                "role"=>"a"
            )
        );
    else
        $responseCode = array(
            "code"=>200,
            "message"=> array(
                "id"=>$row["USER_ID"],
                "Role"=>"u"
            )
        );
}
    echo json_encode($responseCode);


mysqli_close($conn);






