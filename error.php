<?php include ('lib/twitese.php') ?>
<?php $title = "错误" ?>
<?php include ('inc/header.php') ?>

<div class="main_full">
	<div id="error">
		<?php 
			$msg = '未知错误 请返回重试，或<a href="login.php">重新登录</a>';
			if (isset($_GET['url'])) {
				$msg = '未知错误 请<a href="' . $_GET['url'] . '">点此返回重试</a>，或<a href="login.php">重新登录</a>';
			}
			if (isset($_GET["type"])) {
				switch ($_GET["type"]) {
					case 1:
						$msg = '错误！无法连接到twitter，请重试';
						if (isset($_GET['url'])) {
							$msg = '错误！无法连接到twitter，请<a href="' . $_GET['url'] . '">点此返回重试</a>';
						}
						break;
					case 2:
						$msg = '错误！API请求超过限制，请稍候再试';
						if (isset($_GET['url'])) {
							$msg = '错误！API请求超过限制，请稍候<a href="' . $_GET['url'] . '">再试</a>';
						}
						break;
				}
			}
		?>
		<p><?php echo $msg?></p>
	</div>
</div>	

<?php include ('inc/footer.php') ?>
