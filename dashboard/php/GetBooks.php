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
    $title = $filters["title"];
    $author = $filters["author"];
    $publisher = $filters["publisher"];
    $sql = "SELECT * FROM book1 , book2, image WHERE book1.BOOK_ID = book2.BOOK_ID AND book1.BOOK_ID = image.BOOK_ID AND book1.TITLE LIKE \"$title%\" AND book1.AUTHOR LIKE \"$author%\" AND book1.PUBLISHER LIKE \"$publisher%\"";      // AND book1.TITLE LIKE '%$filters['title']%' AND book1.AUTHOR LIKE '%J%'
    $result=mysqli_query($conn,$sql);
    $responsedata= [];
    while($row = mysqli_fetch_assoc($result)){
        array_push($responsedata, $row);
    }
    return json_encode($responsedata);
}

function GetBookDataWithId($bookId){
 
    global $conn;
    $sql = "SELECT * FROM book1 , book2 , details WHERE book1.BOOK_ID = book2.BOOK_ID AND book1.BOOK_ID = details.BOOK_ID AND book1.BOOK_ID= $bookId";
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