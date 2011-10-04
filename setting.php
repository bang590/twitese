<?php 
	include ('lib/twitese.php');
	$title = "设置";
	include ('inc/header.php');
	
	
?>
<script type="text/javascript" src="js/colorpicker.js"></script>
<script type="text/javascript" src="js/setting.js"></script>
<link rel="stylesheet" href="css/colorpicker.css" type="text/css" />

<div class="main_full">
	<div id="setting">
		<?php 
			$type = isset($_GET['t'])? $_GET['t'] : 1;
			if (isset($_POST['name'])) {
				$t = getTwitter();
				$args = array();
				$args['name'] = $_POST['name'];
				$args['url'] = $_POST['url'];
				$args['location'] = $_POST['location'];
				$args['description'] = $_POST['description'];
				$result = $t->updateProfile($args);
				if ($result) echo "<div id=\"sentTip\">修改资料成功</div>";
				else echo "<div id=\"sentTip\">修改资料失败，请重试</div>";
			}
		?>
		
		<div id="subnav">
		<?php if ($type == 1) {?>
	       	<span class="subnavNormal">twitese设置</span><span class="subnavLink"><a href="setting.php?t=2">外观设置</a></span><span class="subnavLink"><a href="setting.php?t=3">个人设置</a></span>
		<?php } else if ($type == 2) {?>
	       	<span class="subnavLink"><a href="setting.php?t=1">twitese设置</a></span><span class="subnavNormal">外观设置</span><span class="subnavLink"><a href="setting.php?t=3">个人设置</a></span>
		<?php } else {?>
			<span class="subnavLink"><a href="setting.php?t=1">twitese设置</a></span><span class="subnavLink"><a href="setting.php?t=2">外观设置</a></span><span class="subnavNormal">个人设置</span>
		<?php } ?>
	    </div>
    
	    <?php if ($type == 3) {	?>
	    <?php 
			if (!isLogin()) header('location: login.php');
			$t = isset($t)? $t : getTwitter();
	    	$user = $t->showUser();
	    ?>
	    <form id="setting_form" action="setting.php?t=3" method="post">
	        <table id="setting_table">
	        	<tr>
	        		<td class="setting_title">昵称：</td>
	        		<td><input class="setting_input" type="text" name="name" value="<?php echo isset($user->name) ? $user->name : ''?>" /></td>
	        	</tr>
	        	<tr>
	        		<td class="setting_title">网站：</td>
	        		<td><input class="setting_input" type="text" name="url" value="<?php echo isset($user->url) ? $user->url : '' ?>" /></td>
	        	</tr>
	        	<tr>
	        		<td class="setting_title">家乡：</td>
	        		<td><input class="setting_input" type="text" name="location" value="<?php echo isset($user->location) ? $user->location : '' ?>" /></td>
	        	</tr>
	        	<tr>
	        		<td class="setting_title">简介：</td>
	        		<td><textarea id="setting_text" name="description"><?php echo isset($user->description) ? $user->description : '' ?></textarea></td>
	        	</tr>
	        	<tr>
	        		<td colspan="2" style="text-align:center"><input type="submit" style="float:none;" class="submit_btn" value="保存" /></td>
	        	</tr>
	        </table>
		</form>
		<?php } else if ($type == 2) { ?>
		<?php 
			if ( isset($_POST['headerBg']) ) {
				try {
					saveStyle($_POST['headerBg'], $_POST['bodyBg'], $_POST['sideBg'], $_POST['sideNavBg'], $_POST['linkColor'], $_POST['linkHColor'], $_POST['wordColor'], $_POST['border'], $_POST['line']);
					echo "<div id=\"sentTip\">修改样式成功</div>";
				} catch (Exception $e) {
					echo "<div id=\"sentTip\">修改样式失败，请重试</div>";
				}
			}
			if ( isset($_GET['reset']) ) {
				resetStyle();
				echo "<div id=\"sentTip\">已恢复默认样式</div>";
			}
		?>
		
			<form id="style_form" action="setting.php?t=2" method="post">
			
		        <table id="setting_table">
		        	<tr>
		        		<td colspan="2" style="text-align:center">
		        			预设样式：
							<Select id="styleSelect" style="padding:2px">
								<option value="n/a">请选择</option>
							</Select>
		        		</td>
		        	</tr>
		        	<tr>
		        		<td class="style_title">头部背景：</td>
		        		<td><input class="style_input" type="text" id="headerBg" name="headerBg" value="<?php echo getColor("headerBg","#DAD6C0") ?>" /></td>
		        	</tr>
		        	<tr>
		        		<td class="style_title">内容背景：</td>
		        		<td><input class="style_input" type="text" id="bodyBg" name="bodyBg" value="<?php echo getColor("bodyBg","#F5F3EC") ?>" /></td>
		        	</tr>
		        	<tr>
		        		<td class="style_title">侧边栏背景：</td>
		        		<td><input class="style_input" type="text" id="sideBg" name="sideBg" value="<?php echo getColor("sideBg","#F9F8F5") ?>" /></td>
		        	</tr>
		        	<tr>
		        		<td class="style_title">侧边导航按钮：</td>
		        		<td><input class="style_input" type="text" id="sideNavBg" name="sideNavBg" value="<?php echo getColor("sideNavBg","#F4F4F4") ?>" /></td>
		        	</tr>
		        	<tr>
		        		<td class="style_title">链接颜色：</td>
		        		<td><input class="style_input" type="text" id="linkColor" name="linkColor" value="<?php echo getColor("linkColor","#3280AB") ?>" /></td>
		        	</tr>
		        	<tr>
		        		<td class="style_title">链接悬浮颜色：</td>
		        		<td><input class="style_input" type="text" id="linkHColor" name="linkHColor" value="<?php echo getColor("linkHColor","#000000") ?>" /></td>
		        	</tr>
		        	<tr>
		        		<td class="style_title">文字颜色：</td>
		        		<td><input class="style_input" type="text" id="wordColor" name="wordColor" value="<?php echo getColor("wordColor","#000000") ?>" /></td>
		        	</tr>
		        	<tr>
		        		<td class="style_title">边框颜色：</td>
		        		<td><input class="style_input" type="text" id="border" name="border" value="<?php echo getColor("border","#C7C5B8") ?>" /></td>
		        	</tr>
		        	<tr>
		        		<td class="style_title">横条颜色：</td>
		        		<td><input class="style_input" type="text" id="line" name="line" value="<?php echo getColor("line","#FFFFFF") ?>" /></td>
		        	</tr>
		        	<tr>
		        		<td colspan="2"><input type="submit" style="float:left;" class="submit_btn" value="保存" />
						<a href="setting.php?reset=true" class="link_btn" style="float:left;" >恢复默认</a>
		        		</td>
		        	</tr>
		        </table>
			</form>
			
		<?php } else if ($type == 1) {?>
		<?php 
			$apiUrl = '';
			$rtStyle = 0;
			$showImg = 1;
			
			if ( isset($_POST['apiurl_input']) ) {
				$time = time() + 3600*24*30;
				
				$showImg = (isset($_POST['show_img']) && $_POST['show_img'] == "on")? 1: 0;
				setcookie("rt_style", $_POST['rt_style'], $time);
				setcookie("show_img", $showImg, $time);
				
				if (preg_match("/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/", $_POST['apiurl_input'])) {
					$time = time() + 3600*24*30;
					
					setcookie("apiurl", $_POST['apiurl_input'], $time);
					
					echo "<div id=\"sentTip\">修改配置成功</div>";
					
				} else {
					if (trim($_POST['apiurl_input']) == '') {
						delCookie('apiurl');
						$apiUrl = '';
						echo "<div id=\"sentTip\">修改配置成功</div>";
					} else {
						echo "<div id=\"sentTip\">修改配置失败，API URL不合法</div>";
					}
				}
			}
			
			if (isset($_COOKIE['apiurl'])) {
				$apiUrl = $_COOKIE['apiurl'];
			}
			if (isset($_COOKIE['rt_style']) && $_COOKIE['rt_style'] == 1) {
				$rtStyle = 1;
			}
			if (isset($_COOKIE['show_img']) && $_COOKIE['show_img'] == 0) {
				$showImg = 0;
			}
				
		?>
			<form id="style_form" action="setting.php?t=1" method="post">
			
		        <table id="setting_table">
		        	<tr>
		        		<td class="style_title">自定义API：</td>
		        		<td><input type="text" id="apiurl_input" name="apiurl_input" value="<?php echo $apiUrl?>" /> (设置为空恢复默认)</td>
		        	</tr>
		        	<tr>
		        		<td class="style_title">官方RT格式：</td>
		        		<td>
		        			<input type="radio" name="rt_style" id="rt_style1" value="0" <?php echo $rtStyle ? '' : 'checked="checked"'?> /><label for="rt_style1">显示RT者头像，与普通RT方式一致</label>
		        		</td>
		        	</tr>
		        	<tr>
		        		<td class="style_title"></td>
		        		<td>
		        			<input type="radio" name="rt_style" id="rt_style2" value="1" <?php echo $rtStyle ? 'checked="checked"' : ''?> /><label for="rt_style2">显示原作者头像，作者名字前有RT标志，底部显示RT by xx</label>
		        		</td>
		        	</tr>
		        	<tr>
		        		<td class="style_title">图片显示：</td>
		        		<td><input type="checkbox" name="show_img" <?php echo $showImg ? 'checked="checked"': ""?> /> <label for="show_img">自动显示图片</label></td>
		        	</tr>
		        	<tr>
		        		<td colspan="2" style="text-align:center"><input type="submit" style="float:none" class="submit_btn" value="保存" />
		        		</td>
		        	</tr>
		        </table>
			</form>
		
		<?php }?>
	</div>
</div>


<?php 
	include ('inc/footer.php');
?>