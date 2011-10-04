<?php
session_start();
require_once('lib/twitese.php');

$time = time()+3600*24*365;

if (isset($_REQUEST['oauth_token'])) {
	if($_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
		$_SESSION['oauth_status'] = 'bad';
		session_destroy();
		header('Location: ./login.php');
	}
	else{		
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_COOKIE['oauth_token'], $_COOKIE['oauth_token_secret']);
		
		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

		$_SESSION['access_token'] = $access_token;
		
		unset($_SESSION['oauth_token']);
		unset($_SESSION['oauth_token_secret']);
		
		setcookie('user_id',$access_token['user_id'],$time,'/');
		setcookie('screen_name',$access_token['screen_name'],$time,'/');
		setcookie('oauth_token',$access_token['oauth_token'],$time,'/');
		setcookie('oauth_token_secret',$access_token['oauth_token_secret'],$time,'/');
		
		if (200 == $connection->http_code) {
			$_SESSION['login_status'] = 'verified';
			$t = getTwitter();
			$user = $t->veverify();

			setEncryptCookie('twitese_name', $t->screen_name, $time, '/');
			setcookie('friends_count', $user->friends_count, $time, '/');
			setcookie('statuses_count', $user->statuses_count, $time, '/');
			setcookie('followers_count', $user->followers_count, $time, '/');
			setcookie('imgurl', $user->profile_image_url, $time, '/');
			setcookie('name', $user->name, $time, '/');
			
			header('Location: ./index.php');
		} else {
			session_destroy();
			header('Location: ./login.php?oauth=error');
		}
	}

}else{
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	
	$scheme = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") ? 'http' : 'https';
	$port = $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '';
	$oauth_callback = $scheme . '://' . $_SERVER['HTTP_HOST'] . $port . $_SERVER['REQUEST_URI'];
	
	$request_token = $connection->getRequestToken($oauth_callback);
	

	$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

	setcookie('oauth_token',$request_token['oauth_token'],$time,'/');
	setcookie('oauth_token_secret',$request_token['oauth_token_secret'],$time,'/');
	
	switch ($connection->http_code) {
	case 200:
		
		$url = $connection->getAuthorizeURL($request_token['oauth_token']);
		header('Location: ' . $url); 
		break;
	default:
		echo '无法连接到 Twitter.请刷新页面或重试.';
		break;
	}
}
?>