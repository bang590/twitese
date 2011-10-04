<?php 
	include ('../lib/twitese.php');
	$t = getTwitter();
	if (!testReferer()) exit();
	
	if ( isset($_POST['action']) && isset($_POST['id']) ) {
		switch ($_POST['action']) {
			case 'check':
				$result = $t->isBlock($_POST['id']);
				if ($result == "notblock") echo "no";
				else if (is_object($result)) echo "yes";
				break;
				
			case 'create':
				$result = $t->blockUser($_POST['id']);
				if ($result) echo 'success';
				else echo 'error';
				break;
				
			case 'destory':
				$result = $t->unblockUser($_POST['id']);
				if ($result) echo 'success';
				else echo 'error';
				break;
			
		}
	}
	
?>

