<?php 
	include ('../lib/twitese.php');
	$t = getTwitter();
	if ( isset($_GET['since_id']) ) {
				
		$messages = $t->directMessages(false, $_GET['since_id']);
		
		$empty = count($messages) == 0? true: false;
		
		if ($empty) {
			echo "empty";
		} else {		
			$count = 0;	
			foreach ($messages as $message) {
				$name = $message->sender_screen_name;
				$imgurl = $message->sender->profile_image_url;
				$date = formatDate($message->created_at);
				$text = formatText($message->text);
				
				if (++$count == count($statuses)) 
					$output = "<li style=\"border-bottom:1px solid #ccc\">";
				else
					$output = "<li>";
					
				$output .= "
						<span class=\"status_author\">
							<a href=\"user.php?id=$name\" target=\"_blank\"><img src=\"$imgurl\" title=\"$name\" /></a>
						</span>
						<span class=\"status_body\">
							<span class=\"status_id\">$message->id_str </span>
							<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$name\">$name</a> $text </span>
							<span class=\"status_info\">
								<a class=\"msg_replie_btn\" href=\"message.php?id=$name\">回复</a>
								<span class=\"date\">$date</span>
						    </span>
						</span>
					</li>";
				echo $output;
			}
		}
		
	} else {
		echo 'error';
	}

?>

