<?php 
	include ('lib/twitese.php');
	$title = "排行榜";
	include ('inc/header.php');
	
?>

<div id="statuses" style="width:760px">

	<h2>关注者排行榜</h2>
	<div class="clear"></div>
	
	<?php 
		$t = getTwitter();
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
		$num = 20*($p-1);
	
		$users = $t->rank($p);
		if ($users === false) {
			header('location: error.php');
		} 
		$empty = count($users) == 0? true: false;
		if ($empty) {
			echo "<div id=\"empty\">此页无消息</div>";
		} else {
			$output = '<ol class="rank_list" style="width:740px">';
			
			foreach ($users as $user) {
				$num++;	
				$output .= "
				<li>
					<span class=\"rank_img\"><a href=\"user.php?id=$user->screen_name\"><img src=\"$user->profile_img_url\" /></a></span>
					<div class=\"rank_content\">
						<span class=\"rank_num\">No. $num <span class=\"rank_name\"><a href=\"user.php?id=$user->screen_name\">$user->name</a></span><span class=\"rank_screenname\"> ($user->screen_name)</span></span>
						<span class=\"rank_count\">关注者：$user->followers_count 　好友：$user->friends_count 　消息数：$user->statuses_count</span>
						<span class=\"rank_description\">简介：$user->description</span>
					</div>
				</li>
				";
			}
			
			$output .= "</ol><div id=\"pagination\">";
			
			if ($p >1) $output .= "<a href=\"rank.php?p=" . ($p-1) . "\">上一页</a>";
			if (!$empty) $output .= "<a href=\"rank.php?p=" . ($p+1) . "\">下一页</a>";
			
			$output .= "</div>";
			
			echo $output;
		}
		
	?>
</div>
<?php 
	include ('inc/footer.php');
?>
