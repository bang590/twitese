<script type="text/javascript" src="js/userlist.js"></script>
<div id="statuses">
	<?php
		$t = getTwitter();
		$t->type = 'xml';
		$p = -1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = -1;
		}
		
		$c = -1;
		if (isset($_GET['c'])) {
			$c = $_GET['c'];
		}
		
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		
		switch ($type) {
			case 'friends':
				echo $id ? "<h2>" . $id . "的好友</h2>": "<h2>我的好友</h2>";
				break;
			case 'followers':
				echo $id ? "<h2>" . $id . "的关注者</h2>": "<h2>我的关注者</h2>";
				break;
			case 'list_members':
				echo "<h2>推群<strong>$id</strong> 成员</h2>";
				break;
			case 'list_followers':
				echo "<h2>推群$id 关注者</h2>";
				break;
			case 'browse':
				echo "<h2>随便看看其他人在做什么...</h2>";
				break;
		}
		
		echo '<div class="clear"></div>';
		
		switch ($type) {
			case 'friends':
				$userlist = $t->friends($id, $c, 30, 'xml');
				$nextpage = (string) $userlist->next_cursor;
				$prepage = (string) $userlist->previous_cursor;
				$userlist = $userlist->users->user;
				break;
			case 'followers':
				$userlist = $t->followers($id, $c, 30, 'xml');
				$nextpage = (string) $userlist->next_cursor;
				$prepage = (string) $userlist->previous_cursor;
				$userlist = $userlist->users->user;
				break;
			case 'list_members':
				$userlist = $t->listMembers($id, $c);
				$nextpage = (string) $userlist->next_cursor;
				$prepage = (string) $userlist->previous_cursor;
				$userlist = $userlist->users->user;
				break;
			case 'list_followers':
				$userlist = $t->listFollowers($id, $c);
				$nextpage = (string) $userlist->next_cursor;
				$prepage = (string) $userlist->previous_cursor;
				$userlist = $userlist->users->user;
				break;
			case 'browse':
				$userlist = $t->followers($id, $p);
				break;
		}
		$empty = count($userlist) == 0? true: false;
		
		if ($empty) {
			echo "<div id=\"empty\">此页无信息</div>";
		} else {
			$output = '<ol class="rank_list">';
			foreach ($userlist as $user) {
				$output .= "
				<li>
					<span class=\"rank_img\"><a href=\"user.php?id=$user->screen_name\"><img src=\"$user->profile_image_url\" /></a></span>
					<div class=\"rank_content\">
						<span class=\"rank_num\"><span class=\"rank_name\"><a href=\"user.php?id=$user->screen_name\">$user->name</a></span> <span class=\"rank_screenname\">($user->screen_name)</span>";
				
				if ($user->following == 'true') $output .= " <a class=\"unfollow_btn\" href=\"a_relation.php?action=destory&id=$user->id\">[取消关注]</a> </span>";
				else $output .= " <a class=\"follow_btn\" href=\"a_relation.php?action=create&id=$user->id\">[关注此人]</a> </span>";
				$output .= "<span class=\"rank_count\">关注者：$user->followers_count 　好友：$user->friends_count 　消息数：$user->statuses_count</span>
				";
				if ($user->description) $output .= "<span class=\"rank_description\">简介：$user->description</span>";
				$list_id = explode("/",$id);
				if ($type == 'list_members' &&  $list_id[0] == $t->username) $output .= "<span class=\"status_info\"><a class=\"delete_btn list_delete_btn\" href=\"javascript:void()\">删除</a></span>";
				$output .= "
					</div>
				</li>
				";
			}
			
			$output .= "</ol><div id=\"pagination\">";
			if ($type == 'list_members' || $type == 'list_followers') {
				if ($prepage != 0) $output .= "<a href=\"list_members.php?id=$id&c=$prepage\">上一页</a>";
				if ($nextpage != 0) $output .= "<a href=\"list_members.php?id=$id&c=$nextpage\">下一页</a>";
			} else if ($type == "browse") {
				if ($p >1) $output .= "<a href=\"$type.php?p=" . ($p-1) . "\">上一页</a>";
				if (!$empty) $output .= "<a href=\"$type.php?p=" . ($p+1) . "\">下一页</a>";
			}else {
				if ($id) {
					if ($prepage != 0) $output .= "<a href=\"$type.php?id=$id&c=$prepage\">上一页</a>";
					if ($nextpage != 0) $output .= "<a href=\"$type.php?id=$id&c=$nextpage\">下一页</a>";
				} else {
					if ($prepage != 0) $output .= "<a href=\"$type.php?c=$prepage\">上一页</a>";
					if ($nextpage != 0) $output .= "<a href=\"$type.php?c=$nextpage\">下一页</a>";
				}
			}
			$output .= "</div>";
			
			echo $output;
		}
	?>
</div>