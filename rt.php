<?php 
	include ('lib/twitese.php');
	$title = "RT";
	include ('inc/header.php');
	
	if (!isLogin()) header('location: login.php');
?>

<script type="text/javascript" src="js/rt.js"></script>

<div id="statuses">
	<?php 
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
		
		$type = isset($_GET['t'])? $_GET['t'] : 2;
		$t = getTwitter();
		switch ($type) {
			case 1:
				$statuses = $t->rtTome($p);
				break;
			case 2:
				$statuses = $t->rtByme($p);
				break;
			case 3:
				$statuses = $t->rtOfme($p);
				break;
			default:
				$statuses = $t->rtTome($p);
				break;
		}
		testResult($statuses);
		
		$isFollower = false;
		//$isFollower = $t->isFollowedList($id);
		
		$empty = count($statuses) == 0? true: false;
		if ($empty) {
			tpEmpty();
		} else {
	?>
	
	<div id="subnav">
	<?php if ($type == 1) {?>
       	<span class="subnavNormal">好友RT</span><span class="subnavLink"><a href="rt.php?t=2">我的RT</a></span><span class="subnavLink"><a href="rt.php?t=3">RT我的</a></span>
	<?php } else if ($type == 2) {?>
       	<span class="subnavLink"><a href="rt.php?t=1">好友RT</a></span><span class="subnavNormal">我的RT</span><span class="subnavLink"><a href="rt.php?t=3">RT我的</a></span>
	<?php } else {?>
		<span class="subnavLink"><a href="rt.php?t=1">好友RT</a></span><span class="subnavLink"><a href="rt.php?t=2">我的RT</a></span><span class="subnavNormal">RT我的</span>
	<?php } ?>
    </div>
    
	<div id="info_head">
	</div>
	<div class="clear"></div>
	
	<?php 
			$argArr = false;
			if ($type == 2) $argArr = array('show_del'=>true, 'show_rtid' => true, 'hide_ort' => true, 'hide_rt' => true, 'hide_replie' => true);
			if ($type == 3) $argArr = array('hide_ort' => true, 'hide_rt' => true, 'hide_replie' => true);
			tpTimeline($statuses, $argArr);
			
			$output = "<div id=\"pagination\">";	
			if ($p >1) $output .= "<a href=\"rt.php?t=$type&p=" . ($p-1) . "\">上一页</a>";
			if (!$empty) $output .= "<a href=\"rt.php?t=$type&p=" . ($p+1) . "\">下一页</a>";			
			$output .= "</div>";			
			echo $output;
		}
		
		
		
	?>
</div>

<?php 
	include ('inc/sidebar.php');
?>

<?php 
	include ('inc/footer.php');
?>
