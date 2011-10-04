<?php 
	include ('lib/twitese.php');
	$title = "个人收藏页面";
	include ('inc/header.php');
	
?>

<script type="text/javascript" src="js/user.js"></script>

<div id="statuses">
	<?php 
		if (!isLogin() || !isset($_GET['id'])) {
			header('location: error.php');
		}
		
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
			
		$t = getTwitter();
		$userid = $_GET['id'];
		$statuses = $t->getFavorites($p, $userid);
		$user = $t->showUser($userid);
		testResult($statuses);
		
		$userinfo = array();
		$userinfo['name'] = $user->name;
		$userinfo['screen_name'] = $user->screen_name;
		$userinfo['friends_count'] = $user->friends_count;
		$userinfo['statuses_count'] = $user->statuses_count;
		$userinfo['followers_count'] = $user->followers_count;
		$userinfo['url'] = $user->url;
		$userinfo['description'] = $user->description;
		$userinfo['location'] = $user->location;
		$userinfo['protected'] = $user->url;
		$userinfo['id'] = $user->id;
		$userinfo['image_url'] = $user->profile_image_url;
			
			
	?>
	<div id="info_head">
		<a href="user.php?id=<?php echo $userid ?>"><img id="info_headimg" src="<?php echo $userinfo['image_url'] ?>" /></a>
		<div id="info_name_area"><span id="info_name"><?php echo $userid ?>的收藏</span></div>
	</div>
	<div class="clear"></div>
	<?php 
		
		$empty = count($statuses) == 0? true: false;
		if ($empty) {
			echo "<div id=\"empty\">此页无消息</div>";
		} else {
			$output = '<ol class="timeline" id="allTimeline">';
			
			foreach ($statuses as $status) {
				$user = $status->user;
				$date = formatDate($status->created_at);
				$text = formatText($status->text);
				
				$output .= "
				<li>
					<span class=\"info_status_body\">
						<span class=\"status_id\">$status->id</span>
						<span class=\"status_word\"> $text</span>
						"; 
				if ($shorturl = unshortUrl($status->text)) $output .= "<span class=\"unshorturl\">$shorturl</span>";
				$output .= 
						"<span class=\"status_info\">
				";
				if ($status->in_reply_to_status_id_str) $output .= "<span class=\"in_reply_to\"> <a href=\"status.php?id=$status->in_reply_to_status_id_str \">对 $status->in_reply_to_screen_name 的回复</a></span>";
				
				$output .= "	
					<span class=\"source\">通过 $status->source</span><span class=\"date\"><a href=\"status.php?id=$status->id\">$date</a></span><a class=\"replie_btn\" href=\"a_reply.php?id=$status->id\">回复</a><a class=\"rt_btn\" href=\"a_rt.php?id=$status->id\">回推</a><a class=\"ort_btn\" href=\"a_ort.php?id=$status->id\">官方RT</a><a class=\"favor_btn\" href=\"a_favor.php?id=$status->id\">收藏</a></span>
					</span>
				</li>
				";
			}
			
			$output .= "</ol><div id=\"pagination\">";
			
			if ($p >1) $output .= "<a href=\"userFavor.php?id=$userid&p=" . ($p-1) . "\">上一页</a>";
			if (!$empty) $output .= "<a href=\"userFavor.php?id=$userid&p=" . ($p+1) . "\">下一页</a>";
			
			$output .= "</div>";
			
			echo $output;
		}
	?>
</div>

<div id="sidebar">
	<ul id="user_info">
		<li><span>昵称：<?php echo $userinfo['name']?></span> </li>
		<?php  if ($userinfo['location']) echo '<li><span>所在地：</span>' . $userinfo['location'] . '</li>'; ?>
		<?php  if ($userinfo['url']) echo '<li><span>网站：</span><a href="' . $userinfo['url'] . '" target="_blank">' . $userinfo['url']  . '</a></li>'; ?>
		<?php  if ($userinfo['description']) echo "<li><span>简介：</span>" . $userinfo['description'] . "</li>"; ?>
	</ul>
	<ul id="user_stats">
		<li>
			<a href="friends.php?id=<?php echo $userid ?>">
				<span class="<?php echo strlen($userinfo['friends_count']) > 5?  "smallcount" :  "count" ?>"><?php echo $userinfo['friends_count'] ?></span>
				<span class="label">好友</span>
			</a>
		</li>
		<li>
			<a href="followers.php?id=<?php echo $userid ?>">
				<span class="<?php echo strlen($userinfo['followers_count']) > 5?  "smallcount" :  "count" ?>"><?php echo $userinfo['followers_count'] ?></span>
				<span class="label">关注者</span>
			</a>
		</li>
		<li>
			<a href="user.php?id=<?php echo $userid ?>">
				<span class="<?php echo strlen($userinfo['statuses_count']) > 5?  "smallcount" :  "count" ?>"><?php echo $userinfo['statuses_count'] ?></span>
				<span class="label">消息</span>
			</a>
		</li>
	</ul>
	<div class="clear"></div>
	
	<div id="sidenav">
		<a href="search.php?q=@<?php echo $userid ?>" target="_blank">@<?php echo $userid ?></a>
		<a href="userFavor.php?id=<?php echo $userid ?>">TA的收藏</a>
		<a href="friends.php?id=<?php echo $userid ?>">TA的好友</a>
		<a href="followers.php?id=<?php echo $userid ?>">TA的关注者</a>
		<a href="lists.php?id=<?php echo $userid ?>">TA的推群</a>
	</div>
	<div class="clear"></div>
	<?php include ('inc/sidepost.php') ?>
</div>
<?php 
	include ('inc/footer.php');
?>
