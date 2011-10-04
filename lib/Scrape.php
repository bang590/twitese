<?php 

class Scrape {
	
	
	public $headers = array();
	
	public $result;
	
	public $error;
	
	
	
	function __construct() {
		
		return true;
		
	}
	
	
	
	function setHeader($header) {
		
		$this->headers[] = $header;
		
	}
	

	
	function fetch($url, $data=''){
		
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		
		curl_setopt($ch, CURLOPT_HEADER, false);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		curl_setopt($ch, CURLOPT_FRESH_CONNECT,true);
		
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)"); 
		
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
		
//		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
	
		
		if (is_array($data) && count($data)>0){
			
			curl_setopt($ch, CURLOPT_POST, true);
			
			$params = http_build_query($data);
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			
		}
			
		
		if (is_array($this->headers) && count($this->headers)>0){
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
			
		}
		
		
		$this->result = curl_exec($ch);
		
		$this->error = curl_error($ch);
		
		curl_close($ch);	
		
	}
	
	
	
	function fetchBefore($needle,$haystack,$include=false){
		
		$included = strpos($haystack,$needle) + strlen($needle);
		
		$excluded = strpos($haystack,$needle);
		
		if ($included === false || $excluded === false) { return null; }
		
		$length = ($include == true) ? $included : $excluded ;
		
		$substring = substr($haystack, 0, $length);
		
		return trim($substring);
		
	}
	
	
	
	function fetchAfter($needle,$haystack,$include=false){
		
		$included = strpos($haystack,$needle);
		
		$excluded = strpos($haystack,$needle) + strlen($needle);
		
		if ($included === false || $excluded === false) { return null; }
		
		$position = ($include == true) ? $included : $excluded ;
		
		$substring = substr($haystack, $position, strlen($haystack) - $position);
		
		return trim($substring);
		
	}
	
	
	
	function fetchBetween($needle1,$needle2,$haystack,$include=false){
		
		$position = strpos($haystack,$needle1);
		
		if ($position === false) { return null; }
		
		if ($include == false) $position += strlen($needle1);
		
		$position2 = strpos($haystack,$needle2,$position);
		
		if ($position2 === false) { return null; }
		
		if ($include == true) $position2 += strlen($needle2);
		
		$length = $position2 - $position;
		
		$substring = substr($haystack, $position, $length);
		
		return trim($substring);
		
	}
	
	
	
	function fetchAllBetween($needle1,$needle2,$haystack,$include=false){
		
		$matches = array();
		
		$exp = "|{$needle1}(.*){$needle2}|U";
		
		preg_match_all($exp,$haystack,$matches);
		
		$i = ($include == true) ? 0 : 1 ;
		
		return $matches[$i];
		
	}
	
	
	
	function removeNewlines($input){
		
		return str_replace(array("\t","\n","\r","\x20\x20","\0","\x0B"), "", html_entity_decode($input));
		
	}
	
	
	
	function removeTags($input,$allowed=''){
		
		return strip_tags($input,$allowed);
		
	}
	
	
 
function http($url, $follow = false, $header = false)

{
	$ch = curl_init($url);
		
	curl_setopt($ch, CURLOPT_HEADER, false);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	curl_setopt($ch, CURLOPT_FRESH_CONNECT,true);
	
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)"); 
	
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	
	curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
	
//	curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
	
	curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
		
	$content = curl_exec($ch);	
 
	curl_close($ch);
	return $content;
}   
	
}







?>