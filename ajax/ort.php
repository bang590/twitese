<?php 
	include ('../lib/twitese.php');
	$t = getTwitter();
	if ( isset($_POST['status_id']) ) {
		if (!testReferer()) exit();
		$result = $t->addRT($_POST['status_id']);
		if (!$result) echo 'error';
		else if (isset($result->errors)) echo 'repeat';
		else echo 'success';
	}
?>

