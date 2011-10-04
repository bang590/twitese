<?php
/*
 * Abraham Williams (abraham@abrah.am) http://abrah.am
 *
 * Basic lib to work with Twitter's OAuth beta. This is untested and should not
 * be used in production code. Twitter's beta could change at anytime.
 *
 * Code based on:
 * Fire Eagle code - http://github.com/myelin/fireeagle-php-lib
 * twitterlibphp - http://github.com/jdp/twitterlibphp
 */

//require_once('config.php');
//require_once('oauth_lib.php');

/**
 * Twitter OAuth class
 */
class TwitterOAuth {
	/* Contains the last HTTP status code returned */
	public $http_code;
	/* Contains the last API call */
	public $last_api_call;
	/* Set up the API root URL */
	//public $host = "https://api.twitter.com/1/";
	public $host = API_URL;
	/* Set timeout default */
	public $timeout = 60;
	/* Set connect timeout */
	public $connecttimeout = 60; 
	/* Verify SSL Cert */
	public $ssl_verifypeer = FALSE;
	/* Respons type */
	public $type = 'json';
	/* Decode returne json data */
	public $decode_json = TRUE;
	/* Immediately retry the API call if the response was not successful. */
	//public $retry = TRUE;
	public $source = '推特中文圈';

	// user info
	public $username;
	public $screen_name;
	public $user_id;

	/**
	 * Set API URLS
	 */
	function accessTokenURL()  { return 'https://twitter.com/oauth/access_token'; }
	function authenticateURL() { return 'https://twitter.com/oauth/authenticate'; }
	function authorizeURL()    { return 'https://twitter.com/oauth/authorize'; }
	function requestTokenURL() { return 'https://twitter.com/oauth/request_token'; }

	/**
	 * Debug helpers
	 */
	function lastStatusCode() { return $this->http_status; }
	function lastAPICall() { return $this->last_api_call; }

	/**
	 * construct TwitterOAuth object
	 */
	function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) {
		$this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
		$this->consumer = new OAuthConsumer($consumer_key, $consumer_secret);
		if (!empty($oauth_token) && !empty($oauth_token_secret)) {
			$this->token = new OAuthConsumer($oauth_token, $oauth_token_secret);
			$this->screen_name = $_COOKIE['screen_name'] ? $_COOKIE['screen_name'] : $_SESSION['access_token']['screen_name'];
			$this->username = $_COOKIE['screen_name'] ? $_COOKIE['screen_name'] : $_SESSION['access_token']['screen_name'];
			$this->user_id = $_COOKIE['user_id'] ? $_COOKIE['user_id'] : $_SESSION['access_token']['user_id'];
			
			
		} else {
			$this->token = NULL;
		}
	}


	/**
	 * Get a request_token from Twitter
	 *
	 * @returns a key/value array containing oauth_token and oauth_token_secret
	 */
	function getRequestToken($oauth_callback = NULL) {
		$parameters = array();
		if (!empty($oauth_callback)) {
			$parameters['oauth_callback'] = $oauth_callback;
		} 
		$request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters);
		$token = OAuthUtil::parse_parameters($request);
		$this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}

	/**
	 * Get the authorize URL
	 *
	 * @returns a string
	 */
	function getAuthorizeURL($token, $sign_in_with_twitter = TRUE) {
		if (is_array($token)) {
			$token = $token['oauth_token'];
		}
		if (empty($sign_in_with_twitter)) {
			return $this->authorizeURL() . "?oauth_token={$token}";
		} else {
			return $this->authenticateURL() . "?oauth_token={$token}";
		}
	}

	/**
	 * Exchange the request token and secret for an access token and
	 * secret, to sign API calls.
	 *
	 * @returns array("oauth_token" => the access token,
	 *                "oauth_token_secret" => the access secret)
	 */
	function getAccessToken($oauth_verifier = FALSE) {
		$parameters = array();
		if (!empty($oauth_verifier)) {
			$parameters['oauth_verifier'] = $oauth_verifier;
		}
		$request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters);
		$token = OAuthUtil::parse_parameters($request);
		$this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}

	/**
	 * GET wrappwer for oAuthRequest.
	 */
	function get($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'GET', $parameters);
		if ($this->type == 'json' && $this->decode_json) {
			return json_decode($response);
		}elseif($this->type == 'xml' && function_exists('simplexml_load_string')){
			return simplexml_load_string($response);
		}
		return $response;
	}

	/**
	 * POST wreapper for oAuthRequest.
	 */
	function post($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'POST', $parameters);
		if ($this->type === 'json' && $this->decode_json) {
			return json_decode($response);
		}elseif($this->type == 'xml' && function_exists('simplexml_load_string')){
			return simplexml_load_string($response);
		}
		return $response;
	}

	/**
	 * DELTE wrapper for oAuthReqeust.
	 */
	function delete($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'DELETE', $parameters);
		if ($this->type === 'json' && $this->decode_json) {
			return json_decode($response);
		}elseif($this->type == 'xml' && function_exists('simplexml_load_string')){
			return simplexml_load_string($response);
		}
		return $response;
	}

	/**
	 * Format and sign an OAuth / API request
	 */
	function oAuthRequest($url, $method, $parameters) {
		if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
			$url = "{$this->host}{$url}.{$this->type}";
		}
		if($_GET['debug']) echo $url;
		$request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
		$request->sign_request($this->sha1_method, $this->consumer, $this->token);
		switch ($method) {
		case 'GET':
			return $this->http($request->to_url(), 'GET');
		default:
			return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata());
		}
	}

	/**
	 * Make an HTTP request
	 *
	 * @return API results
	 */
	function http($url, $method, $postfields = NULL) {
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);

		switch ($method) {
		case 'POST':
			curl_setopt($ci, CURLOPT_POST, TRUE);
			if (!empty($postfields)) {
				curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
			}
			break;
		case 'DELETE':
			curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
			if (!empty($postfields)) {
				$url = "{$url}?{$postfields}";
			}
		}

		curl_setopt($ci, CURLOPT_URL, $url);
		$response = curl_exec($ci);
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->last_api_call = $url;
		curl_close ($ci);
		return $response;
	}

	/* ---------- API METHODS ---------- */
	/*                                   */
	/* ---------- Block ---------- */
	function blockingIDs(){
		$url = '/blocks/blocking/ids';
		return $this->get($url);
	}

	function blockingList($page){
		$url = '/blocks/blocking';
		$args = array();
		if($page){
			$args['page'] = $page;
		}
		return $this->get($url, $args);
	}

	function blockUser($id){
		$url = $this->host.'/blocks/create';
		$url .= "/$id.$this->type";
		return $this->post($url);
	}

	function isBlocked($id){
		$url = $this->host.'/blocks/exists';
		$url .= "/$id.$this->type";
		return $this->get($url);
	}
	
	function isBlock($id = false){
		$url = $this->host.'/blocks/exists';
		$url .= "/$id.$this->type";
		return $this->get($url);
	}

	function unblockUser($id){
		$url = $this->host.'/blocks/destroy';
		$url .= "/$id.$this->type";
		return $this->delete($url);
	}

	/* ---------- Messages ---------- */
	function deleteDirectMessage($id){
		$url = $this->host.'/direct_messages/destroy';
		$url .= "/$id.$this->type";
		return $this->delete($url);
	}
	

	function directMessages($page = false, $since_id = false, $count = null){
		$url = '/direct_messages';
		$args = array();
		if( $since_id )
			$args['since_id'] = $since_id;
		if( $page )
			$args['page'] = $page;
		if( $count )
			$args['count'] = $count;
		return $this->get($url, $args);
	}
	

	function sendDirectMessage($user, $text){
		$url = '/direct_messages/new';
		$args = array();
		$args['user'] = $user;
		if($text)
			$args['text'] = $text;
		return $this->post($url, $args);
	}
	

	function sentDirectMessage($page = false, $since = false, $since_id = false){
		$url = '/direct_messages/sent';
		$args = array();
		if($since)
			$args['since'] = $since;
		if($since_id)
			$args['since_id'] = $since_id;
		if($page)
			$args['page'] = $page;
		return $this->get($url, $args);
	}


	/* ---------- List ---------- */
	function addListMember($listid, $memberid){
		$url = "/$this->username/$listid/members";
		$args = array();
		if($memberid){
			$args['id'] = $memberid;
		}
		return $this->post($url, $args);
	}

	function beAddedLists($username = '', $cursor = false){
		$url = $this->host."/$username/lists/memberships.$this->type";
		$args = array();
		if($cursor){
			$args['cursor'] = $cursor;
		}
		return $this->get($url, $args);
	}

	function createList($name, $description, $isPortect){
		$url = "/$this->username/lists";
		$args = array();
		if($name){
			$args['name'] = $name;
		}
		if($description){
			$args['description'] = $description;
		}
		$args['mode'] = $isProtect ? "private" : "public";
		return $this->post($url, $args);
	}
	

	function createdLists($username = '', $cursor = false){
		$url = $this->host."/$username/lists.$this->type";
		$args = array();
		if($cursor){
			$args['cursor'] = $cursor;
		}
		return $this->get($url, $args);
	}

	function deleteList($id){
		$arr = explode('/', $id);
		$url = "/$arr[0]/lists/$arr[1]";
		return $this->delete($url);
	}

	function deleteListMember($id, $memberid){
		$arr = explode("/", $id);
		$url = "/$arr[0]/$arr[1]/members";
		$args = array();
		if($memberid){
			$args['id'] = $memberid;
		}
		return $this->delete($url, $args);
	}

	function editList($prename, $name, $description, $isProtect){
		$url = "$this->username/lists/$prename";
		$args = array();
		if($name){
			$args['name'] = $name;
		}
		if($description){
			$args['description'] = $description;
		}
		$args['mode'] = $isProtect ? "private" : "public";
		return $this->post($url, $args);
	}
	

	function followedLists($username = '', $cursor = false){
		$url = $this->host."/$username/lists/subscriptions.$this->type";
		$args = array();
		if($cursor){
			$args['cursor'] = $cursor;
		}
		return $this->get($url, $args);
	}

	function followList($id){
		$arr = explode("/", $id);
		$url = $this->host."/$arr[0]/$arr[1]/subscribers.$this->type";
		return $this->get($url, $args);
	}

	function isFollowedList($id){
		$arr = explode('/', $id);
		$url = $this->host."/$arr[0]/$arr[1]/subscribers/$this->username.$this->type";
		return $this->get($url);
	}

	function listFollowers($id, $cursor = false){
		$arr = explode('/', $id);
		$url = $this->host."/$arr[0]/$arr[1]/subscribers.$this->type";
		$args = array();
		if($cursor){
			$args['cursor'] = $cursor;
		}
		return $this->get($url, $args);
	}

	function listInfo($id){
		$arr = explode('/', $id);
		$url = $this->host."/$arr[0]/lists/$arr[1].$this->type";
		return $this->get($url);
	}

	function listMembers($id, $cursor = false){
		$arr = explode("/", $id);
		$url = $this->host."/$arr[0]/$arr[1]/members.$this->type";
		$args = array();
		if($cursor){
			$args['cursor'] = $cursor;
		}
		return $this->get($url, $args);

	}

	function listStatus($id, $page = false, $since_id = false){
		$arr = explode('/', $id);
		$url = $this->host."/$arr[0]/lists/$arr[1]/statuses.$this->type";
		$args = array();
		if($page){
			$args['page'] = $page;
		}
		if($since_id){
			$args['since_id'] = $since_id;
		}
		return $this->get($url, $args);
	}

	function unfollowList($id){
		$arr = explode("/", $id);
		$url = $this->host."/$arr[0]/$arr[1]/subscribers.$this->type";
		return $this->delete($url);
	}

	/* ---------- Friendship ---------- */
	function destroyUser($id){
		$url = $this->host.'/friendships/destroy';
		$url .= "/$id.$this->type";
		return $this->delete($url);
	}

	function followers($id = false, $cursor = false, $count = 30, $type = 'xml'){
		$this->type = $type;
		$url = $this->host.'/statuses/followers';
		$url .= $id ? "/$id.$this->type" : ".$this->type";
		if( $id )
			$args['id'] = $id;
		if( $count )
			$args['count'] = (int) $count;
		if( $cursor )
	        $args['cursor'] =  $cursor;
		return $this->get($url, $args);
	}
	

	function followUser($id, $notifications = false){
		$url = $this->host.'/friendships/create';
		$url .= "/$id.$this->type";
		$args = array();
		if($notifications)
			$args['follow'] = true;
		return $this->post($url, $args);
	}
	

	function friends($id = false, $cursor = false, $count = 30, $type = 'xml'){
		$this->type = $type;
		$url = $this->host.'/statuses/friends';
		$url .= $id ? "/$id.$this->type" : ".$this->type";
		$args = array();
		if( $id )
			$args['id'] = $id;
		if( $count )
			$args['count'] = (int) $count;
		if( $cursor )
	        $args['cursor'] =  $cursor;
		$this->type = 'xml';
		return $this->get($url, $args);
	}
	
	
	function isFriend($user_a, $user_b){
		$url = '/friendships/exists';
		$args = array();
		$args['user_a'] = $user_a;
		$args['user_b'] = $user_b;
		return $this->get($url, $args);
	}
	

	function showUser($id = false, $email = false, $user_id = false, $screen_name = false){
		$url = '/users/show';
		$args = array();
		if($id)
			$args['id'] = $id;
		elseif($screen_name)
			$args['id'] = $screen_name;
		else
			$args['id'] = $this->user_id;

		return $this->get($url, $args);
	}
	

	/* ---------- Ratelimit ---------- */
	function ratelimit(){
		$url = '/account/rate_limit_status';
		return $this->get($url);
	}

	function ratelimit_status(){
		return $this->ratelimit();
	}

	/* ---------- Retweet ---------- */
	function addRT( $id ){	        
	    $url = "http://api.twitter.com/1/statuses/retweet/$id.$this->type";
		return $this->post($url);
	}
	
	function deleteRT( $id )
	{	        		
		$url = "http://api.twitter.com/1/statuses/retweet/$id.$this->type";
		return $this->post($url);
	}
	
	function retweet($id){
		$url = "http://api.twitter.com/1/statuses/retweet/$id.$this->type";
		return $this->post($url);
	}

	function retweets($id, $count = 20){
		if($count > 100){
			$count = 100;
		}
		$url = "http://api.twitter.com/1/statuses/retweets/id.$this->type?count=$count";
		return $this->get($url);
	}

	// Returns the 20 most recent retweets posted by the authenticating user.
	function retweeted_by_me($page = false, $count = 20, $since_id = false, $max_id = false){
		$url = "http://api.twitter.com/1/statuses/retweeted_by_me.$this->type";
		$args = array();
		if($since_id){
			$args['since_id'] = $since_id;
		}
		if($max_id){
			$args['max_id'] = $max_id;
		}
		if($count){
			$args['count'] = $count;
		}
		if($page){
			$args['page'] = $page;
		}
		return $this->get($url, $args);
	}
	
	function rtByme($page = false, $count = 20, $since_id = false, $max_id = false){
		$url = "http://api.twitter.com/1/statuses/retweeted_by_me.$this->type";
		$args = array();
		if($since_id){
			$args['since_id'] = $since_id;
		}
		if($max_id){
			$args['max_id'] = $max_id;
		}
		if($count){
			$args['count'] = $count;
		}
		if($page){
			$args['page'] = $page;
		}
		return $this->get($url, $args);
	}

	// Returns the 20 most recent retweets posted by the authenticating user's friends.
	function retweeted_to_me($page = false, $count = false, $since_id = false, $max_id = false){
		$url = "http://api.twitter.com/1/statuses/retweeted_to_me.$this->type";
		$args = array();
		if($since_id){
			$args['since_id'] = $since_id;
		}
		if($max_id){
			$args['max_id'] = $max_id;
		}
		if($count){
			$args['count'] = $count;
		}
		if($page){
			$args['page'] = $page;
		}
		return $this->get($url, $args);
	}
	
	function rtTome($page = false, $count = false, $since_id = false, $max_id = false){
		$url = "http://api.twitter.com/1/statuses/retweeted_to_me.$this->type";
		$args = array();
		if($since_id){
			$args['since_id'] = $since_id;
		}
		if($max_id){
			$args['max_id'] = $max_id;
		}
		if($count){
			$args['count'] = $count;
		}
		if($page){
			$args['page'] = $page;
		}
		return $this->get($url, $args);
	}

	function retweets_of_me($page = false, $count = false, $since_id = false, $max_id = false){
		$url = "http://api.twitter.com/1/statuses/retweets_of_me.$this->type";
		$args = array();
		if($since_id){
			$args['since_id'] = $since_id;
		}
		if($max_id){
			$args['max_id'] = $max_id;
		}
		if($count){
			$args['count'] = $count;
		}
		if($page){
			$args['page'] = $page;
		}
		return $this->get($url, $args);
	}
	
	function rtOfme($page = false, $count = false, $since_id = false, $max_id = false){
		$url = "http://api.twitter.com/1/statuses/retweets_of_me.$this->type";
		$args = array();
		if($since_id){
			$args['since_id'] = $since_id;
		}
		if($max_id){
			$args['max_id'] = $max_id;
		}
		if($count){
			$args['count'] = $count;
		}
		if($page){
			$args['page'] = $page;
		}
		return $this->get($url, $args);
	}

	/* ---------- Search ---------- */
	function search($q = false, $page = false, $lang = false){
		if(!$q)
			return false;
		$args = array();
		if($page)
			$args['page'] = $page;
		$args['q'] = $q;
		
	    $searchApiUrl = strpos($this->host, "twitter.com") > 0 ? "http://search.twitter.com" : $this->host;
		$url = $searchApiUrl . '/search.' . $this->type;
		return $this->get($url, $args);
	}

	/* ---------- Timeline ---------- */
	function deleteStatus($id){
		$url = $this->host.'/statuses/destroy';
		$url .= "/$id.$this->type";
		return $this->delete($url);
	}
	

	function friendsTimeline($page = false, $since_id = false, $count = false){
		$url = '/statuses/friends_timeline';
		$args = array();
		if($page)
			$args['page'] = $page;
		if($since_id)
			$args['since_id'] = $since_id;
		if($count)
			$args['count'] = $count;
		return $this->get($url, $args);
	}

	
	function getFavorites($page = false,$userid=false){
		if($userid == false){
			$url = '/favorites';
		}
		else{
			$url = '/favorites/'.$userid;
		}
		
		$args = array();
		if($page)
			$args['page'] = $page;
		return $this->get($url, $args);
	}
	

	function makeFavorite($id){
		$url = $this->host.'/favorites/create/';
		$url .= "$id.$this->type";
		return $this->post($url);
	}
	

	function publicTimeline($sinceid = false){
		$url = '/statuses/public_timeline';
		$args = array();
		if($sinceid){
			$args['since_id'] = $sinceid;
		}
		return $this->get($url, $args);
	}
	

	function removeFavorite($id){
		$url = $this->host.'/favorites/destroy/';
		$url .= "$id.$this->type";
		return $this->post($url);
	}
	

	function replies($page = false, $since_id = false, $count = false, $max_id = false){
		
		$url = '/statuses/mentions';
		$args = array();
		if($page)
			$args['page'] = (int) $page;
		if($since_id)
			$args['since_id'] = $since_id;
		if($count)
			$args['count'] = $count;
		if($max_id)
			$args['max_id'] = $max_id;
		return $this->get($url, $args);
	}


	function showStatus( $id ){	        
        $url = $this->host.'/statuses/show';
		$url .= "/$id.$this->type";
		return $this->get($url);
    }

	function update($status, $replying_to = false , $lat = false , $long = false){
		try{
			$url = '/statuses/update';
			$args = array();
			$args['status'] = stripslashes($status);
			if($replying_to)
				$args['in_reply_to_status_id'] = $replying_to;
			if($lat)
				$args['lat'] = $lat;
			if($long)
				$args['long'] = $long;
			return $this->post($url, $args);
		}catch(Exception $ex){
			echo $ex->getLine." : ".$ex->getMessage();
		}
	}
	

	function userTimeline($page = false, $id = false, $count = false, $since_id = false, $max_id = false){
		$url = '/statuses/user_timeline';
		$args = array();
		if($page)
			$args['page'] = $page;
		if($id)
			$args['id'] = $id;
		if($count)
			$args['count'] = $count;
		if($since_id)
			$args['since_id'] = $since_id;
		if($max_id)
			$args['max_id'] = $max_id;
		return $this->get($url, $args);
	}

	
	function homeTimeline( $page = false, $since_id = false,  $count = false, $max_id = false ){		
		$url = '/statuses/home_timeline';
		$args = array();
		if($page)
			$args['page'] = $page;
		if($since_id)
			$args['since_id'] = $since_id;
		if($count)
			$args['count'] = $count;
		if($max_id)
			$args['max_id'] = $max_id;
		return $this->get($url, $args);
		
	}
	
	
	/* ---------- Misc. ---------- */
	function twitterAvailable(){
		$url = "/help/test";
		if($this->get($url) == 'ok'){
			return true;
		}
		return false;
	}

	function updateProfile($fields = array()){
		$url = '/account/update_profile';
		$args = array();
		foreach( $fields as $pk => $pv ){
			switch( $pk ){
			case 'name' :
				$args[$pk] = (string) substr( $pv, 0, 20 );
				break;
			case 'email' :
				if( preg_match( '/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $pv ) )
					$args[$pk] = (string) $pv;
				break;
			case 'url' :
				$args[$pk] = (string) substr( $pv, 0, 100 );
				break;
			case 'location' :
				$args[$pk] = (string) substr( $pv, 0, 30 );
				break;
			case 'description' :
				$args[$pk] = (string) substr( $pv, 0, 160 );
				break;
			default :
				break;
			}
		}
		return $this->post($url, $args);
	}

	function veverify(){
		$url = $this->host.'/account/verify_credentials.'.$this->type;
		return $this->get($url);
	}
	
	function verify(){
		$url = $this->host.'/account/verify_credentials.'.$this->type;
		return $this->get($url);
	}

	/* ---------- image upload ---------- */
	function imglyUpload($image){
		$imgly = new image_uploader();
		return is_null($imgly->imgly($image));
	}

	/* ---------- twitese method ---------- */
	function rank($page = false, $count = false){
		$url = TWITESE_API_URL."/rank.$this->type";
		$args = array();
		if($page){
			$args['page'] = $page;
		}
		if($count){
			$args['count'] = $count;
		}
		return $this->get($url, $args);
	}


	function browse($page = false, $count = false){
		$url = TWITESE_API_URL."/browse.$this->type";
		$args = array();
		if($page){
			$args['page'] = $page;
		}
		if($count){
			$args['count'] = $count;
		}
		return $this->get($url, $args);
	}
	
	/* New */
	function updateAvatar( $file ){
	    return false;
	}
	
	function trends() {		
		$url = "/trends";
		return $this->get($url);
	}
}

