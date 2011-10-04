<?php 
	include ('lib/twitese.php');
	$title = "搜索";
	include ('inc/header.php');
	
	if (!isLogin()) header('location: login.php');
?>

<script type="text/javascript" src="js/search.js"></script>

<div id="statuses">

	<form action="search.php" method="get" id="search_form">
		<input type="text" id="query" name="q" />
		<input type="submit" id="search_btn" value="搜索" />
		<span id="lang"><input type="checkbox" name="l"> 只搜中文</span>
	</form>
	
	<?php 
	if (isset($_GET['q']) && trim($_GET['q']) != '') {
		$t = getTwitter();
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
		
		if ( !isset($_GET['q']) ) {
			header('location: error.php');
		} else {
			$q = $_GET['q'];
		}
		//纯中文每个字符间加空格
		if (!eregi("[^\x80-\xff]",$q)) {
			$string = $q;
		    $strlen = mb_strlen($string);
		    while ($strlen) {
		        $array[] = mb_substr($string,0,1,"UTF8");
		        $string = mb_substr($string,1,$strlen,"UTF8");
		        $strlen = mb_strlen($string);
		    }
        	$q = implode(" ",$array);
		}
		
		if ( isset($_GET['l']) && $_GET['l'] == 'on') {
			$statuses = $t->search($q, $p, "zh");
		} else {
			$statuses = $t->search($q, $p);
		}
		
		testResult($statuses);
		$empty = count($statuses) == 0? true: false;
		if ($empty) {
			echo "<div id=\"empty\">此页无消息</div>";
		} else {
			$output = '<ol class="timeline" id="allTimeline">';
			
			foreach ($statuses->results as $status) {
				$date = formatDate($status->created_at);
				$text = formatText($status->text);
				
				$output .= "
					<li>
						<span class=\"status_author\">
							<a href=\"user.php?id=$status->from_user\" target=\"_blank\"><img src=\"$status->profile_image_url\" title=\"$status->from_user\" /></a>
						</span>
						<span class=\"status_body\">
							<span class=\"status_id\">$status->id</span>
							<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$status->from_user\">$status->from_user</a> $text </span>
							"; 
				if ($shorturl = unshortUrl($status->text)) $output .= "<span class=\"unshorturl\">$shorturl</span>";
				$output .= 
							"<span class=\"status_info\">
								<a class=\"replie_btn\" href=\"a_reply.php?id=$status->id\">回复</a><a class=\"rt_btn\" href=\"a_rt.php?id=$status->id\">回推</a><a class=\"ort_btn\" href=\"a_ort.php?id=$status->id\">官方RT</a><a class=\"favor_btn\" href=\"a_favor.php?id=$status->id\">收藏</a>
								<span class=\"source\">通过 ".html_entity_decode($status->source)."</span>
								<span class=\"date\"><a href=\"https://twitter.com/$status->from_user/status/$status->id\" target=\"_blank\">$date</a></span>
						    </span>
						</span>
					</li>
				";
			}
			
			$output .= "</ol><div id=\"pagination\">";
			
			if ( isset($_GET['l']) && $_GET['l'] == 'on') {
				if ($p >1) $output .= "<a href=\"search.php?q=$q&l=on&p=" . ($p-1) . "\">上一页</a>";
				if (!$empty) $output .= "<a href=\"search.php?q=$q&l=on&p=" . ($p+1) . "\">下一页</a>";
			} else {
				if ($p >1) $output .= "<a href=\"search.php?q=$q&p=" . ($p-1) . "\">上一页</a>";
				if (!$empty) $output .= "<a href=\"search.php?q=$q&p=" . ($p+1) . "\">下一页</a>";
			}
			
			$output .= "</div>";
			
			echo $output;
		}
		
		
	}
	?>
</div>

<?php 
	include ('inc/sidebar.php');
?>

<?php 
	include ('inc/footer.php');
?>
