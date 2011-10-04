<?php 
	include ('lib/twitese.php');
	$title = "个人页面";
	include ('inc/header.php');
	
?>

<script type="text/javascript" src="js/user.js"></script>

<div id="statuses">
	<?php 
		if (!isset($_GET['id'])) {
			header('location: error.php');
		}
		
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
		
		
		if (isLogin() or VISITOR_ALLOW) {
			$t = getTwitter();
		} else {
			header('location: login.php');
		}
		
		
		$userid = $_GET['id'];
		$statuses = $t->userTimeline($p, $userid);
		
		testResult($statuses);
		
		$isProtected = ($statuses->error == 'Not authorized');
		if($_GET['debug']) var_dump($statuses);
		if (isLogin()) {
			$isFriend = $t->isFriend($t->username, $userid);
			$isFollower = $t->isFriend($userid, $t->username);
		}
		if (!$isProtected) {
			$userinfo = array();
			$userinfo['name'] = $statuses[0]->user->name;
			$userinfo['screen_name'] = $statuses[0]->user->screen_name;
			$userinfo['friends_count'] = $statuses[0]->user->friends_count;
			$userinfo['statuses_count'] = $statuses[0]->user->statuses_count;
			$userinfo['followers_count'] = $statuses[0]->user->followers_count;
			$userinfo['url'] = $statuses[0]->user->url;
			$userinfo['description'] = $statuses[0]->user->description;
			$userinfo['location'] = $statuses[0]->user->location;
			$userinfo['protected'] = $statuses[0]->user->url;
			$userinfo['id'] = $statuses[0]->user->id;
			$userinfo['image_url'] = $statuses[0]->user->profile_image_url;
			
			
	?>
	<div id="info_head">
		<a href="user.php?id=<?php echo $userid ?>"><img id="info_headimg" src="<?php echo $userinfo['image_url'] ?>" /></a>
		<div id="info_name_area"><span id="info_name"><?php echo $userid ?></span> 
		<?php if (isLogin() && $isFollower) {?>
		<span class="info_is_following">正在关注我</span>
		<?php } ?>
		</div>
		<?php if($userid != getCookie('twitese_name')) { ?>
		<div id="info_relation">
		
		<?php if (isLogin()) {?>
			<?php if ($isFriend) {?>
				<a class="info_btn_hover info_unfollow_btn"  href="a_relation.php?action=destory&id=<?php echo $userid ?>">取消关注</a>
			<?php } else { ?>
				<a class="info_btn info_follow_btn" href="a_relation.php?action=create&id=<?php echo $userid ?>">关注此人</a>
			<?php } ?>
			<?php if ($isFollower) {?>
				<a class="info_btn" id="info_send_btn" href="message.php?id=<?php echo $userid ?>">发送私信</a>
			<?php } ?>
				<a class="info_btn" id="info_reply_btn" href="javascript:void(0)">给TA留言</a>
		<?php } ?>
		
			<a class="info_btn" id="info_hide_btn" href="javascript:void(0)">隐藏@</a>
			
		<?php if (isLogin()) {?>
			<a class="info_btn info_block_check_btn" href="block.php?id=<?php echo $userid ?>&action=check">检查黑名单</a>
		<?php } ?>
			
		</div>
		<?php } ?>
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
			
			if ($p >1) $output .= "<a href=\"user.php?id=$userid&p=" . ($p-1) . "\">上一页</a>";
			if (!$empty) $output .= "<a href=\"user.php?id=$userid&p=" . ($p+1) . "\">下一页</a>";
			
			$output .= "</div>";
			
			echo $output;
		}
	}//end of if(!$isProtected)
	else {
		?>
		<div id="info_head">
			<div id="info_name"><?php echo $userid ?></div>
			<div id="info_relation">
			<?php if(isLogin()) { ?>
				<?php if ($isFriend) {?>
					<a id="info_block_btn" class="info_btn_hover" href="a_relation.php?action=destory&id=<?php echo $userid ?>">取消关注</a>
				<?php } else { ?>
					<a id="info_follow_btn" class="info_btn" href="a_relation.php?action=create&id=<?php echo $userid ?>">关注此人</a>
				<?php } ?>
				<?php if ($isFollower) {?>
					<a class="info_btn" id="info_send_btn" href="message.php?id=<?php echo $userid ?>">发送私信</a>
				<?php } ?>
					<a class="info_btn" id="info_reply_btn" href="javascript:void(0)">给TA留言</a>
			<?php } ?>
				<a class="info_btn" id="info_hide_btn" href="javascript:void(0)">隐藏@</a>
			</div>
		</div>
		<div class="clear"></div>
		<div id="empty">此用户设置了隐私保护，需要他加你为好友才可查看。</div>
	<?php 
	}
	?>
</div>

<?php if (!$isProtected) {?>
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
<?php } else { 
		include ('inc/sidebar.php');
	  }
?>

<?php 
	include ('inc/footer.php');
?>
