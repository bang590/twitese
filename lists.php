<?php 
	include ('lib/twitese.php');
	$title = "推群列表";
	include ('inc/header.php');
	
	if (!isLogin()) header('location: login.php');
?>

<script type="text/javascript" src="js/lists.js"></script>

<div id="statuses">
	<?php 
		$t = getTwitter();
		$t->type = 'xml';
		if ( isset($_POST['list_name']) ) {
			if ($_POST['is_edit'] == 0) {
				if (trim($_POST['list_name']) == '') {
						echo "<div id=\"sentTip\">创建推群失败，推群名不能为空</div>";
				} else {
					$isProtect = isset($_POST['list_protect']) ? true : false;
					$result = $t->createList($_POST['list_name'], $_POST['list_description'], $isProtect);
					if ($result) {
						echo "<div id=\"sentTip\">创建推群成功</div>";
					} else {
						echo "<div id=\"sentTip\">创建推群失败，请重试</div>";
					}
				}
			} else {
				if (trim($_POST['list_name']) == '') {
						echo "<div id=\"sentTip\">修改推群失败，推群名不能为空</div>";
				} else {
					$isProtect = isset($_POST['list_protect']) ? true : false;
					$result = $t->editList($_POST['pre_list_name'], $_POST['list_name'], $_POST['list_description'], $isProtect);
					if ($result) {
						echo "<div id=\"sentTip\">修改推群成功</div>";
					} else {
						echo "<div id=\"sentTip\">修改推群失败，请重试</div>";
					}
				}
			}
		}
		
		if ( isset($_POST['list_members']) ) {
			if (trim($_POST['list_members']) == '') {
					echo "<div id=\"sentTip\">添加成员失败，成员列表不能为空</div>";
			} else {
				$listId = $_POST['member_list_name'];
				$memberList = explode(",", $_POST['list_members']);
				$count = 0;
				$failList = '';	
				foreach ($memberList as $member) {
					$result = $t->addListMember($listId, trim($member));
					if ($result) $count ++;
					else $failList .= $member . " ";
				}
				
				if ($count > 0) {
					if ($count == count($memberList))
						echo "<div id=\"sentTip\">成功添加 $count 个成员</div>";
					else 
						echo "<div id=\"sentTip\">成功添加 $count 个成员，失败名单：$failList </div>";
				} else {
					echo "<div id=\"sentTip\">添加成员失败，请重试</div>";
				}
			}
		}
	?>
	<?php 
		$isSelf = true;
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$isSelf = false;
		} else {
			$id = $t->username;
		}
		$type = isset($_GET['t'])? $_GET['t'] : 1;
		$c = isset($_GET['c'])? $_GET['c'] : -1;
		switch ($type) {
			case 0:
				$lists = $t->followedLists($id, $c);
				$nextlist = $lists->next_cursor;
				$prelist = $lists->previous_cursor;
				$lists = $lists->lists; 
				break;
			case 1:
				$lists = $t->createdLists($id);
				$lists = $lists->lists; 
				break;
			case 2:
				$lists = $t->beAddedLists($id, $c);
				$nextlist = $lists->next_cursor;
				$prelist = $lists->previous_cursor;
				$lists = $lists->lists; 
				break;
			default:
				$lists = false;
		}
		
		if ($lists === false) {
			header('location: error.php');
		} 
		
		
	?>
	<div id="subnav">
	<?php if ($isSelf) { ?>
		<?php if ($type == 0) {?>
	       	<span class="subnavNormal">我关注的推群</span><span class="subnavLink"><a href="lists.php?t=1">我创建的推群</a></span><span class="subnavLink"><a href="lists.php?t=2">关注我的推群</a></span>
		<?php } else if ($type == 1) {?>
	       	<span class="subnavLink"><a href="lists.php?t=0">我关注的推群</a></span><span class="subnavNormal">我创建的推群</span><span class="subnavLink"><a href="lists.php?t=2">关注我的推群</a></span>
		<?php } else {?>
			<span class="subnavLink"><a href="lists.php?t=0">我关注的推群</a></span><span class="subnavLink"><a href="lists.php?t=1">我创建的推群</a></span><span class="subnavNormal">关注我的推群</span>
		<?php } ?>
	<?php } else {?>
		<?php if ($type == 0) {?>
	       	<span class="subnavNormal">TA关注的推群</span><span class="subnavLink"><a href="lists.php?id=<?php echo $id?>&t=1">TA创建的推群</a></span><span class="subnavLink"><a href="lists.php?id=<?php echo $id?>&t=2">关注TA的推群</a></span>
		<?php } else if ($type == 1) {?>
	       	<span class="subnavLink"><a href="lists.php?t=0&id=<?php echo $id?>">TA关注的推群</a></span><span class="subnavNormal">TA创建的推群</span><span class="subnavLink"><a href="lists.php?id=<?php echo $id?>&t=2">关注TA的推群</a></span>
		<?php } else {?>
			<span class="subnavLink"><a href="lists.php?t=0&id=<?php echo $id?>">TA关注的推群</a></span><span class="subnavLink"><a href="lists.php?id=<?php echo $id?>&t=1">TA创建的推群</a></span><span class="subnavNormal">关注TA的推群</span>
		<?php } ?>
	<?php } ?>
    </div>
    
	<?php 
		
		$empty = count($lists->list) == 0? true: false;
		if ($empty) {
			echo "<div id=\"empty\">此页无推群</div>";
		} else {
			$output = '<ol class="rank_list">';			
			foreach ($lists->list as $list) {
		
				$listurl = substr($list->uri,1);
				$user = $list->user;
				$listname = explode('/',$list->full_name);
				$mode = $list->mode == 'private' ? "隐私群" : "";
				
				$output .= "
				<li>
					<span class=\"rank_img\"><a href=\"https://twitter.com/$user->screen_name\"><img src=\"$user->profile_image_url\" /></a></span>
					<div class=\"rank_content\">
						<span class=\"rank_num\"><span class=\"rank_name\"><a href=\"list.php?id=$listurl\"><em>$listname[0]/</em>$listname[1]</a></span></span>
						<span class=\"rank_count\">关注者：$list->subscriber_count 　成员数：$list->member_count 　$mode</span> 
				";
				if ($list->description != '') $output .= "<span class=\"rank_description\">简介：$list->description</span>";
				if ($type == 0) $output .= "<span class=\"list_action\"><a href=\"javascript:void()\" class=\"unfollow_list\">取消关注</a></span>";
				if ($type == 1 && $isSelf) $output .= "<span class=\"list_action\"><a href=\"javascript:void()\" class=\"edit_list\">编辑推群</a> <a href=\"javascript:void()\" class=\"delete_list\">删除推群</a> <a href=\"javascript:void()\" class=\"add_member\">添加成员</a></span>";
				$output .= "
					</div>
				</li>
				";
			}
			
			$output .= "</ol>";
			
			echo $output;
		}
		
	?>
	
	<?php if ($isSelf && $type == 1) {?>
	    <a href="javascript:void()" id="list_create_btn">创建推群</a>
	    <form method="POST" action="./lists.php?t=1" id="list_form">
	    	<input type="hidden" name="pre_list_name" value="" id="pre_list_name" />
	    	<input type="hidden" name="is_edit" value="0" id="is_edit" />
	    	<span><label for="list_name">推群名：</label><input type="text" name="list_name" id="list_name" /></span>
	    	<span><label for="list_description">描述：</label><textarea type="text" name="list_description" id="list_description"></textarea></span>
	    	<span><label for="list_protect">隐私群：</label><input type="checkbox" name="list_protect" id="list_protect"  /> <input type="submit" id="list_submit" value="保存" /></span>
	    	<span></span>
	    </form>
	    
	    
	<?php }?>
	
	<div id="pagination">
	<?php 
	    if ($type == 0 || $type == 2) {
	    	if ($isSelf) {
				if ($prelist != 0) echo "<a href=\"lists.php?t=$type&c=$prelist\">上一页</a>";
				if ($nextlist != 0) echo "<a href=\"lists.php?t=$type&c=$nextlist\">下一页</a>";
	    	} else {
				if ($prelist != 0) echo "<a href=\"lists.php?id=$id&t=$type&c=$prelist\">上一页</a>";
				if ($nextlist != 0) echo "<a href=\"lists.php?id=$id&t=$type&c=$nextlist\">下一页</a>";
	    	}
		}
	?>
	</div>
</div>

<?php 
	include ('inc/sidebar.php');
?>

<?php 
	include ('inc/footer.php');
?>
