<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="css/share.css" rel="stylesheet" />
<title>分享到Twitter - <?php echo SITE_NAME ?></title>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/public.js"></script>
<script type="text/javascript">
$(function(){leaveWord();
	$("#textbox").focus();
	$("#textbox").keydown(function(){leaveWord(110);}).keyup(function(){leaveWord(110);})
});
</script>
</head>

<body>
<?php 
	include ('lib/twitese.php');
	$t = getTwitter();
	if ( isset($_POST['status']) && isset($_POST['url']) ) {
		$status = $_POST['status'];
		/*
		if (mb_strlen($status) > 110) {
			$status = substr($status, 0, 110);
		}
		*/
		$shortUrl = shortUrl($_POST['url']);
		if ($shortUrl) {
			$status .= $shortUrl;
		} else {
			$status .= ' ' . $_POST['url'];
		}
		$result = $t->update($status);
	}
	
	$text = '';
	
	if ( isset($_GET['u']) ) {
		$url = $_GET['u'];
	}
	
	if ( isset($_GET['t']) ) {
		$title = $_GET['t'];
		$text = $_GET['t'];
	}
	
	if ( isset($_GET['d']) ) {
		$select = $_GET['d'];
		if ( trim($select) != "" ) $text = $select;
	}
	
	$text = "分享: " . $text . " ";
	
	$siteUrl = str_replace('share', 'index', 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER['PHP_SELF']);

	?>
<div id="share">

	<?php if ( !$t->username ) {?>
		<div id="message">请先<a href="login.php" target="_blank">登录<?php echo SITE_NAME ?></a></div>
	<?php } else if ( isset($_POST['status']) ) { 
			if ($result) {
	?>
				<div id="message">分享成功，请访问<a href="<?php echo $siteUrl?> target="_blank"><?php echo SITE_NAME ?> </a>查看。一秒后会自动<a href="javascript:window.close()">关闭窗口</a></div>
					<script type="text/javascript">
					setTimeout("window.close()",1000);
					</script>
		<?php } else { ?>
				<div id="message">分享失败，请重试。<a href="javascript:window.history.go(-1)">后退</a></div>
		<?php 
			}
	   } else { 
	?>
		<form action="share.php" method="post">
		<table>
			<tr>
				<td colspan="2"><h2>分享到Twitter</h2><span id="tip">还可以输入<b>140</b>个字</span></td>
			</tr>
			<tr>
				<td class="title">网址:</td>
				<td><input type="text" name="url" id="url" value="<?php echo $url?>"/></td>
			</tr>
			<tr>
				<td class="title">内容:</td>
				<td><textarea name="status" id="textbox"><?php echo $text?></textarea></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" id="submit_btn" value="分享" /></td>
			</tr>
		</table>
		</form>
	<?php } ?>
</div>
<div id="copyright"><p>Copyright © <a href="https://twitter.com/bang590">@bang590</a> | <a href="http://twitese.appspot.com/" target="_blank">推特中文圈 </a></p></div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-1895639-5");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>