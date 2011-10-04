<?php 
	include ('lib/twitese.php');
	$title = "登陆";
	include ('inc/header.php');
	
?>

<div id="login_area">
	<div id="error">

<?php
	if ( isset($_POST['username']) && isset($_POST['password']) ) {
		
		//附加密码
		if ( TWITESE_PASSWORD != '' && $_POST['twitese_password'] != TWITESE_PASSWORD) {
			echo "<p>附加密码错误。<a href='login.php'>返回重新登录</a></p>";
		} else {
			$remember = isset($_POST['remember']) ? true : false;
			$result = verify($_POST['username'], $_POST['password'], $remember);
			
			if ($result === 'password') {
				echo "<p>登陆失败，用户名密码错误，请返回重试</p>";
			} else if ($result === 'noconnect') {
				echo "<p>登陆失败，服务器连接不上twitter，请检查API</p>";
			} else if (!$result) {
				echo "<p>登陆失败，未知错误</p>";
			} else {
				header('location: index.php');
			}
			
		}
	} else {
		echo "<p>非法请求，请返回</p>";
	}
?>

	</div>
</div>	

<?php 
	include ('inc/footer.php');
?>
