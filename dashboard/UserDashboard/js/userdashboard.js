jQuery(document).ready(function($) {

    const filepath = "http://localhost/LMS/Assets/book_images/";

    const checkLogin = () =>{
      const user = localStorage.getItem("UserId");
      if(user==null)
          location.replace("http://localhost/LMS/Login/login.html");
          //console.log("herer");
      alert(`Your User Id is : ${user}`);
    }
    
  checkLogin();

  function getData(){
    let payload = {
      op: 1,
    }

    $.get(
      "../php/GetBooks.php",
      payload,
      function (response) {
        const data = JSON.parse(response);
        console.log(data);
        setmostvisited(data);
      }
    );
    
  }

    getData()


  function setmostvisited(data){
    const parentDiv = $("#BooksSection");
    parentDiv.html("");
    const childarr = data.map(row=>{        
        let imgurl = filepath+row.IMAGE_URL; 
        let childDom = ` 
          <div class='books' id='1' name="${row.BOOK_ID}" >
            <div class="BookImage" >
            <img src="${imgurl}" alt="IMG" name="${row.BOOK_ID}">
            </div>
            <div class="text-content">
              <h4 >${row.TITLE}</h4>
              <span>${row.AUTHOR}</span>
            </div>
          </div>`;
        return childDom;
    })
    childarr.forEach(element => {
      parentDiv.append(element);
    });
    }   
    
    $("#BooksSection").on("click","div.books",(e)=>{
      const id= e.target.getAttribute('name');
      window.location = 'BooksDetails.html?Id=' + id;
      console.log(id);
    })



    const searchfilter = () =>{
      const payload = {
        op: 2,
        filter:{
          title : $("#title").val(),
          author : $("#author").val(),
          publisher : $("#publisher").val()
        }
      }
      $.get(
        "../php/GetBooks.php",
        payload,
        function (response) {
          const data = JSON.parse(response);
          setmostvisited(data);
        }
      );

    }

  $("#title").on("keyup",()=>searchfilter());
  $("#author").on("keyup",()=>searchfilter());
  $("#publisher").on("keyup",()=>searchfilter());


  $("#Logout").click(function(e){
    e.preventDefault()
    localStorage.removeItem("UserId");
    location.replace("http://localhost/LMS/Login/");

})


 

});
