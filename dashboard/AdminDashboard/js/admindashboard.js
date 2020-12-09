(function($) {

    const checkLogin = () =>{
        const user = localStorage.getItem("UserId");
        console.log("here")
        if(user==null)
            location.replace("http://localhost/LMS/Login/login.html");
            //console.log("herer");
    }
    checkLogin();

    const cardData = () =>{
        $.get(
            "../php/UploadBook.php" ,
            {
                op: 3
            },
            function (response) {

                const {users, borrowers, books} = JSON.parse(response);
                $("#totalUsers").text(users);
                $("#totalBorrowers").text(borrowers);
                $("#totalBooks").text(books);
            }
        )
    }

    cardData();

    const filepath = "http://localhost/LMS/Assets/book_images/";

    function displayToast(message) {
        var x = $("#snackbar");
        x.html(message);
        x.attr("class","show");
        setTimeout(function(){
                x.attr("class","");
            }
        ,3000);
    }

    $("#upload").click(function(e){
        e.preventDefault(); 
        var fd = new FormData();
        var files = $('#bookImage')[0].files[0];
        fd.append('op',1);
        fd.append('file',files);
        fd.append("id",$('#bookId').val());
        $.ajax({
            type: "POST",
            url:"../php/UploadBook.php" ,
            contentType: false,
            cache: false,
            processData:false,
            data: fd,
            success: function (response) {
                        console.log("response",response);
                        const data = JSON.parse(response);
                        console.log(data);
                        if(data.code==500)
                            console.log(data.message)
                            //displayToast(data.message);
                        else if(data.code==201){
                            var uploadedfile=data.message.filename;
                            $("#ImageBook").css("background-image", `url('${filepath+uploadedfile}')`); //filepath+uploadedfile
                            $("#bookImageUrl").attr("value",uploadedfile);
                            $("#upload").attr("type","hidden");
                            console.log("Success")
                            //displayToast("Book Image Uploaded Successfully");
                        }
                
            }
        });
    });

    $("form").submit(function(e){
        e.preventDefault();
        const form = $(this).serializeArray();
        form.push({name: "op", value: 2})
        const  url = $(this).attr('action');
        if($("#bookImageUrl").val()==""){
            displayToast("Upload Image");
        }else{
            $.get(
                url ,
                form,
                    function (response) {
                        console.log(response);
                        const data = JSON.parse(response);
                        console.log(data);
                        if(data.code==403)
                            displayToast(data.message);
                        else if(data.code==201)
                            displayToast("Book Added");
                        else{
                            displayToast(data.message);
                        }
                }
            )
        }
         
    })

    $("#Logout").click(function(e){
        e.preventDefault()
        localStorage.removeItem("UserId");
        location.replace("../../Login/login.html");
    
    })


})(jQuery);