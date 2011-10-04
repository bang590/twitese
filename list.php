<?php 
	include ('lib/twitese.php');
	$title = "推群";
	include ('inc/header.php');
	
	if (!isLogin()) header('location: login.php');
?>

<script type="text/javascript" src="js/list.js"></script>

<div id="statuses">
	<?php 
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
		
		$id = isset($_GET['id'])? $_GET['id'] : false;
		$arr = explode("/", $id);
		$userid = $arr[0];
		$t = getTwitter();
		$statuses = $t->listStatus($id, $p);
		$listInfo = $t->listInfo($id);
		testResult($statuses);
		
		$isFollower = $listInfo->following;
		
		$empty = count($statuses) == 0? true: false;
		if ($empty) {
			tpEmpty();
		} else {
	?>
	
		
	<div id="info_head">
		<a href="user.php?id=<?php echo $userid ?>"><img id="info_headimg" src="<?php echo $listInfo->user->profile_image_url?>" /></a>
		<div id="info_name_area"><span id="info_name"><?php echo $id ?></span></div>
		<div id="info_relation">
		<?php if ($isFollower) {?>
			<a id="list_block_btn" class="info_btn_hover" href="a_list.php?action=destory&id=<?php echo $id?>">取消关注</a>
		<?php } else { ?>
			<a id="list_follow_btn" class="info_btn" href="a_list.php?action=create&id=<?php echo $id?>">关注推群</a>
		<?php } ?>
			<a id="list_send_btn" class="info_btn" href="#">发 推</a>
			<a href="list_followers.php?id=<?php echo $id?>">关注者(<?php echo $listInfo->subscriber_count?>)</a>
			<a href="list_members.php?id=<?php echo $id?>">成员数(<?php echo $listInfo->member_count?>)</a>
		</div>
	</div>
	<div class="clear"></div>
	
	<?php 
			tpTimeline($statuses);
			
			$output = "<div id=\"pagination\">";			
			if ($p >1) $output .= "<a href=\"list.php?id=$id&p=" . ($p-1) . "\">上一页</a>";
			if (!$empty) $output .= "<a href=\"list.php?id=$id&p=" . ($p+1) . "\">下一页</a>";			
			$output .= "</div>";			
			echo $output;
		}
		
		
		
	?>
</div>

<?php 
	$show_stop_refresh = true;
	include ('inc/sidebar.php');
?>

<?php 
	include ('inc/footer.php');
?>
