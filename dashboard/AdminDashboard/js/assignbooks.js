(function($) {
    const checkLogin = () =>{
        const user = localStorage.getItem("UserId");
        
        if(user==null)
            location.replace("http://localhost/LMS/Login/");
            //console.log("herer");
    }
    checkLogin();

    $("#Form").on("submit",(e)=>{
        e.preventDefault();
        const { id , action } = e.target;
        const form = $(`#${id}`).serializeArray(); 
        form.push({name: "op" , value: 3})
        console.log(form);
        $.post(
            action,
            form,
            response =>{
                const { code , message }=JSON.parse(response);
                if(code==201){
                    console.log(message);
                }
                alert(message); 
            }
        );
    })
    
    $("#btn-Logout").click(function(e){
        e.preventDefault()
        localStorage.removeItem("UserId");
        location.replace("http://localhost/LMS/Login/");
    })
})(jQuery)