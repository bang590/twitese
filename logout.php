<?php 
	include ('lib/twitese.php');
	$time = time() - 300;
	delCookie('twitese_name');
	delCookie('twitese_pw');
	delCookie('friends_count');
	delCookie('statuses_count');
	delCookie('followers_count');
	delCookie('imgurl');
	delCookie('name');
	
	delCookie('oauth_token');
	delCookie('oauth_token_secret');
	
	delCookie('user_id');
	delCookie('screen_name');
	session_destroy();

	header('location: login.php');
?>
