<?php 
	include ('lib/twitese.php');
	$title = "我的消息";
	include ('inc/header.php');
	
	if (!isLogin()) header('location: login.php');
?>

<script type="text/javascript" src="js/profile.js"></script>

<div id="statuses">

	<?php include('inc/sentForm.php')?>
	
	<?php 
		$t = getTwitter();
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
	
		$statuses = $t->userTimeline($p);
		testResult($statuses);
		$empty = count($statuses) == 0? true: false;
		if ($empty) {
			tpEmpty();
		} else {
			tpTimeline($statuses, array("show_del"=>true, "hide_rt"=>true, "hide_replie"=>true, "hide_ort"=>true));
			
			$output = "<div id=\"pagination\">";
			
			if ($p >1) $output .= "<a href=\"profile.php?p=" . ($p-1) . "\">上一页</a>";
			if (!$empty) $output .= "<a href=\"profile.php?p=" . ($p+1) . "\">下一页</a>";
			
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
