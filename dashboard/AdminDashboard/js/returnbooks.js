(function($){
    const checkLogin = () =>{
        const user = localStorage.getItem("UserId");
        console.log("here")
        if(user==null)
            location.replace("http://localhost/LMS/Login/");
            //console.log("herer");
    }
    checkLogin();


    const populateTable = data =>{
        console.log(data);
        const parentEle = $("tbody");
        parentEle.html("");
        data.forEach(row=>{
            var childEle = ` 
            <tr>
                <form action="#" class="assign" method="post" id="form-${row.BORROW_ID}">
                    <td>${row.USER_ID}</td>
                    <td>${row.NAME}</td>
                    <td>${row.EMAIL}</td>
                    <td>${row.TITLE}</td>
                    <td>${row.RETURN_DATE}</td>
                    <td>
                        <div class="dropdown">
                            <input type="submit" value="Returned" class="btn btn-primary" form="form-${row.BORROW_ID}"/>
                        </div>
                    </td>
                </form>
            </tr>`;
            parentEle.append(childEle);
        })
    }

    const getData = (userName)=>{
        $.get(
            "../php/AdminUser.php" ,
            {
                op: 1,
                userName
            },
            function (response) {
                const data = JSON.parse(response);
                populateTable(data);
          
            }
        )
    }

    getData("");


    $("tbody").on("submit","form",(e)=>{
        e.preventDefault();     
        const id = e.target.id;
       
        $.post(
            "../php/AdminUser.php",{
                op: 2,
                id: id.split("-")[1]
            },
            function (response) {
                const { code , message } = JSON.parse(response);
                if(code == 201){
                    console.log(message);
                    location.reload();
                }

            }
        )
        
    })


    $("#SearchBox").on("keyup",(e)=>{
        const { value } = e.target;
        getData(value);
    })


    $("#Logout").click(function(e){
        e.preventDefault()
        localStorage.removeItem("UserId");
        location.replace("../../Login/");
    
    })
})(jQuery)