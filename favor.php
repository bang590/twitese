<?php 
	include ('lib/twitese.php');
	$title = "我的收藏";
	include ('inc/header.php');
	
	if (!isLogin()) header('location: login.php');
?>

<script type="text/javascript" src="js/favor.js"></script>

<div id="statuses">

	<?php include('inc/sentForm.php')?>
	
	<?php 
		$t = getTwitter();
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
	
		$statuses = $t->getFavorites($p);
		testResult($statuses);
		$empty = count($statuses) == 0? true: false;
		if ($empty) {
			tpEmpty();
		} else {
			
			tpTimeline($statuses, array('show_del'=>true, 'hide_favor' => true));
			
			$output = "<div id=\"pagination\">";
			if ($p >1) $output .= "<a href=\"favor.php?p=" . ($p-1) . "\">上一页</a>";
			if (!$empty) $output .= "<a href=\"favor.php?p=" . ($p+1) . "\">下一页</a>";
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
