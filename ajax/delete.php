<?php 
	include ('../lib/twitese.php');
	$t = getTwitter();
	if (!testReferer()) exit();
	if ( isset($_POST['status_id']) ) {
		$result = $t->deleteStatus($_POST['status_id']);
		if ($result) echo 'success';
		else echo 'error';
	}
	if ( isset($_POST['message_id']) ) {
		$result = $t->deleteDirectMessage($_POST['message_id']);
		if ($result) echo 'success';
		else echo 'error';
	}
	if ( isset($_POST['favor_id']) ) {
		$result = $t->removeFavorite($_POST['favor_id']);
		if ($result) echo 'success';
		else echo 'error';
	}
	if ( isset($_POST['list_id']) ) {
		$result = $t->deleteList($_POST['list_id']);
		if ($result) echo 'success';
		else echo 'error';
	}
	if ( isset($_POST['list_member']) ) {
		$user = $t->showUser($_POST['list_member']);
		$memberid = (string)$user->id;
		$result = $t->deleteListMember($_POST['id'], $memberid);
		if ($result) echo 'success';
		else echo 'error';
	}
?>

