<?php
//create by  @miajiao & @dcshi
include ('lib/utility.php');

$url = 'https://twitter.com/oauth/authenticate';
 
$authenticity_token = $_POST['authenticity_token'];
$oauth_token = $_POST['oauth_token'];
$username = $_POST['session']['username_or_email'];
$password = $_POST['session']['password'];
$data = array('session[username_or_email]' => $username, 'session[password]' => $password);

require_once("lib/Scrape.php");
$scrape = new Scrape(); 

$data['authenticity_token']=$authenticity_token;
$data['oauth_token']=$oauth_token;

$scrape->fetch($url,$data);

//echo $scrape->result; 

$oldInput = $scrape->result;
$search_contents ='https://twitter.com/oauth/authorize';
$replace_contents = 'authenticate_2.php?username='.$username.'&password='.spEncrypt($password);
$new = str_replace($search_contents,$replace_contents,$oldInput);
echo $new;

?>