<?php
	include ('config.php');
	include ('utility.php');
	include ('twitteroauth.php');
	include ('oauth_lib.php');
	require_once('Autolink.php');
	
	if (!isset($_SESSION)) {
		session_start();
	}
	
	function verify($name, $password, $remember) {
		$t = new twitter($name, $password);
		$user = $t->verify();
		print_r($user);
		if (!isset($user->error) && isset($user->name)) {
			$time = $remember ? time()+3600*24*365 : 0; 
			setEncryptCookie('twitese_name', $user->screen_name, $time, '/');
			setEncryptCookie('twitese_pw', $password, $time, '/');
			setcookie('friends_count', $user->friends_count, $time, '/');
			setcookie('statuses_count', $user->statuses_count, $time, '/');
			setcookie('followers_count', $user->followers_count, $time, '/');
			setcookie('imgurl', $user->profile_image_url, $time, '/');
			setcookie('name', $user->name, $time, '/');
			return true;
		} else {
			return $user;
		}
	}
	
	function saveStyle($headerBg, $bodyBg, $sideBg, $sideNavBg, $linkColor, $linkHColor, $wordColor, $border, $line) {
		$time = time() + 3600*24*30;
		setcookie("headerBg", $headerBg, $time);
		setcookie("bodyBg", $bodyBg, $time);
		setcookie("sideBg", $sideBg, $time);
		setcookie("sideNavBg", $sideNavBg, $time);
		setcookie("linkColor", $linkColor, $time);
		setcookie("linkHColor", $linkHColor, $time);
		setcookie("wordColor", $wordColor, $time);
		setcookie("border", $border, $time);
		setcookie("line", $line, $time);
	}

	function resetStyle() {
		delCookie("headerBg");
		delCookie("bodyBg");
		delCookie("sideBg");
		delCookie("sideNavBg");
		delCookie("linkColor");
		delCookie("linkHColor");
		delCookie("wordColor");
		delCookie("border");
		delCookie("line");
	}
	
	function getColor($name, $default) {
		if (getCookie($name)) return getCookie($name);
		else return $default;
	}
		
	function setUpdateCookie($value) {
		setcookie('update_status', $value);
	}
	
	function getUpdateCookie() {
		if ( isset($_COOKIE['update_status']) ) {
			$update_status = $_COOKIE['update_status'];
			setcookie('update_status', '', time()-300);
			return $update_status;
		} else {
			return null;
		}
	}
	
	function formatText($text) {
		$autolinker = new Twitter_Autolink();
		$text = $autolinker->autolink($text);
		return $text;
	}
	
	function formatDate($date, $is_raw = false) {
		date_default_timezone_set('Asia/Chongqing'); 
		$differ = time() - strtotime($date);

		if ($is_raw) {
			$dateFormated = date('Y-m-d H:i:s', strtotime($date)); 
		} else {
			if ($differ < 0) $differ = 0;
			if ($differ < 60) {
				$dateFormated = ceil($differ) . "秒前";
			} else if ($differ < 3600) {
				$dateFormated = ceil($differ/60) . "分钟前";
			} else if ($differ < 3600*24) {
				$dateFormated = "约" . ceil($differ/3600) . "小时前";
			} else {
				$dateFormated = date('Y-m-d H:i:s', strtotime($date)); 
			}
		}
		
		return $dateFormated;
	}
	function unshortUrl($text) {
		$urlRegs = array();
		$urlRegs[] ='/http:\/\/bit\.ly\/([a-z0-9]{5}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/j\.mp\/([a-z0-9]{5}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/tinyurl\.com\/([a-z0-9]{5}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/retwt\.me\/([a-z0-9]{5}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/is\.gd\/([a-z0-9]{5}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/t\.co\/([a-z0-9]{5}[a-z0-9]*)/i';
		
		//根据需要开启
//		$urlRegs[] ='/http:\/\/moby\.to\/([a-z0-9]{5}[a-z0-9]*)/i';
//		$urlRegs[] ='/http:\/\/tr\.im\/([a-z0-9]{4}[a-z0-9]*)/i';
//		$urlRegs[] ='/http:\/\/snurl\.com\/([a-z0-9]{5}[a-z0-9]*)/i';
//		$urlRegs[] ='/http:\/\/short\.ie\/([a-z0-9]{6}[a-z0-9]*)/i';
//		$urlRegs[] ='/http:\/\/kl\.am\/([a-z0-9]{4}[a-z0-9]*)/i';
//		$urlRegs[] ='/http:\/\/idek\.net\/([a-z0-9]{3}[a-z0-9]*)/i';
//		$urlRegs[] ='/http:\/\/cli\.gs\/([a-z0-9]{6}[a-z0-9]*)/i';
//		$urlRegs[] ='/http:\/\/u\.nu\/([a-z0-9]{5}[a-z0-9]*)/i';
//		$urlRegs[] ='/http:\/\/digg\.com\/([a-z0-9]{6}[a-z0-9]*)/i';
		
		$objs = false;
		
		if(preg_match_all('/http:\/\/[a-z0-9\/\.]+[^<]/i',$text,$urls,PREG_PATTERN_ORDER)){
			foreach($urls[0] as $url) {
				foreach($urlRegs as $urlReg) {
					if(preg_match_all($urlReg,$url,$matchs,PREG_PATTERN_ORDER)){
						foreach($matchs[0] as $match){
							$request = 'http://api.unshort.me/?r=' . $match;
							$obj = objectifyXml(processCurl( $request ));
							if (isset($obj->resolvedURL) && trim($obj->resolvedURL) != '')
							$objs .= "<span>URL:<a rel=\"nofollow\" href=\"$obj->resolvedURL\" target=\"_blank\">$obj->resolvedURL</a></span>";
						}
					}
				}
			}
		}
		return $objs;
	}
	
	function shortUrl($url, $type = "is.gd") {
		switch ($type) {
			case 'is.gd':
				$request = 'http://is.gd/api.php?longurl=' . rawurlencode($url);
				$result = processCurl( $request );
				if ($result) return $result;
				else return false;
				break;
			case 'aa.cx':
				$request = 'http://aa.cx/api.php?url=' . rawurlencode($url);
				$result = processCurl( $request );
				if ($result) return $result;
				else return false;
				break;
			case 's8.hk':
				$request = "http://s8.hk/api/shorten?longUrl=" . rawurlencode($url);
				$result = processCurl( $request );
				if ($result) return $result;
				else return false;
				break;
				
			default:
				return false;
		}
	}
	
	function processCurl($url,$postargs=false)
	{
	    $ch = curl_init($url);
	
		if($postargs !== false)	{
			curl_setopt ($ch, CURLOPT_POST, true);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $postargs);
        }
		
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
   		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);
        $responseInfo=curl_getinfo($ch);
        
        curl_close($ch);
        if( intval( $responseInfo['http_code'] ) == 200 )
			return $response;    
        else
            return false;
	}
	
	function objectifyXml( $data )
	{
		if( function_exists('simplexml_load_string') ) {
			$obj = @simplexml_load_string( $data );
		}
		if (isset($obj->error) || !$obj) return false;
		else return $obj;

		return false;
	}
	
	function twitgooUpload( $image ) {
		$postdata = array( 'media' => "@$image", 'username' => getEncryptCookie('twitese_name'), 'password' => getEncryptCookie('twitese_pw'));
	    $request = 'http://twitgoo.com/api/upload';
	    $result = objectifyXml( processCurl( $request, $postdata ) );
		if (isset($result->mediaurl)) {
			return $result->mediaurl;
		} else {
			return false;
		}
	}
	
	function imglyUpload( $image ) {
		$postdata = array( 'media' => "@$image", 'username' => getEncryptCookie('twitese_name'), 'password' => getEncryptCookie('twitese_pw'));
	    $request = 'http://img.ly/api/upload';
	    $result = objectifyXml( processCurl( $request, $postdata ) );
		if (isset($result->mediaurl)) {
			return $result->mediaurl;
		} else {
			return false;
		}
	}
	
	function getTwitter() {
			$access_token = $_SESSION['access_token'] ? $_SESSION['access_token'] : null;
			$oauth_token = $access_token ? $access_token['oauth_token'] : $_COOKIE['oauth_token'];
			$oauth_token_secret = $access_token ? $access_token['oauth_token_secret'] : $_COOKIE['oauth_token_secret'];
			$oauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
			return $oauth;
	}
	
	function isLogin(){
		return isOAuth();
	}
	
	function isBasicAuth() { #Deprecated, always retrun false
		return getEncryptCookie('twitese_name') && getEncryptCookie('twitese_pw');
	}
	
	function isOAuth() {
		if(isset($_SESSION['login_status']) && $_SESSION['login_status'] == 'verified'){
			return true;
		}
		else{
			return getCookie('user_id') && getCookie('screen_name') && getCookie('oauth_token') && getCookie('oauth_token_secret');
		}
	}
	
	/***Template***/
	function tpTimeline($statuses, $argsArr=false) {
		$output = '';
		if (isset($argsArr['id'])) {
			$output .= '<div id="' . $argsArr['id'] . '">';
		}
		
		$output .= '<ol class="timeline">';
		
		foreach ($statuses as $status) {
			$is_rt = isset($status->retweeted_status) && isset($_COOKIE['rt_style']) && $_COOKIE['rt_style'] == 1;
			
			$username = getEncryptCookie('twitese_name');
			if ($is_rt) {
				
				if (isset($argsArr['show_rtid'])) $status_id = $status->id_str;
				else $status_id = $status->retweeted_status->id_str;
				
				$rtsign = 'rt_sign';
				$user = $status->retweeted_status->user;
				$rtuser = $status->user;
				$date = formatDate($status->created_at);
				$rawdate = formatDate($status->created_at, true);
				$text = formatText($status->retweeted_status->text);
				$rawtext = $status->retweeted_status->text;
			} else {
				$user = $status->user;
				$date = formatDate($status->created_at);
				$rawdate = formatDate($status->created_at, true);
				$text = formatText($status->text);
				$rawtext = $status->text;
				$status_id = $status->id_str;
				$rtsign = '';
			}
			
			if(!isset($argsArr['is_mention']) && (strpos($rawtext, "@$username") > -1)) {
				$output .= "<li class=\"mention\">";
			} else {
				$output .= "<li>";
			}
			
			$output .= "
					<span class=\"status_author\">
						<a href=\"user.php?id=$user->screen_name\" target=\"_blank\"><img src=\"$user->profile_image_url\" title=\"$user->screen_name\" /></a>
					</span>
					<span class=\"status_body\">
						<span class=\"status_id\">$status_id</span>
						<span class=\"status_word\"><a class=\"user_name $rtsign\" href=\"user.php?id=$user->screen_name\" target=\"_blank\">$user->screen_name</a> $text </span>
						"; 
			if ($shorturl = unshortUrl($status->text)) $output .= "<span class=\"unshorturl\">$shorturl</span>";
			
			$output .= "<span class=\"status_info\">";
			if (!isset($argsArr['hide_replie'])) $output .= "<a class=\"replie_btn\" href=\"a_reply.php?id=$status->id_str\">回复</a>";
			if (!isset($argsArr['hide_rt'])) $output .= "<a class=\"rt_btn\" href=\"a_rt.php?id=$status->id_str\">回推</a>";
			if (!isset($argsArr['hide_ort']) && $user->screen_name != $username) $output .= "<a class=\"ort_btn\" href=\"a_ort.php?id=$status->id_str\">官方RT</a>";
			if (!isset($argsArr['hide_favor'])) $output .= "<a class=\"favor_btn\" href=\"a_favor.php?id=$status->id_str\">收藏</a>";
			if ($user->screen_name == $username || isset($argsArr['show_del'])) $output .= "<a class=\"delete_btn\" href=\"a_del.php?id=$status->id_str&t=s\">删除</a>";
			
			if ($status->in_reply_to_status_id_str) $output .= "<span class=\"in_reply_to\"> <a href=\"status.php?id=$status->in_reply_to_status_id_str \">对 $status->in_reply_to_screen_name 的回复</a></span>";
			if ($is_rt) $output .= "RT by <a href=\"user.php?id=$rtuser->screen_name\">$rtuser->screen_name</a>";
			else if (isset($status->retweeted_status)) $output .= "RT from <a href=\"user.php?id=" . $status->retweeted_status->user->screen_name . "\">" . $status->retweeted_status->user->screen_name . "</a>";
			$output .= "		
			 				<span class=\"source\">通过 $status->source</span>
							<span class=\"date\" title=\"$rawdate\"><a href=\"status.php?id=$status->id_str\" target=\"_blank\">$date</a></span>
					    </span>
					</span>
				</li>
			";
		}
		$output .= "</ol>";
		
		if (isset($argsArr['id'])) {
			if ($argsArr['id'] == 'allTimeline') {
				$output .=  '<input type="button" value="更多" id="more_home" class="more_btn" />';
			} else if ($argsArr['id'] == 'allReplies') {
				$output .=  '<input type="button" value="更多" id="more_replie" class="more_btn" />';
			}
			$output .= '</div>';
		}
		echo $output;
		
	}
	
	function tpEmpty(){
		echo "<div class=\"empty\">此页无消息</div>";
	}
	
	function testResult($result) {
		$url = urlencode('http://' . $_SERVER ['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		if ((!is_array($result) && !is_object($result))|| !$result || isset($result->errors)) {
			//header("location: error.php?url=$url");
		}
		if ($result == 'noconnect') {
			header("location: error.php?type=1&url=$url");
		} 
		if ($result == 'limit') {
			header("location: error.php?type=2&url=$url");
		} 
	}
	
	function testReferer() {
		$scheme = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") ? 'http' : 'https';
		$port = $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443 ? ':'.$_SERVER['SERVER_PORT'] : '';
		$HOST = $scheme . '://' . $_SERVER['HTTP_HOST'] . $port;
		return strpos($_SERVER['HTTP_REFERER'],$HOST) === 0 ? true : false;
	}
?>
