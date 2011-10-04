<?php 
	include ('../lib/twitese.php');
	$t = getTwitter();
	if ( isset($_GET['since_id']) && isset($_GET['id'])) {
		
		$statuses = $t->listStatus($_GET['id'], false, $_GET['since_id']);
		
		$empty = count($statuses) == 0? true: false;
		
		if ($empty) {
			echo "empty";
		} else {
			$count = 0;
			foreach ($statuses as $status) {
				$is_rt = isset($status->retweeted_status) && isset($_COOKIE['rt_style']) && $_COOKIE['rt_style'] == 1;
				
				$username = $t->username;
				if ($is_rt) {
					
					if (isset($argsArr['show_rtid'])) $status_id = $status->id_str;
					else $status_id = $status->retweeted_status->id;
					
					$rtsign = 'rt_sign';
					$user = $status->retweeted_status->user;
					$rtuser = $status->user;
					$date = formatDate($status->created_at);
					$rawdate = formatDate($status->created_at, true);
					$text = formatText($status->retweeted_status->text);
				} else {
					$user = $status->user;
					$date = formatDate($status->created_at);
					$rawdate = formatDate($status->created_at, true);
					$text = formatText($status->text);
					$status_id = $status->id_str;
					$rtsign = '';
				}
				
			
				if(strpos($text, "@$t->username") > -1) {
					if (++$count == count($statuses)) 
						$output = "<li style=\"border-bottom:1px solid #ccc\" class=\"mention\">";
					else 
						$output = "<li class=\"mention\">";
				} else {
					if (++$count == count($statuses)) 
						$output = "<li style=\"border-bottom:1px solid #ccc\">";
					else 
						$output = "<li>";
				}
				
				$output .= "
						<span class=\"status_author\">
							<a href=\"user.php?id=$user->screen_name\" target=\"_blank\"><img src=\"$user->profile_image_url\" title=\"$user->screen_name\" /></a>
						</span>
						<span class=\"status_body\">
							<span class=\"status_id\">$status_id</span>
							<span class=\"status_word\"><a class=\"user_name $rtsign\" href=\"user.php?id=$user->screen_name\">$user->screen_name</a> $text </span>
							"; 
				if ($shorturl = unshortUrl($status->text)) $output .= "<span class=\"unshorturl\">$shorturl</span>";
				
				$output .= "<span class=\"status_info\">";
				$output .= "<a class=\"replie_btn\" href=\"a_reply.php?id=$status->id_str\">回复</a>";
				$output .= "<a class=\"rt_btn\" href=\"a_rt.php?id=$status->id_str\">回推</a>";
				if ($user->screen_name != $username) $output .= "<a class=\"ort_btn\" href=\"a_ort.php?id=$status->id_str\">官方RT</a>";
				$output .= "<a class=\"favor_btn\" href=\"a_favor.php?id=$status->id_str\">收藏</a>";
				if ($user->screen_name == $username) $output .= "<a class=\"delete_btn\" href=\"a_del.php?id=$status->id_str&t=s\">删除</a>";
				
				if ($status->in_reply_to_status_id_str) $output .= "<span class=\"in_reply_to\"> <a href=\"status.php?id=$status->in_reply_to_status_id_str \">对 $status->in_reply_to_screen_name 的回复</a></span>";
				if ($is_rt) $output .= "RT by <a href=\"user.php?id=$rtuser->screen_name\">$rtuser->screen_name</a>";
				else if (isset($status->retweeted_status)) $output .= "RT from <a href=\"user.php?id=" . $status->retweeted_status->user->screen_name . "\">" . $status->retweeted_status->user->screen_name . "</a>";
				$output .= "		
				 				<span class=\"source\">通过 $status->source</span>
								<span class=\"date\" title=\"$rawdate\"><a href=\"https://twitter.com/$user->screen_name/status/$status->id_str\" target=\"_blank\">$date</a></span>
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

