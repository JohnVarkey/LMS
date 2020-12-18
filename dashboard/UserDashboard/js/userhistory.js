(function ($) {
	const filepath = 'http://localhost/LMS/Assets/book_images/';

	const checkLogin = () => {
		const user = localStorage.getItem('UserId');
		if (user == null) location.replace('http://localhost/LMS/Login/');
		//console.log("herer");
	};

	checkLogin();

	const getIdFromAddress= ()=> {
		var query = window.location.search.substring(1);
		var parms = query.split('&');
		var pos = parms[0].indexOf('=');
		if (pos > 0) {
			var key = parms[0].substring(0, pos);
			var val = parms[0].substring(pos + 1);
		}
		if (key == 'Id') return val;
    }
    
	const populateTable = (data) => {
		console.log(data);
		const parentEle = $('tbody');
		parentEle.html('');
		data.forEach((row) => {
			var childEle = ` 
            <tr>
                <form action="#" class="assign" method="post" id="form-${row.BORROW_ID}">
                    <td>${row.TITLE}</td>
                    <td>${row.ISSUE_DATE}</td>
                    <td>${row.RETURN_DATE}</td>
                    <td>${row.STATUS}</td>
                </form>
            </tr>`;
			parentEle.append(childEle);
		});
	};

	const getData = () => {
        const Id= getIdFromAddress()
		$.get(
			'../php/AdminUser.php',
			{
				op: 4,
			    userId: Id,
			},
			function (response) {
                const data = JSON.parse(response);
                console.log(data);
				populateTable(data);
			}
		);
	};

	getData();

})(jQuery);
