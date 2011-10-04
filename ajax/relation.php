<?php 
	include ('../lib/twitese.php');
	$t = getTwitter();
	if (!testReferer()) exit();
	
	if ( isset($_POST['action']) && isset($_POST['id']) ) {
		if ($_POST['action'] == 'create') {
			$result = $t->followUser($_POST['id']);
			if ($result) echo 'success';
			else echo 'error';
		} else if ($_POST['action'] == 'destory') {
			$result = $t->destroyUser($_POST['id']);
			if ($result) echo 'success';
			else echo 'error';
		}
	}
	
?>

