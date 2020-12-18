(function($) {

    const checkLogin = () =>{
        const user = localStorage.getItem("UserId");
        
        if(user==null)
            location.replace("http://localhost/LMS/Login/");
            //console.log("herer");
    }
    checkLogin();

    const tabledata= (title="" , author="" , publisher="") => {
        $.get(
            "../php/GetBooks.php",
            {
                op: 2,
                filter:{
                    title,
                    author,
                    publisher
                }
            },
                function (response) {
                    const data = JSON.parse(response);
                    console.log(data);
                    populateTable(data);
                    
            }
        )
    }
    tabledata();


    $("#btn-Logout").click(function(e){
        e.preventDefault()
        localStorage.removeItem("UserId");
        location.replace("http://localhost/LMS/Login/");
    
    })

    
    
    const populateTable = (data)=>{
        const parent = $("tbody");
        parent.html("");
        data.forEach((row)=>{
            var childEle = `
                <tr 
                    class="accordion-toggle" 
                    id="tr-${row.BOOK_ID}" 
                    data-parent="#tr-${row.BOOK_ID}" 
                    href="#tr-${row.BOOK_ID}"
                >
                <td>${row.BOOK_ID}</td>
                <td 
                    class = "BookImage" 
                    style = " 
                                background-image: url('http://localhost/LMS/Assets/book_images/${row.IMAGE_URL}')
                            "
                ></td>
                <td>${row.TITLE}</td>
                <td>${row.AUTHOR}</td>
                <td>${row.PUBLISHER}</td>
                <td>
                    <button type="button" name="bt-${row.BOOK_ID}" class="btn btn-primary" >
                        +
                    </button>
                </td> 
            </tr>
            `;
            parent.append(childEle);
        })
    }
   
    $("tbody").on("click",".accordion-toggle td button",(e)=>{
        const Id = e.target.name.split("-")[1];
        const populateModal = (data) =>{
            
            const ids=[
                "AUTHOR",
                "BOOK_ID",
                "COPIES",
                "AVAILABLE",
                "DESCRIPTION",
                "EDITION",
                "PRICE",
                "PUBLISHER",
                "RELEASE_DATE",
                "TITLE"
            ];
            ids.forEach((id)=>{
                $(`#${id}`).val(data[id]);
            })
            $('#DataModal').modal('show');

        }
        
        
        $.get(
            "../php/GetBooks.php",
            {
                op: 3,
                bookId: Id
            },
                function (response) {
                    const data = JSON.parse(response);
                    if(data.code==200){
                        populateModal(data.message);  
                    }else{
                        console.log("error:", data.message)
                    }
                }
        )
    })


    $("#deleteBook").on("click",()=>{
        const id = $("#BOOK_ID").val();
        if(confirm("Do you want to Delete?")){
            $.post(
                "../php/UploadBook.php",
                {
                    op: 4,
                    bookId: id
                },
                (response)=>{
                    const {code, message} = JSON.parse(response);
                    if(code==201){
                        alert(message);
                        location.reload();
                    }else{
                        alert(message)
                    }
                }
            )
            
        }
    })

    const displayToast = message =>{
        alert(message);
    }


    $("#ModalForm").submit((e)=>{
        e.preventDefault();
        
        const form = $(e.target).serializeArray();
        form.push({name: "op", value: 5})
        const  url = "../php/UploadBook.php"
        $.get(
            url ,
            form,
                function (response) {
                    console.log("res",response);
                    const data = JSON.parse(response);
                    console.log(data);
                    if(data.code==403)
                        displayToast(data.message);
                    else if(data.code==200)
                        displayToast("Book Edited");
                    else{
                        displayToast(data.message);
                    }
            }
        )
    })

    $("#searchBox").on("keyup",(e)=>{
        const { value } = e.target;
        tabledata(value);
    })


})(jQuery);  