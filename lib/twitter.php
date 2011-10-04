<?php

class twitter{
	
	var $username='';
	var $password='';
	var $user_agent='twitese';
	var $type='json';
	var $isOAuth = false;
	var $debug = false;
    
	function twitter($username = '', $password = '', $type = 'json', $isOAuth = false)
	{
		if ($username != '' && $password != '') {
			$this->username = $username;
			$this->password = $password;
			$this->type = $type;
			$this->isOAuth = $isOAuth;
		}
	}
	
	
	function verify()
	{
		$request = $this->getAPI() . '/account/verify_credentials.' . $this->type;
		return $this->objectify( $this->process($request) );
	}
	
	/**** Status Methods ****/
	
	function showStatus( $id )
	{	        
        $request = $this->getAPI() . '/statuses/show/'.$id . '.' . $this->type;
		return $this->objectify( $this->process($request) );
    }
    
	function update( $status, $replying_to = false )
	{	
	    $args = array();
	    if( $status )
	        $args['status'] = stripslashes($status);
	    if( $replying_to )
	        $args['in_reply_to_status_id'] = $replying_to;
	    $args['source'] = 'twitese';
	    
	    $qs = $this->_glue($args);
        $request = $this->getAPI() . '/statuses/update.' . $this->type ;
		return $this->objectify( $this->process($request, $args) );
	}
	
	function deleteStatus( $id ) {
        $request = $this->getAPI() . '/statuses/destroy/' . $id . '.' . $this->type;
        return $this->objectify( $this->process( $request, true ) );
    }
    
    
    /**** Timeline Methods ****/

	function publicTimeline( $sinceid = false )
	{
        $qs='';
        if( $sinceid !== false )
            $qs = '?since_id=' . intval($sinceid);
        $request = $this->getAPI() . '/statuses/public_timeline.' . $this->type . $qs;

		return $this->objectify( $this->process($request) );
	}
	   
	function userTimeline($page=false, $id=false, $count=false, $since_id=false)
	{
	
	    $args = array();
	    if( $id )
	        $args['screen_name'] = $id;
	    if( $count )
	        $args['count'] = (int) $count;
	    if( $since_id )
	        $args['since_id'] = $since_id;
	    if( $page )
	        $args['page'] = (int) $page;
	    
	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
			            
        
        $request = $this->getAPI() . '/statuses/user_timeline.'. $this->type . $qs;
        
		return $this->objectify( $this->process($request) );

	}

	function homeTimeline( $page = false, $since_id = false,  $count = false )
	{
	    $args = array();
	    if( $count )
	        $args['count'] = $count;
	    if( $since_id )
	        $args['since_id'] = $since_id;
	    if( $page )
	        $args['page'] = (int) $page;
	    
	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
			            
        $request = $this->getAPI() . '/statuses/home_timeline.' . $this->type . $qs;
		return $this->objectify( $this->process($request) );
		
	}
	function friendsTimeline( $page = false, $since_id = false,  $count = false )
	{
	    $args = array();
	    if( $count )
	        $args['count'] = $count;
	    if( $since_id )
	        $args['since_id'] = $since_id;
	    if( $page )
	        $args['page'] = (int) $page;
	    
	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
			            
        $request = $this->getAPI() . '/statuses/friends_timeline.' . $this->type . $qs;
		return $this->objectify( $this->process($request) );
		
	}
	
	function replies( $page = false, $since_id = false )
	{
	    $args = array();
	    if( $page )
	        $args['page'] = (int) $page;
	    if( $since_id )
	        $args['since_id'] = $since_id;
	    
	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
	    
	    $request = $this->getAPI() . '/statuses/mentions.' . $this->type . $qs;    
	    
		return $this->objectify( $this->process($request) );
		
	}
	
	
	/**** Direct Message Methods ****/
	
	function directMessages( $page = false, $since_id = false, $count = null )
	{
	    $args = array();
	    if( $page )
	        $args['page'] = (int) $page;
	    if( $since_id )
	        $args['since_id'] = $since_id;
	    
	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
			
        $request = $this->getAPI() . '/direct_messages.' . $this->type . $qs;
		return $this->objectify( $this->process($request) );
	}

	function sentDirectMessage( $page = false, $since = false, $since_id = false )
	{
	    $args = array();
	    if( $page )
	        $args['page'] = (int) $page;
	    if( $since_id )
	        $args['since_id'] = $since_id;
	    
	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
            
        $request = $this->getAPI() . '/direct_messages/sent.' . $this->type  . $qs;
        return $this->objectify( $this->process($request) );
	}
    
	function sendDirectMessage( $user, $text )
	{
	    $args = array();
	    if( $user )
	        $args['user'] = $user;
	    if( $text )
	        $args['text'] = $text;
		
        $request = $this->getAPI() . '/direct_messages/new.' . $this->type;
		return $this->objectify( $this->process($request, $args) );
	}
	
	function deleteDirectMessage( $id )
	{
	    $request = $this->getAPI() . '/direct_messages/destroy/' . $id . '.' . $this->type;
	    return $this->objectify( $this->process( $request, true ) );
	}
	
	
	/**** User Methods ****/

	function showUser( $id = false )
	{	   	
	    $args = array();
		
	    if (!$id) 
	    	$id = $this->username;
			
	    $qs = '?screen_name=' . $id;
	    
        $request = $this->getAPI() . '/users/show/' . $id . '.' . $this->type . $qs;
		return $this->objectify( $this->process($request) );
	}
	
	function friends( $id = false, $cursor = false , $count = 30, $type = 'xml' )
	{
	    $this->type = $type;
		$args = array();
	    if( $id )
	        $args['id'] = $id;
	    if( $count )
	        $args['count'] = (int) $count;
	    if( $cursor )
	        $args['cursor'] =  $cursor;
	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
	        
        $request = $this->getAPI() . ($id ?  "/statuses/friends/$id.$this->type" : "/statuses/friends.$this->type");
        $request .= $qs;

        return $this->objectify( $this->process($request) );
	}
    
	function followers( $id = false, $cursor = false , $count = 30, $type = 'xml' )
	{
	    $this->type = $type;
	    $args = array();
	    if( $id )
	        $args['id'] = $id;
	    if( $count )
	        $args['count'] = (int) $count;
	    if( $cursor )
	        $args['cursor'] =  $cursor;
	    $qs = '';
	    if( !empty($args) )
	        $qs = $this->_glue($args);
	        
        $request = $this->getAPI() . ($id ?  "/statuses/followers/$id.$this->type" : "/statuses/followers.$this->type");
        $request .= $qs;
                            
		return $this->objectify( $this->process($request) );
	}


	
    /****** Favorites ******/

	function getFavorites( $page = false, $id = false)
	{	        
	    $args = array();
	    if( $id )
	        $args['id'] = $id;
	    if( $page )
	        $args['page'] = (int) $page;
	    $qs = '';
	    if( !empty($args) )
	        $qs = $this->_glue( $args );
			
		
		$request = $this->getAPI() . '/favorites.' . $this->type . $qs; 
		return $this->objectify( $this->process($request) );
	}
	
	function makeFavorite( $id )
	{
		$request = $this->getAPI() . '/favorites/create/' . $id . '.' . $this->type;
		return $this->objectify( $this->process($request, true) );
	}
	
	function removeFavorite( $id )
	{
		$request = $this->getAPI() . '/favorites/destroy/' . $id . '.' . $this->type;
		return $this->objectify( $this->process($request, true) );	
	}
	

	/**** Friendship Methods ****/
	
	function isFriend( $user_a, $user_b )
	{
	    $args = array();
	    $args['user_a'] = $user_a;
	    $args['user_b'] = $user_b;
	    $qs = $this->_glue( $args );
	    
		$request = $this->getAPI() . '/friendships/exists.' . $this->type . $qs;
		return $this->objectify( $this->process($request) );
	}
	
	function friendship( $source_screen_name, $target_screen_name )
	{
	    $args = array();
	    $args['source_screen_name'] = $source_screen_name;
	    $args['target_screen_name'] = $target_screen_name;
	    $qs = $this->_glue( $args );
	    
		$request = $this->getAPI() . '/friendships/show.' . $this->type . $qs;
		return $this->objectify( $this->process($request) );
	}
	
	function followUser( $id, $notifications = false )
	{	        
	    $args = array();
	    if( $id )
	        $args['screen_name'] = $id;
	    if( $notifications )
	        $args['follow'] = 'true';
		
		$request = $this->getAPI() . '/friendships/create/' . $id . '.' . $this->type;
		return $this->objectify( $this->process($request, $args) );
	}
	
	function destroyUser( $id )
	{
		$request = $this->getAPI() . '/friendships/destroy/' . $id . '.' . $this->type;
		return $this->objectify( $this->process($request, true) );
	}
	
	/****** Block Methods ******/
	
	function blockUser( $id )
	{
		$request = $this->getAPI() . '/blocks/create/' . $id . '.' . $this->type;
		return $this->objectify( $this->process($request, true) );
	}
	
	function unblockUser($id)
	{
		$request = $this->getAPI() . '/blocks/destroy/' . $id . '.' . $this->type;
		return $this->objectify( $this->process($request, true) );
	}

	function isBlock($id)
	{
		$request = $this->getAPI() . '/blocks/exists/' . $id . '.' . $this->type;
		return $this->objectify( $this->process($request) );
	}
	
	/****** Account Methods ******/
		
	function updateAvatar( $file )
	{
	    $postdata = array( 'image' => "@$file");
	    $request = $this->getAPI() . '/account/update_profile_image.' . $this->type;
	    return $this->objectify( $this->process( $request, $postdata ) );
	}
	/*
	function updateBackground( $file )
	{	        
	    // Adding @ ensures the POST will be raw multipart data encoded. This MUST be a file, not a URL. Handle it outside of the class.
	    $postdata = array( 'image' => "@$file");
	    $request = $this->getAPI() . '/account/update_profile_background_image.' . $this->type;
	    return $this->objectify( $this->process( $request, $postdata ) );
	}
	*/
	function updateProfile( $fields = array() )
	{	        
	    $postdata = array();
	    foreach( $fields as $pk => $pv ) {
	        switch( $pk ) 
	        {
	            case 'name' :
	                $postdata[$pk] = (string) substr( $pv, 0, 20 );
	                break;
	            case 'url' :
	                $postdata[$pk] = (string) substr( $pv, 0, 100 );
	                break;
	            case 'location' :
	                $postdata[$pk] = (string) substr( $pv, 0, 30 );
	                break;
	            case 'description' :
	                $postdata[$pk] = (string) substr( $pv, 0, 160 );
	                break;
	            default :
	                break;
	        }
		}
	    $request = $this->getAPI() . '/account/update_profile.' . $this->type;
	    return $this->objectify( $this->process( $request, $postdata ) );
	}
	/*
	function updateColors( $colors = array() )
	{
	    if( !in_array( $this->type, array( 'xml','json' ) ) )
	        return false;
	        
	    $postdata = array();
	    foreach( $colors as $ck => $cv ) :
	        if( preg_match('/^(?:(?:[a-f\d]{3}){1,2})$/i', $hex) ) :
                $postdata[$ck] = (string) $cv;
            endif;
	    endforeach;
	    
		$request = $this->getAPI() . '/account/update_profile_colors.' . $this->type;
	    return $this->objectify( $this->process( $request, $postdata ) );
	}
	*/
	
	/**** Search Method ****/
	
	function search( $q = false, $page = false, $lang = false )
	{
		if( !$q )
			return false;
		
		$args = array();
		$args['q'] = $q;
	    if( $lang )
	        $args['lang'] = $lang;
	    if( $page )
	        $args['page'] = $page;
	    $qs = $this->_glue( $args );
	    $searchApiUrl = strpos($this->getAPI(), "twitter.com") > 0 ? "http://search.twitter.com" : $this->getAPI();
		$request = $searchApiUrl . '/search.' . $this->type . $qs;
			
		return $this->objectify( $this->process($request) );
	}

	function trends() {
	    $searchApiUrl = strpos($this->getAPI(), "twitter.com") > 0 ? "http://search.twitter.com" : $this->getAPI();
		$request = $searchApiUrl . '/trends.' . $this->type ;
			
		return $this->objectify( $this->process($request) );
	}
	/**** List Method ****/

	function createdLists( $username = '', $cursor = false )
	{
	        
	    $args = array();
	    if( $cursor )
	        $args['cursor'] = $cursor;

	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
	    
	    $request = $this->getAPI() . '/' . $username . '/lists.' . $this->type . $qs;    
	    
		return $this->objectify( $this->process($request) );
		
	}

	function followedLists( $username = '', $cursor = false )
	{	
	    $args = array();
	    if( $cursor )
	        $args['cursor'] = $cursor;

	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
	    
	    $request = $this->getAPI() . '/' . $username . '/lists/subscriptions.' . $this->type . $qs;    
	    
		return $this->objectify( $this->process($request) );
		
	}
	
	function beAddedLists( $username = '', $cursor = false )
	{
	    $args = array();
	    if( $cursor )
	        $args['cursor'] = $cursor;

	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
	    
	    $request = $this->getAPI() . '/' . $username . '/lists/memberships.' . $this->type . $qs;    
	    
		return $this->objectify( $this->process($request) );
		
	}
	
	//id格式：username/listname 例:bang590/temp 下同
	function listStatus( $id, $page = false, $since_id = false )
	{
	    if (!$id) {
	    	return false;
	    }
	    
	    $arr = explode('/', $id);
	    if (count($arr) != 2) return false;
	    $username = $arr[0];
	    $listname = $arr[1];
	   
	    $args = array();
	    if( $page )
	        $args['page'] = (int) $page;
	    if( $since_id )
	        $args['since_id'] = $since_id;
	    
	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
	    
	    $request = $this->getAPI() . "/$username/lists/$listname/statuses." . $this->type . $qs;    
	    
		return $this->objectify( $this->process($request) );
		
	}
	
	function listInfo( $id ) {
	
	    if (!$id) {
	    	return false;
	    }
	    
	    $arr = explode('/', $id);
	    if (count($arr) != 2) return false;
	    $username = $arr[0];
	    $listname = $arr[1];
	   
	    $request = $this->getAPI() . "/$username/lists/$listname." . $this->type;    
		return $this->objectify( $this->process($request) );
	}
	
	function isFollowedList( $id )
	{	   
	    $arr = explode('/', $id);
	    if (count($arr) != 2) return false;
	    $username = $arr[0];
	    $listname = $arr[1];
	    
		$request = $this->getAPI() . "/$username/$listname/subscribers/$this->username." . $this->type;
		return $this->objectify( $this->process($request) );
	}
	

	function listMembers( $id, $cursor = false )
	{	        
	    $arr = explode('/', $id);
	    if (count($arr) != 2) return false;
	    $username = $arr[0];
	    $listname = $arr[1];
	    
	    $args = array();
	    if( $cursor )
	        $args['cursor'] = $cursor;
	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
	        
	    $request = $this->getAPI() . "/1/$username/$listname/members." . $this->type . $qs;  
        return $this->objectify( $this->process($request) );
	}

	function listFollowers( $id, $cursor = false )
	{
	    $arr = explode('/', $id);
	    if (count($arr) != 2) return false;
	    $username = $arr[0];
	    $listname = $arr[1];
	    
	    $args = array();
	    if( $cursor )
	        $args['cursor'] = $cursor;
	    $qs = '';
	    if( !empty( $args ) )
	        $qs = $this->_glue( $args );
	        
	    $request = $this->getAPI() . "/1/$username/$listname/subscribers." . $this->type . $qs;  
        return $this->objectify( $this->process($request) );
	}

	function followList( $id )
	{
	    $arr = explode('/', $id);
	    if (count($arr) != 2) return false;
	    $username = $arr[0];
	    $listname = $arr[1];
	    
	    $request = $this->getAPI() . "/1/$username/$listname/subscribers." . $this->type;  
        return $this->objectify( $this->process($request, true) );
	}

	function unfollowList( $id )
	{
	    $arr = explode('/', $id);
	    if (count($arr) != 2) return false;
	    $username = $arr[0];
	    $listname = $arr[1];
	    
	    $request = $this->getAPI() . "/1/$username/$listname/subscribers." . $this->type;  
        return $this->objectify( $this->process($request, true, "DELETE") );
	}

	function createList( $name, $description, $isProtect)
	{
	    $mode = $isProtect ? "private" : "public";
	    $args = array();
	    if( $name )
	        $args['name'] = $name;
	    if( $description )
	        $args['description'] = $description;
	    if( $isProtect )
	        $args['mode'] = $mode;
	        
	    $request = $this->getAPI() . "/$this->username/lists." . $this->type; 
        return $this->objectify( $this->process($request, $args) );
	}

	function editList( $prename, $name, $description, $isProtect)
	{	    
	    $mode = $isProtect ? "private" : "public";
	    $args = array();
	    if( $name )
	        $args['name'] = $name;
	    if( $description )
	        $args['description'] = $description;
	    if( $isProtect )
	        $args['mode'] = $mode;
	    $request = $this->getAPI() . "/$this->username/lists/$prename." . $this->type; 
	    
        return $this->objectify( $this->process($request, $args) );
	}
	
	function deleteList( $id)
	{
	    $arr = explode('/', $id);
	    if (count($arr) != 2) return false;
	    $username = $arr[0];
	    $listname = $arr[1];
	    
	    $request = $this->getAPI() . "/$username/lists/$listname." . $this->type; 
        return $this->objectify( $this->process($request, true, "DELETE") );
	}
	
	
	function deleteListMember( $id, $memberid )
	{	        
	    $arr = explode('/', $id);
	    if (count($arr) != 2) return false;
	    $username = $arr[0];
	    $listname = trim($arr[1]);
	    
	    $args = array();
	    if( $memberid )
	        $args['id'] = $memberid;
	    
	    $request = $this->getAPI() . "/$username/$listname/members." . $this->type;  
        return $this->objectify( $this->process($request, $args, "DELETE") );
	}

	function addListMember( $listid, $memberid )
	{	        	    
	    $args = array();
	    if( $memberid )
	        $args['id'] = $memberid;
			
	    $request = $this->getAPI() . "/$this->username/$listid/members." . $this->type;  
        return $this->objectify( $this->process($request, $args) );
	}
	
	/**** official RT ****/
	
	function addRT( $status_id )
	{	        
	    $request = $this->getAPI() . "/statuses/retweet/$status_id.$this->type"; 
        return $this->objectify( $this->process($request, true) );
	}

	function deleteRT( $status_id )
	{	        
	    $request = $this->getAPI() . "/statuses/retweet/$status_id.$this->type"; 
        return $this->objectify( $this->process($request, true) );
	}
	
	function rtByme($page = false, $count = 20, $since_id = false, $max_id = false){
		$args = array();
		
		if($since_id)
			$args['since_id'] = $since_id;
		if($max_id)
			$args['max_id'] = $max_id;
		if($count)
			$args['count'] = $count;
		if($page)
			$args['page'] = $page;
		
		$request = $this->getAPI() . "/statuses/retweeted_by_me.$this->type";
        return $this->objectify( $this->process($request, $args) );
	}

	function rtTome($page = false, $count = false, $since_id = false, $max_id = false){
		$args = array();
		
		if($since_id)
			$args['since_id'] = $since_id;
		if($max_id)
			$args['max_id'] = $max_id;
		if($count)
			$args['count'] = $count;
		if($page)
			$args['page'] = $page;
			
		$request = $this->getAPI() . "/statuses/retweeted_to_me.$this->type";
        return $this->objectify( $this->process($request, $args) );
	}

	function rtOfme($page = false, $count = false, $since_id = false, $max_id = false){
		$args = array();
		
		if($since_id)
			$args['since_id'] = $since_id;
		if($max_id)
			$args['max_id'] = $max_id;
		if($count)
			$args['count'] = $count;
		if($page)
			$args['page'] = $page;
		
		$request = $this->getAPI() . "/statuses/retweets_of_me.$this->type";
        return $this->objectify( $this->process($request, $args) );
	}
	
	/**** Twitese Method ****/
	
	function rank( $page = false, $count = false )
	{
		$args = array();
	    if( $page )
	        $args['page'] = $page;
	    if( $count )
	        $args['count'] = $count;
	    $qs = $this->_glue( $args );
	    
		$request = TWITESE_API_URL . '/rank.' . $this->type . $qs;
		return $this->objectify( $this->process($request) );
	}

	function browse( $page = false, $count = false )
	{
		$args = array();
	    if( $page )
	        $args['page'] = $page;
	    if( $count )
	        $args['count'] = $count;
	    $qs = $this->_glue( $args );
	    
		$request = TWITESE_API_URL . '/browse.' . $this->type . $qs;
		return $this->objectify( $this->process($request) );
	}
	/**** API Rate Limit ****/
	function ratelimit()
	{
		$request = $this->getAPI() . '/account/rate_limit_status.' . $this->type;
		return $this->objectify( $this->process($request) );
	}
	
		
	/****** Tests ******/
	
	function twitterAvailable()
	{
		$request = $this->getAPI() . '/help/test.' . $this->type;
		if( $this->objectify( $this->process($request) ) == 'ok' )
			return true;
		
		return false;
	}
	
	/****** other ******/
	function getAPI() {
		if (isset($_COOKIE['apiurl'])) {
			return $_COOKIE['apiurl'];
		} else {
			return API_URL;
		}
	}
	
	/**** request method ****/
	function process($url, $postargs=false, $method = false)
	{
		if ($this->debug) {
			echo $url . '<br/>';
			print_r($postargs);
		}
		$ch = curl_init($url);
		if($this->isOAuth){
			user_oauth_sign($url, $postargs);
		}
		
		if($postargs !== false)
		{
			curl_setopt ($ch, CURLOPT_POST, true);
			if (is_array($postargs)) {
				$postargs = $this->_glue($postargs);
				$postargs = substr( $postargs, 1 );
				curl_setopt ($ch, CURLOPT_POSTFIELDS, $postargs);
			} else {
				curl_setopt ($ch, CURLOPT_POSTFIELDS, '');
			}
			if ($method === "DELETE") {
				curl_setopt ($ch, CURLOPT_POSTFIELDS, $postargs . "&_method=DELETE");
			}
        }
        
		if(!$this->isOAuth && $this->username !== false && $this->password !== false)
			curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password );
        
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER,  array('Expect:'));
		
        $response = curl_exec($ch);
        $responseInfo=curl_getinfo($ch);
        curl_close($ch);
		$http_code = intval( $responseInfo['http_code'] );
		
		if ($this->debug) {
			echo 'respones:<br/>';
			print_r($response); 
			echo '<br/>httpcode:';
			print($http_code); 
			echo '<br/>';
		}
		
        if( $http_code != 0) //0无法连接 403超过limit
			return $response;    
        else
            return false;
            
	}
	
	function objectify( $data ) {
	
		if ($data === false) {
			return 'noconnect';
		}
		if( $this->type ==  'json' ) {
			$result = json_decode( $data );
			if ($this->debug) {
				echo '<pre>';
				print_r($result);
				echo '</pre>';
			}
			if (isset($result->error)) {
				if (substr_count($result->request, 'user_timeline') && $result->error == 'Not authorized') {
					return 'protected';
				}
				//[error] => You cannot send messages to users who are not following you.
				if (strrpos($result->error, 'not following') > 0) {
					return 'nofollow';
				}
				//[error] => Could not authenticate you.
				if (strrpos($result->error, 'authenticate') > 0) {
					return 'password';
				}
				
				if (strrpos($result->error, 'favorited') > 0) {
					return 'favorited';
				}
				if (strrpos($result->error, 'limit') > 0) {
					return 'limit';
				}
				if (strrpos($result->error, 'not blocking') > 0) {
					return 'notblock';
				}
				return false;
			} else {
				return $result;
			}
		}
		
		if( $this->type == 'xml' ) {
			if( function_exists('simplexml_load_string') ) {
			    $obj = simplexml_load_string( $data );
			}
			if ($this->debug) {
				echo '<pre>';
				print_r($obj);
				echo '</pre>';
			}
			if (isset($obj->error) || !$obj) return false;
			else return $obj;
		} else {
			return false;
		}
		
	}
	
	
	function _glue( $array ) {
		    $s = array();
		    foreach ($array as $name => $value)
		      $s[] = $name.'='.urlencode($value);
		    $post_data = implode('&', $s);
		    			
			return '?' . $post_data;
	}
}
?>