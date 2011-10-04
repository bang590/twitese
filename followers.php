<?php 
	include ('lib/twitese.php');
	$title = "关注者查看";
	include ('inc/header.php');
	
	if (!isLogin()) header('location: login.php');
	
	$type = 'followers';
	include ('inc/userlist.php');
	
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>