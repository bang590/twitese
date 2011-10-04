<?php 
	include ('lib/twitese.php');
	$title = "回复";
	include ('inc/header.php');
	
	if (!isLogin()) header('location: login.php');
	
	$t = getTwitter();
	if ( isset($_GET['id']) ) {
		if (!testReferer()) exit();
		$statusid = $_GET['id'];
		$status = $t->showStatus($statusid);
		if (!$status) {
			header('location: error.php');
		}
		$_sentText = 'RT @' . $status->user->screen_name . ': ' . $status->text . ' ';
		$_sentInReplyTo = $statusid;
	} else {
		header('location: error.php');
	}
	
?>

<div id="statuses">
	<h2>回推</h2>
	<?php include('inc/sentForm.php')?>
</div>

<?php 
	include ('inc/sidebar.php');
?>

<?php 
	include ('inc/footer.php');
?>
