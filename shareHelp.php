<?php include ('lib/twitese.php') ?>
<?php $title = "分享帮助" ?>
<?php include ('inc/header.php') ?>
<?php 
	$url = str_replace('shareHelp', 'share', 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER['PHP_SELF']);
?>
<div id="login_area">
	<div id="faq">
		<h1>分享到Twitter功能</h1>
		<h2>添加分享按钮</h2>
		<p><a href="javascript:var%20d=document,w=window,f='<?php echo $url ?>',l=d.location,e=encodeURIComponent,p='?u='+e(l.href)+'&t='+e(d.title)+'&d='+e(w.getSelection?w.getSelection().toString():d.getSelection?d.getSelection():d.selection.createRange().text)+'&s=bm';a=function(){if(!w.open(f+p,'sharer','toolbar=0,status=0,resizable=0,width=600,height=350'))l.href=f+'.new'+p};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else{a()}void(0);"><img src="img/share.gif" /></a></p>
		<p>右击上方图片，IE选择“添加到收藏夹”，firefox选择“将此链接加入书签”（注意改名字），若不成功请尝试其他方法，目的将其加入收藏夹。其他浏览器按其各自的方法把上面的链接添加至收藏夹，为了使用方便快捷，推荐移至收藏夹/书签的工具栏。</p>
		<h2>使用分享按钮</h2>
		<p>浏览网页时，想将当前浏览的网页分享至twitter，则点击上面收藏的链接，即出现一窗口，编辑内容后点击发送即可成功分享到Twitter。若未登录推特中文圈，则会提示先登录。</p>
		<p>分享时默认内容为网页的标题，当浏览的网页有选择内容时，分享的内容会变成选择部分的内容。</p>
	</div>
</div>	

<?php include ('inc/footer.php') ?>
