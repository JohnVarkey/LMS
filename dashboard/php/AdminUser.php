<?php

require_once '../../config.php';

function GetAllBorrowersAssigned(){

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


function GetAllBorrowedBooksByUserId(){

    global $conn;
    $userId = getEscapedString($_GET["userId"]);
    $sql = "SELECT BORROW_ID, user.USER_ID, EMAIL, NAME, TITLE, ISSUE_DATE, RETURN_DATE, STATUS FROM borrow , user , book1 where user.USER_ID=borrow.USER_ID And book1.BOOK_ID=borrow.BOOK_ID  And borrow.USER_ID = $userId"; 
    $result = mysqli_query($conn, $sql);
    $responseArray = [];
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            array_push($responseArray, $row);
        }
    }
    return json_encode($responseArray);
}

function NextBookDateByBookId(){

    global $conn;
    $bookId = getEscapedString($_GET["bookId"]);
    $sql = "SELECT MIN(RETURN_DATE) AS RETURN_DATE FROM borrow WHERE BOOK_ID=$bookId";
    
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return json_encode($row);
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


    $sql = "INSERT INTO `borrow` (`USER_ID`, `BOOK_ID`, `RETURN_DATE` ) VALUES ( $userId, $bookId, DATE_ADD(CURRENT_DATE(), INTERVAL 1 WEEK))";
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
            echo GetAllBorrowersAssigned();
            break;
        case 2:
            echo ChangeBorrowStatus();
            break;
        case 3:
            echo AssignBookToUser();
            break;
        case 4:
            echo GetAllBorrowedBooksByUserId();
            break;
        case 5:
            echo NextBookDateByBookId();
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