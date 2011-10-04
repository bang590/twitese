<?php 
	include ('lib/twitese.php');
	$title = "私信";
	include ('inc/header.php');
	
	if (!isLogin()) header('location: login.php');
?>

<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/message.js"></script>

<?php 
	$isSentPage = isset($_GET['t'])? true : false;
?>
<div id="statuses">
	<div id="subnav">
	<?php if ($isSentPage) {?>
       	<span class="subnavLink"><a href="message.php">我收到的私信</a></span><span class="subnavNormal">我发出的私信</span>
	<?php } else {?>
       	<span class="subnavNormal">我收到的私信</span><span class="subnavLink"><a href="message.php?t=sent">我发出的私信</a></span>
	<?php } ?>
    </div>
    
	<?php 
		$t = getTwitter();
		
		if ( isset($_POST['sent_id']) && isset($_POST['message']) ) {
			$msg = '';
			if (trim($_POST['message']) == '') {
				echo "<div id=\"sentTip\">发送私信失败，消息不能为空</div>";
			} else {
				$result = $t->sendDirectMessage(trim($_POST['sent_id']), $_POST['message']);
				if (!$result) {
					echo "<div id=\"sentTip\">发送私信失败，请重试</div>";
				} if ($result == 'nofollow') {
					echo "<div id=\"sentTip\">对方没有加你为好友，无法发送私信</div>";
				} else {
					echo "<div id=\"sentTip\">发送私信成功</div>";
				}
			}
		}
		
	?>
	
	<form action="message.php" method="post">
	<?php if ( isset($_GET['id']) ) { ?>
	<h2>给 <input type="text" name="sent_id" id="sent_id" value="<?php echo $_GET['id'] ?>"/> 发私信</h2>
	<?php	} else { ?>
	<h2>给 <input type="text" name="sent_id" id="sent_id" /> 发私信</h2>
	<?php	} ?>
	<span id="tip">还可以输入<b>140</b>个字</span>
		<textarea name="message" id="textbox"></textarea>
		<input type="submit" id="submit_btn" title="按ctrl+enter键发送" value="发送" />
	</form>
	<div class="clear"></div>
	
	
	
	<?php 
		$t = getTwitter();
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
	
		if ($isSentPage) {
			$messages = $t->sentDirectMessage($p);
		} else {
			$messages = $t->directMessages($p);
		}
		if ($messages === false) {
			header('location: error.php');
		} 
		$empty = count($messages) == 0? true: false;
		if ($empty) {
			echo "<div id=\"empty\">此页无消息</div>";
		} else {
			$output = '<ol class="timeline" id="allTimeline">';
			
			foreach ($messages as $message) {
				$name = $message->sender_screen_name;
				$imgurl = $message->sender->profile_image_url;
				$date = formatDate($message->created_at);
				$text = formatText($message->text);
				
				$output .= "
					<li>
						<span class=\"status_author\">
							<a href=\"user.php?id=$name\" target=\"_blank\"><img src=\"$imgurl\" title=\"$name\" /></a>
						</span>
						<span class=\"status_body\">
							<span class=\"status_id\">$message->id </span>
							<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$name\">$name </a> $text </span>
							<span class=\"status_info\">
				";
				if (!$isSentPage) {
					$output .= "<a class=\"msg_replie_btn\" href=\"message.php?id=$name\">回复</a>";
				} else {
					$output .= "<a class=\"delete_btn\" href=\"a_del.php?id=$message->id&t=m\">删除</a>";
				}
				$output .="		<span class=\"date\">$date</span>
						    </span>
						</span>
					</li>
				";
			}
			
			$output .= "</ol><div id=\"pagination\">";
			
			
			if ($isSentPage) {
				if ($p >1) $output .= "<a href=\"message.php?t=sent&p=" . ($p-1) . "\">上一页</a>";
				if (!$empty) $output .= "<a href=\"message.php?t=sent&p=" . ($p+1) . "\">下一页</a>";
			} else {
				if ($p >1) $output .= "<a href=\"message.php?p=" . ($p-1) . "\">上一页</a>";
				if (!$empty) $output .= "<a href=\"message.php?p=" . ($p+1) . "\">下一页</a>";
			}
			
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
