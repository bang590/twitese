<?php 
	include ('../lib/twitese.php');
	if (isset($_POST['longurl']) && isset($_POST['type'])) {
		$result = shortUrl($_POST['longurl'], $_POST['type']);
		if ($result) echo $result;
		else echo 'error';
	}
?>

