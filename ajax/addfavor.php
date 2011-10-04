<?php 
	include ('../lib/twitese.php');
	$t = getTwitter();
	if (!testReferer()) exit();
	$result = $t->makeFavorite($_POST['status_id']);
	if (isset($result->created_at)) echo 'success';
	else if ($result == 'favorited') echo 'favorited';
	else echo 'error';
?>