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
		$_sentText = '@' . $status->user->screen_name . ' ';
		$_sentInReplyTo = $statusid;
		$user = $status->user;
		$date = formatDate($status->created_at);
		$text = formatText($status->text);
	} else {
		header('location: error.php');
	}
	
?>

<div id="statuses">
	<h2>回复<?php echo $user->screen_name ?></h2>
	<?php include('inc/sentForm.php')?>
	<div class="clear"></div>
	<ol class="timeline">
		<li>
			<span class="status_author">
				<a href="user.php?id=<?php echo $user->screen_name ?>" target="_blank"><img src="<?php echo $user->profile_image_url ?>" /></a>
			</span>
			<span class="status_body">
				<span class="status_id"><?php echo $statusid ?></span>
				<span class="status_word"><a class="user_name" href="user.php?id=<?php echo $user->screen_name ?>"><?php echo $user->screen_name ?></a> <?php echo $text ?></span>
				<span class="status_info">
					<?php if ($status->in_reply_to_status_id) {?><span class="in_reply_to"> <a href="status.php?id=<?php echo $status->in_reply_to_status_id_str ?>">对 <?php echo $status->in_reply_to_screen_name?> 的回复</a></span> <?php }?>
					<span class="source">通过<?php echo $status->source ?></span>
					<span class="date"><a href="https://twitter.com/<?php echo $user->screen_name ?>/status/<?php echo $statusid ?>" target="_blank"><?php echo $date ?></a></span>
			    </span>
			</span>
		</li>
	</ol>
</div>

<?php 
	include ('inc/sidebar.php');
?>

<?php 
	include ('inc/footer.php');
?>
