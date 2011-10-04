<?php 
	include ('lib/twitese.php');
	$title = "@回复我的";
	include ('inc/header.php');
	
	if (!isLogin()) header('location: login.php');
?>

<script type="text/javascript">
	$(function(){
		formFunc();
		timelineFocus();
		$(".rt_btn").live("click", function(e){
			e.preventDefault();
			onRT($(this));
		});
		
		$(".replie_btn").live("click", function(e){
			e.preventDefault();
			onReplie($(this));
		});
		$(".favor_btn").live("click", function(e){
			e.preventDefault();
			onFavor($(this));
		});
	});
</script>

<div id="statuses">

	<?php include('inc/sentForm.php')?>
	
	<?php 
		$t = getTwitter();
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
	
		$statuses = $t->replies($p);
		testResult($statuses);
		$empty = count($statuses) == 0? true: false;
		if ($empty) {
			tpEmpty();
		} else {
			tpTimeline($statuses, array("is_mention"=>true));
			
			$output = "<div id=\"pagination\">";
			if ($p >1) $output .= "<a href=\"replies.php?p=" . ($p-1) . "\">上一页</a>";
			if (!$empty) $output .= "<a href=\"replies.php?p=" . ($p+1) . "\">下一页</a>";
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
