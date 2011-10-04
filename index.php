<?php 
	include ('lib/twitese.php');
	if (!isLogin()) {
		header('location: login.php');
		exit();
	}
	$title = "首页";
	include ('inc/header.php');
?>
<script type="text/javascript" src="js/home.js"></script>

<div id="statuses">
	<?php 
		$t = getTwitter();
		if ( isset($_POST['status']) && isset($_POST['in_reply_to']) ) {
			if (!testReferer()) exit();
			if (trim($_POST['status']) == '') {
				setUpdateCookie('empty');
			} else {
				$result = $t->update($_POST['status'], $_POST['in_reply_to']);
				if ($result) {
					setUpdateCookie('success');
					
					$user = $result->user;
					$time = time()+3600*24*365;
					if ($user) {
							setcookie('friends_count', $user->friends_count, $time, '/');
							setcookie('statuses_count', $user->statuses_count, $time, '/');
							setcookie('followers_count', $user->followers_count, $time, '/');
							setcookie('imgurl', $user->profile_image_url, $time, '/');
							setcookie('name', $user->name, $time, '/');
					}
				}
				else {
					setUpdateCookie('error');
				}
			}
			
			header('location: index.php');
		}
		
		if (getUpdateCookie()) {
			switch (getUpdateCookie()) {
				case 'success':
					echo "<div id=\"sentTip\">发送消息成功</div>";
					break;
				case 'empty':
					echo "<div id=\"sentTip\">发送失败，消息不能为空</div>";
					break;
				case 'error':
					echo "<div id=\"sentTip\">发送消息失败，请重试</div>";
					break;
				default:
					break;
			}
		}
	?>
	
	<?php include('inc/sentForm.php')?>
	
	<?php 
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
	
		$statuses = $t->homeTimeline($p);
		
		testResult($statuses);
		$empty = count($statuses) == 0? true: false;
		if ($empty) {
			tpEmpty();
		} else {
			tpTimeline($statuses);
			
			$output = "<div id=\"pagination\">";
			
			if ($p >1) $output .= "<a href=\"index.php?p=" . ($p-1) . "\">上一页</a>";
			if (!$empty) $output .= "<a href=\"index.php?p=" . ($p+1) . "\">下一页</a>";
			
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
