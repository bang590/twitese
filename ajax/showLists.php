<?php 
	include ('../lib/twitese.php');
	$t = getTwitter();
	echo "<ul id=\"showList\">";
	$lists = $t->createdLists($t->username);
	$flag = 0;
	$emptyFlag = 0;
	if (isset($lists->lists)) {
		$lists = $lists->lists; 
		if (count($lists) == 0) $emptyFlag ++;
		foreach ($lists as $list) {
			$listurl = substr($list->uri,1);
			echo "<li><a href=\"list.php?id=$listurl\">$list->full_name</a></li>";
		}
	} else {
		$flag ++;
	}
	
	$lists = $t->followedLists($t->username);
	if (isset($lists->lists)) {
		$lists = $lists->lists; 
		if (count($lists) == 0) $emptyFlag ++;
		foreach ($lists as $list) {
			$listurl = substr($list->uri,1);
			echo "<li><a href=\"list.php?id=$listurl\">$list->full_name</a></li>";
		}
	} else {
		$flag ++;
	}
	
	if ($flag >= 1) echo "<li class=\"red\">请求出错，请重试</li>";
	if ($emptyFlag >= 2) echo "<li class=\"red\">推群为空</li>";
	
	echo "</ul>";
	
?>

