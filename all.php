<?php 
	include ('lib/twitese.php');
	$title = "全部消息";
	include ('inc/header.php');
	
	if (!isLogin()) header('location: login.php');
	
?>
<script type="text/javascript" src="js/all.js"></script>
<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript" src="js/formfunc.js"></script>
<div id="statuses">

	<h2>随便说说</h2>
	<div id="sent_function">
		<a href="javascript:void(0)" id="photoBtn"><img src="img/photo.gif" /></a>
		<a href="javascript:void(0)" id="linkBtn"><img src="img/link.gif" /></a>
	</div>
	<span id="tip">还可以输入<b>140</b>个字</span>
	
	<form enctype="multipart/form-data" action="ajax/uploadPhoto.php" method="post" id="photoArea">
		<span>图片上传：</span>
		<input name="image" id="imageFile" type="file" />
		<input type="submit" id="imageUploadSubmit" value="提交" />
	</form>
	
	<div id="linkArea">
		<label>缩短URL：</label><input type="text" name="longurl" id="longurl" />
		<select name="shortUrlType" id="shortUrlType">
			<option value="aa.cx">aa.cx</option>
			<option value="is.gd">is.gd</option>
			<option value="s8.hk">s8.hk</option>
		</select>
		<input type="button" value="提交" id="linkSubmit" />
	</div>

	<form action="index.php" method="post">
		<textarea name="status" id="textbox"></textarea>
		<input type="hidden" id="in_reply_to" name="in_reply_to" value="0" />
		
		<div id="allNav">
			<a class="allBtn allHighLight" id="allTimelineBtn" href="javascript:void(0);">好友消息</a>
			<a class="allBtn" id="allRepliesBtn" href="javascript:void(0);">@回复我的</a>
			<a class="allBtn" id="allMessageBtn" href="javascript:void(0);">私信</a>
			<a class="allBtn" id="refreshBtn" href="javascript:void(0);">刷新</a>
		</div>
		
		<input type="submit" id="submit_btn" title="按Ctrl+Enter可以快捷发送" value="发送" />
	</form>
	<div class="clear"></div>
	<?php
		$t = getTwitter();
		$statuses = $t->homeTimeline();
		testResult($statuses);
		
		$empty = count($statuses) == 0? true: false;
		if ($empty) {
			echo "<div id=\"allTimeline\" class=\"empty\">此页无消息</div>";
		} else {
			tpTimeline( $statuses, array("id" => 'allTimeline') );
		}
		
		$statuses = $t->replies();
		testResult($statuses);
		
		$empty = count($statuses) == 0? true: false;
		if ($empty) {
			echo "<div id=\"allReplies\" class=\"empty\">此页无消息</div>";
		} else {
			tpTimeline( $statuses, array("is_mention"=>true, "id" => 'allReplies') );
		}
		
		
		$messages = $t->directMessages();
		testResult($statuses);
		
		$empty = count($messages) == 0? true: false;
		if ($empty) {
			echo "<div id=\"allMessage\" class=\"empty\">此页无消息</div>";
		} else {
			$output = '<div id="allMessage">';
			$output .= '<ol class="timeline">';
			
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
							<span class=\"status_id\">$message->id</span>
							<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$name\">$name</a> $text </span>
							<span class=\"status_info\">
								<a class=\"msg_replie_btn\" href=\"message.php?id=$name\">回复</a>
								<span class=\"date\">$date</span>
						    </span>
						</span>
					</li>
				";
			}
			
			$output .= "</ol>";
			
			$output .= '</div>';
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
