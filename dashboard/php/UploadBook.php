<?php

require_once '../../config.php';

$file_path = "../../Assets/book_images/";



/**
 * @desc: checks if the query was successfully excecuted. throws Exception if Error
 * @input: $result=> the $result object after excucution of a query
 */
function checkerr($conn,$result){
   if(!$result)
   {
      $queryerror = mysqli_error($conn);
      if(stripos($queryerror,"Duplicate Entry")!==false)
      {
         if(stripos($queryerror,"-")!==false)
         //Book_Title - Book_Author Primary key Duplicate error
            $responseCode = array(
               "code"=>403,
               "message"=> "Duplicate Entry for Title and Author "
            );
         else
         //Book_Id Primary Key Duplicate Error
            $responseCode = array(
               "code"=> 403,
               "message"=> "Duplicate Entry for Book_Id"
            );
      }
      else
      {
         $responseCode = array(
            "code"=> 500,
            "message"=> $queryerror
         );
      }
      echo json_encode($responseCode);
      throw new Exception("QueryError");
   }
}




function UploadImage(){
   global $conn, $file_path;

   if(isset($_POST["id"])){

      $id= getEscapedString($_POST["id"]);
      /* Getting file name */
      $filename = $id.getEscapedString($_FILES['file']['name']);
      /* Location */
      $location = $file_path.$filename;  
      $uploadOk = 1;
      $imageFileType = pathinfo($location,PATHINFO_EXTENSION);

      /* Valid Extensions */
      $valid_extensions = array("jpg","jpeg","png");
      
      /* Check file extension */
      if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
         $uploadOk = 0;
      }

      if($uploadOk == 0){
         $responseCode = array(
         "code"=> 401,
         "message"=> "Image Extension not Valid. [jpg,jpeg,png]"
      );
      }else{
         /* Upload file */
         if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){

            $responseCode = array(
               "code"=> 201,
               "message"=> array(
                  "filename"=>$filename
               )
            );
         }else{
            $responseCode = array(
               "code"=> 500,
               "message"=> "some Error Occured"
            );
         }
      }
   }else{
      $responseCode = array(
         "code"=> 401,
         "message"=> "Book Id not found"
      );
   }
   echo json_encode($responseCode);
}






function UploadBookToDB(){
   global $conn;
   $bookTitle=getEscapedString($_GET["bookTitle"]);
   $bookId=getEscapedString($_GET["bookId"]);
   $bookAuthor=getEscapedString($_GET["bookAuthor"]);
   $bookPublisher=getEscapedString($_GET["bookPublisher"]);
   $bookCopies=getEscapedString($_GET["bookCopies"]);
   $bookImageUrl=getEscapedString($_GET["bookImageUrl"]);
   $bookReleaseDate=getEscapedString($_GET["bookReleaseDate"]);
   $bookDescription=getEscapedString($_GET["bookDescription"]);
   $bookPrice=getEscapedString($_GET["bookPrice"]);
   $bookEdition = getEscapedString($_GET["bookEdition"]);
   $date = date('Y-m-d', strtotime($bookReleaseDate));

   /**
    * sets the auto commit feature to false. now the query must be commited at the end
    */
   mysqli_autocommit($conn,FALSE);

   /**
    * For catching any error that may occur while executing the queries 
    */
   $flag=0;
   try{
      
      $result=mysqli_query($conn,"INSERT INTO book1 VALUES ($bookId, '$bookTitle', '$bookAuthor','$bookPublisher')");
      checkerr($conn,$result);
    
      $result=mysqli_query($conn,"INSERT INTO book2 VALUES ($bookId, '$bookTitle', '$bookAuthor',$bookCopies,$bookCopies )");
      checkerr($conn,$result);
    
      $result=mysqli_query($conn,"INSERT INTO `image` VALUES ($bookId,'$bookImageUrl')");
      checkerr($conn,$result);
     

      $result=mysqli_query($conn,"INSERT INTO details VALUES ($bookId, $bookPrice, '$date', '$bookEdition', '$bookDescription')");
      checkerr($conn,$result);

   }catch(Exception $e){

      $flag=1;
      mysqli_rollback($conn);
   }finally{

      //Commiting the Transaction
      if(mysqli_commit($conn) && $flag==0){
         $responseCode = array(
            "code"=>201,
            "message"=> "Successfull"
         );
         echo json_encode($responseCode);
      }
   }
 mysqli_close($conn);
} 



function GetCardData(){
   global $conn;
   $sql = "SELECT \n"

    . "	(SELECT COUNT(*) FROM book1) AS books, \n"

    . "    (SELECT COUNT(*) FROM user WHERE ROLE = 2) AS users, \n"

    . "    (SELECT COUNT(*) FROM borrow WHERE STATUS=\"Assigned\") AS borrowers";
    
   $result=mysqli_query($conn,$sql);
   $row = mysqli_fetch_assoc($result);
   echo json_encode($row);

}





function DeleteBookFromServer($path){
   global $file_path;
   $file_pointer= $file_path.$path;
   if(!unlink($file_pointer)){  
      return false;
   }else{
      return true;
   }
}





function DeleteBookWithId(){
   global $conn;

   $bookId = getEscapedString($_POST['bookId']);
   mysqli_autocommit($conn,FALSE);

   try{

      //get Filename from DB
      $row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IMAGE_URL as image FROM `image` WHERE BOOK_ID= $bookId"));
      
      //Deleting Image from Server
      if(!DeleteBookFromServer($row["image"])) {  
         throw new Exception("Deletion Error");
      }else{    
         mysqli_query($conn,"DELETE FROM `image` WHERE BOOK_ID= $bookId");
         mysqli_query($conn,"DELETE FROM details WHERE BOOK_ID= $bookId");
         mysqli_query($conn,"DELETE FROM book2 WHERE BOOK_ID= $bookId");
         mysqli_query($conn,"DELETE FROM book1 WHERE BOOK_ID= $bookId");
         
         //Commiting the Transaction
         mysqli_commit($conn);
         $responseCode = array(
            "code"=>201,
            "message"=> "Successfull"
         );
      }  
   }catch(Exception $e){

      $flag=1;
      mysqli_rollback($conn);
      $responseCode = array(
         "code"=>404,
         "message"=> $e
      );
   }
      
   echo json_encode($responseCode);
   mysqli_close($conn);
}





function EditBookInDB(){

   global $conn;
   $bookTitle=getEscapedString($_GET["TITLE"]);
   $bookId=getEscapedString($_GET["BOOK_ID"]);
   $bookAuthor=getEscapedString($_GET["AUTHOR"]);
   $bookPublisher=getEscapedString($_GET["PUBLISHER"]);
   $bookCopies=getEscapedString($_GET["COPIES"]);
   $bookReleaseDate=getEscapedString($_GET["RELEASE_DATE"]);
   $bookDescription=getEscapedString($_GET["DESCRIPTION"]);
   $bookPrice=getEscapedString($_GET["PRICE"]);
   $bookEdition = getEscapedString($_GET["EDITION"]);
   $date = date('Y-m-d', strtotime($bookReleaseDate));

   /**
    * sets the auto commit feature to false. now the query must be commited at the end
    */
   mysqli_autocommit($conn,FALSE);

   /**
    * For catching any error that may occur while executing the queries 
    */
   $flag=0;
   try{
      
      $result=mysqli_query($conn,"UPDATE book1 SET TITLE = '$bookTitle', AUTHOR = '$bookAuthor', PUBLISHER = '$bookPublisher' WHERE BOOK_ID = $bookId");
      checkerr($conn,$result);
    
      $result=mysqli_query($conn,"UPDATE book2 SET TITLE = '$bookTitle', AUTHOR = '$bookAuthor', `COPIES` = $bookCopies WHERE BOOK_ID = $bookId");
      checkerr($conn,$result);
    
      $result=mysqli_query($conn,"UPDATE `details` SET RELEASE_DATE = '$date', EDITION = '$bookEdition', DESCRIPTION = '$bookDescription' WHERE BOOK_ID = $bookId");
      checkerr($conn,$result);

   }catch(Exception $e){

      $flag=1;
      mysqli_rollback($conn);
   }finally{

      //Commiting the Transaction
      if(mysqli_commit($conn) && $flag==0){
         $responseCode = array(
            "code"=>200,
            "message"=> "Successfull"
         );
      }else{
         $responseCode = array(
            "code"=>404,
            "message"=> mysqli_error($conn)
         );
      }
      echo json_encode($responseCode);
   }
 mysqli_close($conn);

}



function PostReview(){

   global $conn;
   $bookId=getEscapedString($_POST["bookId"]);
   $userId=getEscapedString($_POST["userId"]);
   $bookrating=getEscapedString($_POST["rating"]);
   $bookreview=getEscapedString($_POST["review"]);
   $sql = "INSERT INTO `review` (`BOOK_ID`, `USER_ID`, `rating`, `review`) VALUES ($bookId, $userId, $bookrating, '$bookreview')";
   if($result = mysqli_query($conn, $sql)){
      $responseCode = array(
         "code"=>201,
         "message"=> "Successfull"
      );
   }else{
      $responseCode = array(
         "code"=>404,
         "message"=> mysqli_error($conn)
      );
   }
   echo json_encode($responseCode);
}

/**
 * This is used to handle all the request coming to UploadBook.php
 * requests are differentiated using an op parameter passed along with the required data
 */
if(isset($_REQUEST["op"]))
   switch($_REQUEST["op"]){
      case 1:
         UploadImage();
         break;
      case 2:
         UploadBookToDB();
         break;
      case 3:
         GetCardData();
         break;
      case 4:
         DeleteBookWithId();
         break;
      case 5:
         EditBookInDB();
         break;
      case 6:
         PostReview();
         break;
      default:
         $responseCode = array(
            "code"=> 404,
            "message"=> "Not Found"
         );
         echo json_encode($responseCode);
}else{
   $responseCode = array(
      "code"=> 404,
      "message"=> "Not Found"
   );
   echo json_encode($responseCode);
}
   

