<?php 
	include ('lib/twitese.php');
	$title = "推群成员";
	include ('inc/header.php');
?>
<script type="text/javascript" src="js/list_members.js"></script>
<?php
	$type = 'list_members';
	include ('inc/userlist.php');
	
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>