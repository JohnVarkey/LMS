(function($){

  var current = null;
  $('#email').on('focus', function(e) {
    if (current) current.pause();
    current = anime({
      targets: 'path',
      strokeDashoffset: {
        value: 0,
        duration: 700,
        easing: 'easeOutQuart'
      },
      strokeDasharray: {
        value: '240 1386',
        duration: 700,
        easing: 'easeOutQuart'
      }
    });
  });

  $('#password').on('focus', function(e) {
    if (current) current.pause();
    current = anime({
      targets: 'path',
      strokeDashoffset: {
        value: -336,
        duration: 700,
        easing: 'easeOutQuart'
      },
      strokeDasharray: {
        value: '240 1386',
        duration: 700,
        easing: 'easeOutQuart'
      }
    });
  });

  $('#submit').on('focus', function(e) {
    if (current) current.pause();
    current = anime({
      targets: 'path',
      strokeDashoffset: {
        value: -730,
        duration: 700,
        easing: 'easeOutQuart'
      },
      strokeDasharray: {
        value: '530 1386',
        duration: 700,
        easing: 'easeOutQuart'
      }
    });
  });

  function displayToast(message) {
    var x = $("#snackbar");
    x.html(message);
    x.attr("class","show");
    setTimeout(function(){
            x.attr("class","");
        }
    ,3000);
}

  /**
   * The Id of the logged User is stored in the LocalStorage of the Browser
   * @param {*} id: Id of the Logged User
   * @param {*} role: Role of the Looged User
   */
  const completeLogin =({ id , role }) =>{
    
    localStorage.setItem("UserId", id);
    if(role=="a"){
      location.replace("../dashboard/AdminDashboard/admindashboard.html");
    }else{
      location.replace("../dashboard/AdminDashboard/userdashboard.html");
    }
    
  }


  $('#Login').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    const  url = form.attr('action');
    $.ajax({
        type: "POST",
        url: url ,
        data: form.serialize(),
        success: function (response) {
    
            const data = JSON.parse(response);
            if(data.code!=200){
              displayToast(data.message);
            }else{
              completeLogin(data.message);
              displayToast("Login Successful");
            }
        }
    })
  })


})(jQuery)