<?php 
	include ('../lib/twitese.php');
	$t = getTwitter();
	echo "<ul id=\"showTrends\">";
	$trends = $t->trends();
	if (isset($trends->trends)) {
		foreach ($trends->trends as $trend) {
			$formated = urlencode($trend->name);
			echo "<li><a href=\"search.php?q=$formated\">$trend->name</a></li>";
		}
	} else {
		echo "<li class=\"red\">请求出错，请重试</li>";
	}
	echo "</ul>";
	
?>

