<?php

require_once '../../config.php';

function GetAllData(){

    global $conn;
    $sql = "SELECT * FROM book1 , book2, image , details WHERE book1.BOOK_ID = book2.BOOK_ID AND book1.BOOK_ID = image.BOOK_ID AND book1.BOOK_ID = details.BOOK_ID  ";
    $result=mysqli_query($conn,$sql);
    $responsedata= [];
    while($row = mysqli_fetch_assoc($result)){
        array_push($responsedata, $row);
    }
    return json_encode($responsedata);
}

function GetDataWithFilter($filters){

    global $conn;
    $title = getEscapedString($filters["title"]);
    $author = getEscapedString($filters["author"]);
    $publisher = getEscapedString($filters["publisher"]);
    $sql = "SELECT * FROM book1 , book2, image WHERE book1.BOOK_ID = book2.BOOK_ID AND book1.BOOK_ID = image.BOOK_ID AND book1.TITLE LIKE \"$title%\" AND book1.AUTHOR LIKE \"$author%\" AND book1.PUBLISHER LIKE \"$publisher%\"";      
    $result=mysqli_query($conn,$sql);
    $responsedata= [];
    while($row = mysqli_fetch_assoc($result)){
        array_push($responsedata, $row);
    }
    return json_encode($responsedata);
}


function GetBookDataWithId($bookId){
 
    global $conn;
    $bookId= getEscapedString($bookId);
    $sql = "SELECT * FROM book1 , book2 , details, image WHERE book1.BOOK_ID = book2.BOOK_ID AND book1.BOOK_ID = details.BOOK_ID AND book1.BOOK_ID= $bookId AND image.BOOK_ID= book1.BOOK_ID";
    if($result=mysqli_query($conn,$sql)){
        $row = mysqli_fetch_assoc($result);
        $responseCode = array(
            "code"=> 200,
            "message"=> $row
        );
        return json_encode($responseCode);
    }else{
        $responseCode = array(
            "code"=> 404,
            "message"=> mysqli_error($conn)
        );
        return json_encode($responseCode);
    }
}
function getReviewswithId($bookId){

    global $conn;
    $bookId= getEscapedString($bookId);
    $sql = "SELECT NAME, review FROM review, user WHERE review.BOOK_ID=$bookId AND user.USER_ID=review.USER_ID";
    $review = []; 
    if($result=mysqli_query($conn,$sql)){
        while($row = mysqli_fetch_assoc($result)){
            array_push($review,$row);
        }
    }
    return $review;

}


function GetRatingAndReviewWithId($bookId){

    global $conn;
    $bookId= getEscapedString($bookId);
    $sql = "SELECT AVG(`rating`) as rating FROM `review` WHERE `BOOK_ID`=$bookId";
    $reviews = getReviewswithId($bookId);
    if($result=mysqli_query($conn,$sql)){
        $row = mysqli_fetch_assoc($result);
        $responseCode = array(
            "code"=> 200,
            "rating"=> $row,
            "review"=> $reviews
        );
        return json_encode($responseCode);
    }else{
        $responseCode = array(
            "code"=> 404,
            "message"=> mysqli_error($conn)
        );
        return json_encode($responseCode);
    }

}


if(isset($_GET['op'])){

    switch($_GET['op']){
        case 1:
            echo GetAllData();
            break;
        case 2:
            echo GetDataWithFilter($_GET['filter']);
            break;
        case 3:
            echo GetBookDataWithId($_GET["bookId"]);
            break;
        case 4:
            echo GetRatingAndReviewWithId($_GET["bookId"]);
            break;
        default:
            $responseCode = array(
                "code"=> 404,
                "message"=> "OP Error"
            );
            echo json_encode($responseCode);
            break;
    }
}else{
    echo "Error Occured";
}
?>