<?php 
	include ('lib/twitese.php');
	header("content-type:text/html; charset=utf-8");
	if (!isLogin()) header('location: login.php');
	
	if ( isset($_GET['id']) ) {
		if (!testReferer()) exit();
		$t = getTwitter();
		$result = $t->addRT($_GET['id']);
		if (!$result) echo 'RT出错，请返回重试';
		else if (isset($result->errors)) echo '错误，你已RT过此推，请<a href="index.php">返回首页</a>或后退';
		else echo 'RT成功，请<a href="index.php">返回首页</a>或后退';
	} else {
		echo "非法请求";
	}
?>