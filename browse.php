<?php 
	include ('lib/twitese.php');
	$title = "随便看看";
	include ('inc/header.php');
	
?>

<script type="text/javascript" src="js/browse.js"></script>

<div id="statuses" style="width:760px">

	<h2 id="browse_title">随便看看其他人在做什么...</h2>
	<div class="clear"></div>
	
	<?php 
		$t = getTwitter();
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
	
		$statuses = $t->browse($p);
		if ($statuses === false) {
			header('location: error.php');
		} 
		$empty = count($statuses) == 0? true: false;
		if ($empty) {
			echo "<div id=\"empty\">此页无消息</div>";
		} else {
			$output = '<ol class="timeline" id="allTimeline" style="width:740px">';
			
			foreach ($statuses as $status) {
				$date = formatDate($status->created_at);
				$text = formatText($status->text);
				
				$output .= "
					<li>
						<span class=\"status_author\">
							<a href=\"user.php?id=$status->screen_name\" target=\"_blank\"><img src=\"$status->profile_img_url\" title=\"$status->screen_name\" /></a>
						</span>
						<span class=\"status_body\">
							<span class=\"status_id\">$status->id</span>
							<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$status->screen_name\">$status->screen_name</a> $text </span>
							<span class=\"status_info\">
								<a class=\"replie_btn\" href=\"a_reply.php?id=$status->id\">回复</a><a class=\"rt_btn\" href=\"a_rt.php?id=$status->id\">回推</a><a class=\"ort_btn\" href=\"a_ort.php?id=$status->id\">官方RT</a><a class=\"favor_btn\" href=\"a_favor.php?id=$status->id\">收藏</a>
								<span class=\"source\">通过 $status->source</span>
								<span class=\"date\"><a href=\"https://twitter.com/$status->screen_name/status/$status->id\" target=\"_blank\">$date</a></span>
						    </span>
						</span>
					</li>
				";
			}
			
			$output .= "</ol><div id=\"pagination\">";
			
			if ($p >1) $output .= "<a href=\"browse.php?p=" . ($p-1) . "\">上一页</a>";
			if (!$empty) $output .= "<a href=\"browse.php?p=" . ($p+1) . "\">下一页</a>";
			
			$output .= "</div>";
			
			echo $output;
		}
		
		
		
	?>
</div>


<?php 
	include ('inc/footer.php');
?>
