<?php 
	include ('lib/twitese.php');
	$title = "修改好友关系";
	header("content-type:text/html; charset=utf-8");
	if (!isLogin()) header('location: login.php');
	if (!testReferer()) exit();
	
	if ( isset($_POST['action']) && isset($_POST['id']) ) {
		$t = getTwitter();
		if ($_POST['action'] == 'create') {
			$result = $t->followUser($_POST['id']);
			if ($result) echo '添加好友成功，请<a href="index.php">返回首页</a>或后退';
			else echo '添加好友出错，请返回重试';
		} else if ($_POST['action'] == 'destory') {
			$result = $t->destroyUser($_POST['id']);
			if ($result) echo ' 删除好友成功，请<a href="index.php">返回首页</a>或后退';
			else echo '删除好友出错，请返回重试';
		}
	} else {
		if ( isset($_GET['action']) && isset($_GET['id']) ) {
			$id = $_GET['id'];
			$action = $_GET['action'];
			$msg = $action == 'create' ? "确定添加 $id 为好友?" : "确定删除好友 $id ?";
			echo "
				<form action=\"a_relation.php\" method=\"post\">
					$msg
					<input type=\"hidden\" name=\"id\" value=\"$id\" />
					<input type=\"hidden\" name=\"action\" value=\"$action\" />
					<input type=\"submit\" value=\"确定\" />
					<a href=\"index.php\">取消</a>
				</form>
			";
		} else {
			echo "非法请求";
		}
	}
?>