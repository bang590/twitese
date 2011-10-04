<?php 
	include ('../lib/twitese.php');
	$t = getTwitter();
	if ( isset($_POST['action']) && isset($_POST['id']) ) {
		if (!testReferer()) exit();
		if ($_POST['action'] == 'create') {
			$result = $t->followList($_POST['id']);
			if ($result) echo 'success';
			else echo 'error';
		} else if ($_POST['action'] == 'destory') {
			$result = $t->unfollowList($_POST['id']);
			if ($result) echo 'success';
			else echo 'error';
		} 
	}
	
?>

