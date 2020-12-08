(function($) {

  
    const checkpasswords = ()=>{
      let pass = $("#password").val();
      let repass = $("#re_password").val();
      var ans  = pass.localeCompare(repass);
      if(ans!=0)
        return false
      return true;
    }


    $(".toggle-password").click(function() {

        $(this).toggleClass("zmdi-eye zmdi-eye-off");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
          input.attr("type", "text");
        } else {
          input.attr("type", "password");
        }
    });

    $('#submit').submit((e)=>{
      alert("Submitted")
      let fields = ["email", "password","re_password","phone"];
      let flag=0;
        if(!checkpasswords()){
          alert("Passwords Do not Match");
          flag=1;
        }
        for(let i in fields){
          let val = $(`#${fields[i]}`).val()
          if(val.length=== 0){
            alert("Enter All Fields");
            flag=1;
            break;
          }
        }
        if(flag==1)
          e.preventDefault; 
    });

    $("#re_password").change(()=>{
      if(!checkpasswords()){
          $("#re_passwordError").html("Passwords Do not Match");
          $("#re_passwordError").css({
            "color": "Red"
          })
        }else{
          $("#re_passwordError").html("Passwords Match!");
          $("#re_passwordError").css({
            "color": "Green"
          })
        } 
    })

    $("#email").change(()=>{
          let email= $("#email").val();
          var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          if (!regex.test(email)){
            $("#emailError").html("Enter a Valid MailId");
            $("#emailError").css({
              "color": "Red"
            })
          }else{
            $("#emailError").html("");
          }
    })


    

    


})(jQuery);