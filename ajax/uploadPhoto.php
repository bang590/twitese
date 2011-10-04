<?php 
	include ('../lib/twitese.php');
	$t = getTwitter();
	if (isset($_FILES["image"])) {
		$image = $_FILES["image"]['tmp_name'];
		$result = imglyUpload($image);
		
		if ($result) {
			echo '{"result": "success" , "url" : "' . $result . '"}';
		} else {
			echo '{"result": "error"}';
		}
	}

?>