<?php

require_once '../../config.php';


function GetAllBorrowers(){

    global $conn;
    $username = getEscapedString($_GET["userName"]);
    $sql = "SELECT BORROW_ID, user.USER_ID, EMAIL, NAME, TITLE, RETURN_DATE, STATUS FROM borrow , user , book1 where user.USER_ID=borrow.USER_ID And book1.BOOK_ID=borrow.BOOK_ID And borrow.STATUS='Assigned' And NAME LIKE '$username%'";
    $result = mysqli_query($conn, $sql);
    $responseArray = [];
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            array_push($responseArray, $row);
        }
    }
    return json_encode($responseArray);
}


function ChangeBorrowStatus(){
    
    global $conn;
    
    $borrowId = getEscapedString($_POST["id"]);
    $sql = "UPDATE borrow SET STATUS= 'Returned' WHERE BORROW_ID = $borrowId";
    $result = mysqli_query($conn, $sql);
    if(!$result){
        $responseCode = array(
            "code"=>404,
            "message"=> mysqli_error($conn)
         );
    }
    else{
        $responseCode = array(
            "code"=>201,
            "message"=> "Successfull"
         );
    }
    return json_encode($responseCode);
    
}

function AssignBookToUser(){

    global $conn;
    $bookId = getEscapedString($_POST["bookId"]);
    $userId = getEscapedString($_POST["userId"]);
    $returnDate = getEscapedString($_POST["returnDate"]);
    $returnDate = date('Y-m-d', strtotime($returnDate));

    $sql = "INSERT INTO `borrow` (`USER_ID`, `BOOK_ID`, `RETURN_DATE` ) VALUES ( $userId, $bookId, '$returnDate' )";
    $result = mysqli_query($conn, $sql);
    if(!$result){
        $responseCode = array(
            "code"=>404,
            "message"=> mysqli_error($conn)
         );
    }
    else{
        $responseCode = array(
            "code"=>201,
            "message"=> "Successfull"
         );
    }
    return json_encode($responseCode);
}

/**
 * This is used to handle all the request coming to UploadBook.php
 * requests are differentiated using an op parameter passed along with the required data
 */
if(isset($_REQUEST["op"])){
    switch($_REQUEST["op"]){
        case 1:
            echo GetAllBorrowers();
            break;
        case 2:
            echo ChangeBorrowStatus();
            break;
        case 3:
            echo AssignBookToUser();
            break;
        default:
            $responseCode = array(
                "code"=> 500,
                "message"=> "Not Found"
            );
            echo json_encode($responseCode);
        }
}else{
    $responseCode = array(
        "code"=> 500,
        "message"=> "Not Found"
    );
   echo json_encode($responseCode);
}