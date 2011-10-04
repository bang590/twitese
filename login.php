<?php include ('lib/twitese.php') ?>
<?php $title = "登录" ?>
<?php include ('inc/header.php') ?>
<?php 
	if(isLogin()) header("location: /");
?>
<div id="login_area">
	<?php if (!function_exists('curl_init')) { ?>
	<div id="description">
		<p style="text-align: center; color:red;">空间不支持curl，无法使用twitese</p>
	</div>
	<?php } else {?>
	<div class="description">
		<p>Twitese推特中文圈旨在帮助中国twitter使用者寻找国内优秀twitter用户，同时让大陆用户无需翻墙即可更新状态和浏览好友消息。</p>
		<p>Twitese有两个版本，其一架设在Google App Engine上，由java语言编写，另一个PHP版本开源，可由任何人自由架设在自己服务器上，<a href="http://blog.webbang.net/?p=1000" target="_blank">详细</a>。开源主页：<a href="http://code.google.com/p/twitese/">http://code.google.com/p/twitese/</a></p>
	</div>
	<div class="login">
		<h3>OAUTH代理方式登录(<b class="red">无需翻墙</b>)</h3>
		<form method="post" action="oauth_proxy.php">
			<div><label class="login_label" for="username">用户名：</label><input type="text" id="username" name="username" /></div>
			<div><label class="login_label" for="password">密码：</label><input type="password" id="password" name="password" /></div>
			<?php if (TWITESE_PASSWORD != '') {?>
			<div><label class="login_label" for="twitese_password">附加密码：</label><input type="password" id="password" name="twitese_password" /></div>
			<?php }?>
			<input type="submit" id="login_btn" value="登录" />
		</form>
	</div>
	<?php if(CONSUMER_KEY && CONSUMER_SECRET){?>
		<div class="clear"></div>
		<div class="description">
			<p><h3>使用OAuth登陆</h3></p>
			<p>OAuth是twitter为了用户安全使用第三方应用推出的验证系统。使用OAuth登陆不需要在第三方程序输入密码，只需要点击相应的链接到twitter首页对此第三方应用进行授权，即可等同于在第三方应用登陆了twitter。</p>
			<p>点击下面的OAuth登陆按钮后会跳转到twitter验证页面，点击allow即可登录推特中文圈。</p>
			<p>注意，因为需要上twitter官方网进行验证，所以此登陆方法<b>需翻墙</b>，此方法绝对不会暴露密码给第三方，<b>适合于对账号安全要求较高的用户。</b></p>
			<p>另外，如果你还没有Twitter帐号，也<b>需翻墙</b>注册。</p>
			<p>怎么翻墙? 没有VPN或者HTTPS代理的用户，可以直接改hosts文件翻墙: <b>128.242.245.148 twitter.com www.twitter.com</b> *nix系统：/etc/hosts; Windows: C:/Windows/system32/drivers/etc/hosts </p>
			<p style="margin-left:300px;"><a href="oauth.php" class="link_btn">OAuth登陆</a></p>
		</div>
    <?php }?>
	<?php }?>
</div>	

<?php include ('inc/footer.php') ?>
