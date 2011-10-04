<div id="sidebar">
	<div id="sideinfo">
		<a href="profile.php"><img id="sideimg" src="<?php echo getCookie("imgurl")?>" /></a>
		<span id="sideid"><?php echo getEncryptCookie('twitese_name')?> <a href="javascript:void(0);" id="profileRefresh"><img src="img/refresh.png" /></a></span>
		<span id="sidename"><?php echo getCookie('name')?></span>
	</div>
	<?php if (strrpos($_SERVER['PHP_SELF'], 'profile')) {
			$user = $t->showUser();
	?>
	<ul id="user_info_profile">
		<li><span>昵称：</span> <?php echo $user->name ?></li>
		<?php  if ($user->location) echo "<li><span>所在地：</span>$user->location</li>"; ?>
		<?php  if ($user->url) echo "<li><span>网站：</span><a href=\"$user->url\" target=\"_blank\">$user->url</a></li>"; ?>
		<?php  if ($user->description) echo "<li><span>简介：</span>$user->description</li>"; ?>
		</ul>
	<?php }?>
	<ul id="user_stats">
		<li>
			<a href="friends.php">
				<span class="<?php echo strlen(getCookie('friends_count')) > 5?  "smallcount" :  "count" ?>"><?php echo getCookie('friends_count') ?></span>
				<span class="label">好友</span>
			</a>
		</li>
		<li>
			<a href="followers.php">
				<span class="<?php echo strlen(getCookie('followers_count')) > 5?  "smallcount" :  "count" ?>"><?php echo getCookie('followers_count') ?></span>
				<span class="label">关注者</span>
			</a>
		</li>
		<li>	
			<a href="profile.php">
				<span class="<?php echo strlen(getCookie('statuses_count')) > 5?  "smallcount" : "count" ?>"><?php echo getCookie('statuses_count') ?></span>
				<span class="label">消息</span>
			</a>
		</li>
	</ul>
	<div class="clear"></div>
	<div id="sidenav">
		<a href="all.php">全部消息</a>
		<a href="index.php">好友消息</a>
		<a href="profile.php">我的消息</a>
		<a href="replies.php">@<?php echo getEncryptCookie('twitese_name')?></a>
		<a href="rt.php">RT消息</a>
		<a href="message.php">私信</a>
		<a href="favor.php">我的收藏</a>
		<a href="lists.php">我的推群</a>
	</div>
	<div class="clear"></div>
	<?php include ('sidepost.php') ?>
</div>