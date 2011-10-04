<?php 
	include ('lib/twitese.php');
	$title = "添加收藏";
	header("content-type:text/html; charset=utf-8");
	if (!isLogin()) header('location: login.php');
	
	if ( isset($_GET['id']) ) {
		if (!testReferer()) exit();
		$t = getTwitter();
		$result = $t->makeFavorite($_GET['id']);
		
		if (isset($result->created_at)) echo '收藏成功，请<a href="index.php">返回首页</a>或后退';
		else if ($result == 'favorited') echo '此消息已收藏，请<a href="index.php">返回首页</a>或后退';
		else echo '添加收藏出错，请返回重试';
		
	} else {
		echo "非法请求";
	}
		
?>