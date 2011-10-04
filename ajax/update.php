<?php 
	include ('../lib/twitese.php');
	$t = getTwitter();
	if ( isset($_POST['status']) && isset($_POST['in_reply_to']) ) {
		if (!testReferer()) exit();
		if (trim($_POST['status']) == '') {
			echo 'empty';
			exit();
		}
		$status = $t->update($_POST['status'], $_POST['in_reply_to']);
		if (isset($status->user)) {
			$user = $status->user;
			$time = time()+3600*24*365;
			if ($user) {
				setcookie('friends_count', $user->friends_count, $time, '/');
				setcookie('statuses_count', $user->statuses_count, $time, '/');
				setcookie('followers_count', $user->followers_count, $time, '/');
				setcookie('imgurl', $user->profile_image_url, $time, '/');
				setcookie('name', $user->name, $time, '/');
			}
					
			$user = $status->user;
			$date = formatDate($status->created_at);
			$rawdate = formatDate($status->created_at, true);
			$text = formatText($status->text);
			$status_id = $status->id_str;
		
			
			$output = "<li>";
			
			$output .= "
					<span class=\"status_author\">
						<a href=\"user.php?id=$user->screen_name\" target=\"_blank\"><img src=\"$user->profile_image_url\" title=\"$user->screen_name\" /></a>
					</span>
					<span class=\"status_body\">
						<span class=\"status_id\">$status_id</span>
						<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$user->screen_name\">$user->screen_name</a> $text </span>
						"; 
			if ($shorturl = unshortUrl($status->text)) $output .= "<span class=\"unshorturl\">$shorturl</span>";
			
			$output .= "<span class=\"status_info\">";
			$output .= "<a style=\"display:none\" class=\"replie_btn\"href=\"a_reply.php?id=$status->id_str\">回复</a>";
			$output .= "<a style=\"display:none\" class=\"rt_btn\" href=\"a_rt.php?id=$status->id_str\">回推</a>";
			$output .= "<a style=\"display:none\" class=\"favor_btn\" href=\"a_favor.php?id=$status->id_str\">收藏</a>";
			$output .= "<a style=\"display:none\" class=\"delete_btn\" href=\"a_del.php?id=$status->id_str&t=s\">删除</a>";
			
			if ($status->in_reply_to_status_id_str) $output .= "<span class=\"in_reply_to\"> <a href=\"status.php?id=$status->in_reply_to_status_id_str \">对 $status->in_reply_to_screen_name 的回复</a></span>";
			$output .= "		
			 				<span class=\"source\">通过 $status->source</span>
							<span class=\"date\" title=\"$rawdate\"><a href=\"https://twitter.com/$user->screen_name/status/$status->id_str\" target=\"_blank\">$date</a></span>
					    </span>
					</span>
				</li>
			";
			echo $output;
			
//			echo $result->id;
		} else {
			echo 'error';
		}
		
	}

?>