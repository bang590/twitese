<?php 
	include ('../lib/twitese.php');
	
	if ( isset($_GET['id']) ) {
		$t = getTwitter();
		$statusid = $_GET['id'];
		$status = $t->showStatus($statusid);
		
		$user = $status->user;
		$text = formatText($status->text);
				
		if (!$status) {
			echo "error";
		} else {
			echo "
			<div class=\"inline_replie\">
				<span class=\"inline_replie_author\">
					<a href=\"user.php?id=$user->screen_name\" target=\"_blank\"><img src=\"$user->profile_image_url\" title=\"$user->screen_name\" /></a>
				</span>
				<span class=\"status_body inline_replie_body\">
					<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$user->screen_name\" target=\"_blank\">$user->screen_name</a> $text </span>
					
				</span>
				<div class=\"clear\"></div>
			</div>
			";
		}
	} else {
		echo "error";
	}
	
	
?>

