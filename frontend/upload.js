webcam.set_hook( 'onComplete', 'my_completion_handler' );

function take_snapshot() {
	// take snapshot and upload to server
	document.getElementById('upload_results').innerHTML = '<h1>Uploading...</h1>';
	webcam.snap();
}

function my_completion_handler(msg) {
	// extract URL out of PHP output
	if (msg.match(/(http\:\/\/\S+)/)) {
		var image_url = RegExp.$1;
		// show JPEG image in page
		document.getElementById('upload_results').innerHTML = 
			'<h1>Upload Successful!</h1>' + 
			'<h3>JPEG URL: ' + image_url + '</h3>' + 
			'<img src="' + image_url + '">';

		// reset camera for another shot
		webcam.reset();
	}
	else {
		document.getElementById('upload_results').innerHTML =
			'<h1>Upload Failed!</h1>' +
			'<h2>' + msg + '</h2>';
	}
}
