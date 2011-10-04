<?php 
	include ('lib/twitese.php');
	$title = "删除消息";
	header("content-type:text/html; charset=utf-8");
	if (!isLogin()) header('location: login.php');
	if ( isset($_POST['id']) && isset($_POST['t']) ) {
		if (!testReferer()) exit();
		$t = getTwitter();
		switch ($_POST['t']) {
			case 's':
				$result = $t->deleteStatus($_POST['id']);
				break;
			case 'f':
				$result = $t->removeFavorite($_POST['id']);
				break;
			case 'm':
				$result = $t->deleteDirectMessage($_POST['id']);
				break;
				
		}
		if ($result) echo '删除成功，请<a href="index.php">返回首页</a>或后退';
		else echo '添加收藏出错，请返回重试';
	} else {
		if ( isset($_GET['id']) && isset($_GET['t']) ) {
			$id = $_GET['id'];
			$type = $_GET['t'];
			echo "
				<form action=\"a_del.php\" method=\"post\">
					确定删除?
					<input type=\"hidden\" name=\"id\" value=\"$id\" />
					<input type=\"hidden\" name=\"t\" value=\"$type\" />
					<input type=\"submit\" value=\"确定\" />
					<a href=\"index.php\">取消</a>
				</form>
			";
		} else {
			echo "非法请求";
		}
	}
?>