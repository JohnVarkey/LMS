(function ($) {
	const checkLogin = () => {
		const user = localStorage.getItem('UserId');

		if (user == null) location.replace('http://localhost/LMS/Login/');
		//console.log("herer");
	};
	checkLogin();

	$('#Form').on('submit', (e) => {
		e.preventDefault();
		const { id, action } = e.target;
		const form = $(`#${id}`).serializeArray();
		form.push({ name: 'op', value: 3 });
		console.log(form);
		$.post(action, form, (response) => {
			const { code, message } = JSON.parse(response);
			if (code == 201) {
				console.log(message);
			}
			alert(message);
		});
	});

	const getNextAvailable = (bookId) => {
		$.get(
			'../php/AdminUser.php',
			{
				op: 5,
				bookId,
			},
			function (response) {
				const data = JSON.parse(response);
				$('#bookStatus').text(`Next Available: ${data.RETURN_DATE}`);
				$('#bookStatus').css('color', 'red');
				$('#Assign').attr('disabled', true);
			}
		);
	};

	$('#bookId').on('change', (e) => {
		const Id = e.target.value;
		$('#Assign').attr('disabled', false);
		$.get(
			'../php/GetBooks.php',
			{
				op: 3,
				bookId: Id,
			},
			function (response) {
				const data = JSON.parse(response);

				if (data.message == null || data.code != 200) {
					$('#bookStatus').text('Book Not Found');
					$('#bookStatus').css('color', 'red');
					$('#Assign').attr('disabled', true);
				} else {
					const { AVAILABLE: Available } = data.message;
					$('#bookStatus').text(`Available Copies: ${Available}`);
					if (Available > 0) $('#bookStatus').css('color', 'Green');
					else getNextAvailable(Id);
				}
			}
		);
	});

	$('#btn-Logout').click(function (e) {
		e.preventDefault();
		localStorage.removeItem('UserId');
		location.replace('http://localhost/LMS/Login/');
	});
})(jQuery);
