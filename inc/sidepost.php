	
	<ul id=sidefunc>
		<li><a class="dropdown dropdown_normal" id="showListBtn" href="javascript:void(0);">我的推群</a></li>
		<li><a class="dropdown dropdown_normal" id="showTrendsBtn" href="javascript:void(0);">热门话题</a></li>
	</ul>
	<ul id="sidepost">
		<?php if (isset($show_stop_refresh)) {?>
		<li><input type="checkbox" id="stop_refresh" /> <label for="stop_refresh">停止自动刷新</label></li>
		<li> <a id="clear_btn" href="javascript:void(0);">清理页面</a></li>
		<?php }?>
		<?php 
			$t = getTwitter();
			$limit = $t->ratelimit();
			$reset = intval((strtotime($limit->reset_time) - time())/60);
			$remaining = $limit->remaining_hits;
			echo "<li>剩余API:$remaining {$reset}分钟后重置</li>";
		?>
		
		<li><a href="shareHelp.php"><img src="img/share.gif" /></a></li>
		<li>若有疑问请先查看<a href="http://code.google.com/p/tuite/" target="_blank">常见问题</a>
		<li>发现bug可以找我<a href="user.php?id=bang590">@bang590</a></li>
		<li>版本：V1.4.4</li>
		
	</ul>
