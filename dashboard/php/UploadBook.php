<?php

require_once '../../config.php';

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
   global $conn;

   /* Getting file name */
   $filename = $_FILES['file']['name'];
   
   /* Location */
   $location = "../../Assets/book_images/".$filename;  
   $uploadOk = 1;
   $imageFileType = pathinfo($location,PATHINFO_EXTENSION);

   /* Valid Extensions */
   $valid_extensions = array("jpg","jpeg","png");
   
   /* Check file extension */
   if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
      $uploadOk = 0;
   }
   if($uploadOk == 0){
      echo 0;
   }else{
      /* Upload file */
      if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){

         $responseCode = array(
            "code"=> 201,
            "message"=> array(
               "filename"=>$filename
            )
         );
         echo json_encode($responseCode);
      }else{
         $responseCode = array(
            "code"=> 500,
            "message"=> "some Error Occured"
         );
         echo json_encode($responseCode);
      }
   }
}


function UploadToDB(){
   global $conn;
   $bookTitle=$_GET["bookTitle"];
   $bookId=$_GET["bookId"];
   $bookAuthor=$_GET["bookAuthor"];
   $bookPublisher=$_GET["bookPublisher"];
   $bookCopies=$_GET["bookCopies"];
   $bookImageUrl=$_GET["bookImageUrl"];
   $bookReleaseDate=$_GET["bookReleaseDate"];
   $bookDescription=$_GET["bookDescription"];
   $bookPrice=$_GET["bookPrice"];
   $bookEdition = $_GET["bookEdition"];
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
    
      $result=mysqli_query($conn,"INSERT INTO book2 VALUES ($bookId, '$bookTitle', '$bookAuthor',$bookCopies,1)");
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

    . "    (SELECT COUNT(*) FROM borrow WHERE STATUS=\"PENDING\") AS borrowers";
    
   $result=mysqli_query($conn,$sql);
   $row = mysqli_fetch_assoc($result);
   echo json_encode($row);

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
         UploadToDB();
         break;
      case 3:
         GetCardData();
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
   

