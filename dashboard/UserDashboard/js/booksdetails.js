(function($){
    const filepath = "http://localhost/LMS/Assets/book_images/";

    const checkLogin = () =>{
      const user = localStorage.getItem("UserId");
      if(user==null)
          location.replace("http://localhost/LMS/Login/login.html");
          //console.log("herer");
    }
    
    checkLogin();

    function getIdFromAddress(){
        
        var query = window.location.search.substring(1);
        var parms = query.split('&');
        var pos = parms[0].indexOf('=');
        if (pos > 0) {
            var key = parms[0].substring(0, pos);
            var val = parms[0].substring(pos + 1);
        }
        if(key=="Id")
            return val;
    }

    getReview = (Id, bookdata)=>{
        $.get(
            "../php/GetBooks.php",
            {
                op: 4,
                bookId: Id
            },
                function (response) {
                    const { code, ...reviewdata} = JSON.parse(response);
                    populateTable(bookdata,reviewdata);
                    
            }
        )
    }
    
    const tabledata= () => {
        const Id=getIdFromAddress();
        
        $.get(
            "../php/GetBooks.php",
            {
                op: 3,
                bookId: Id
            },
                function (response) {
                    const data = JSON.parse(response);
                    getReview(Id,data.message);
                    
            }
        )
    }
    tabledata();

 
    const populateTable = (bookdata,review)=>{
        console.log(bookdata,review);
        

        //hidden text input for review posting;
        $("#bookid").val(bookdata.BOOK_ID);
        //adding title
        $("#bookTitle h2").text(bookdata.TITLE);

        //adding subtitle
        var subtitle = `${bookdata.AUTHOR}, ${bookdata.PUBLISHER}, ${bookdata.RELEASE_DATE}`;
        $("#bookTitle p").text(subtitle);
        //adding image
        var image = `${filepath}${bookdata.IMAGE_URL}`;
        $("#bookImage img").attr("src",image);
        //adding synopsis
        $("#synopsis p").text(bookdata.DESCRIPTION);        
        //adding Rating
        const rating = parseFloat(review.rating.rating).toFixed(1);
        $("#rating").text(`${rating}/5.0`);
        //adding Review

        const parentEle = $("#review");
        const Review = review.review;
        Review.forEach((row)=>{
            let childDom = `<p>${row.NAME}: ${row.review}</p>`;
            parentEle.append(childDom);
        });
    }


    $("#reviewForm").submit((e)=>{
        e.preventDefault();
        const payload = {
            op: 6,
            userId:$("#userId").val(),
            bookId: $("#bookid").val(),
            rating:4.5,
            review: $("#message").val()

        }
        
        $.post(
            "../php/UploadBook.php",
            payload,
            function (response) {
                const data = JSON.parse(response);
                alert(data.message);
            
            }
        )
        
    })

})(jQuery)