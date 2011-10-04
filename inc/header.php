<?php ob_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="twitter,推特,微博客,翻墙,推特中文圈,twitese" />
<meta name="description" content="推特中文圈提供更友好的界面和功能让你不翻墙使用twitter" />
<link rel="shortcut icon" href="img/favicon.ico" />
<link type="text/css" href="css/main.css" rel="stylesheet" />
<title><?php echo $title ?> <?php echo SITE_NAME ?> Twitese</title>
<?php 
	$headerBg = getColor("headerBg","#DAD6C0");
	$bodyBg = getColor("bodyBg","#F5F3EC");
	$sideBg = getColor("sideBg","#F9F8F5");
	$sideNavBg = getColor("sideNavBg","#F4F4F4");
	$linkColor = getColor("linkColor","#3280AB");
	$linkHColor = getColor("linkHColor","#000000");
	$wordColor = getColor("wordColor","#000000");
	$border = getColor("border","#C7C5B8");
	$line = getColor("line","#FFFFFF");
?>
<style type="text/css">
	#header{background-color:<?php echo $headerBg ?>}
	#footer{background-color:<?php echo $headerBg ?>}
	#sidebar{background-color:<?php echo $sideBg ?>;border-color:<?php echo $border ?>}
	#sidenav a{background-color:<?php echo $sideNavBg ?>}
	#content{background-color:<?php echo $bodyBg ?>}
	a{color:<?php echo $linkColor ?>}
	a:hover{color:<?php echo $linkHColor ?>}
	body{color:<?php echo $wordColor ?>;background-color:<?php echo $headerBg ?>}
	#statuses{border-color:<?php echo $border ?>}
	.white_line{background-color:<?php echo $line ?>}
</style>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/public.js"></script>


	
</head>

<body>
		<div class="warpper">
		<div class="fixtip"></div>
		</div>
	<div id="header">
		<div class="warpper relative">
			<a href="index.php"><img id="logo" src="img/logo.png" /></a>
			<ul id="nav">
				<li><a href="index.php">首页</a></li>
				<li><a href="browse.php">随便看看</a></li>
				<li><a href="rank.php">排行榜</a></li>
				<li><a href="setting.php">设置</a></li>
				<li><a href="logout.php">退出</a></li>				
				<li id="header_search">
				    <form action="search.php" method="get">
				    <input type="text" id="header_search_query" name="q" />
				    <input type="submit" id="header_search_submit" value="搜索" />
				    </form>
    			</li>
			</ul>
		</div>
	</div>
	<div class="white_line"></div>
	<div id="content">
		<div class="warpper">
