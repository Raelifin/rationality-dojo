function signinCallback(authResult) {
	if (authResult['code']) {
		$('#loginSection').html('<p>Waiting on server...</p>');
		
		// Send the code to the server
		$.ajax({
			type: 'POST',
			url: 'loginBackend.php?storeToken',
			contentType: 'application/octet-stream; charset=utf-8',
			success: function(result) {
				resultObj = JSON.parse(result);
				$('#loginSection').html(resultObj.response);
				if ('pass' in resultObj) {
					document.cookie="pass="+resultObj.pass;
					window.location.href = "http://rationality-dojo.com";
				}
			},
			processData: false,
			data: authResult['code']
		});
	} else if (authResult['error']) {
		// There was an error.
		// Possible error codes:
		//   "access_denied" - User denied access to your app
		//   "immediate_failed" - Could not automatially log in the user
		if (authResult['error'] != 'access_denied') {
			alert('There was an error: ' + authResult['error']);
		}
	}
}

$( document ).ready(function() {
	$('.expandButton').click(function(e) {
		$(e.target).parent().toggleClass('expanded');
		if ($(e.target).parent().hasClass('expanded')) {
			$(e.target).html('&#9660;');
		} else {
			$(e.target).html('&#9654;');
		}
	});
});