<?php
//create by  @miajiao & @dcshi
include ('lib/utility.php');
require_once("lib/Scrape.php");

$url = 'https://twitter.com/oauth/authorize';

$authenticity_token = $_POST['authenticity_token'];
$oauth_token = $_POST['oauth_token'];
$username = $_GET['username'];
$password = urldecode(spDecrypt($_GET['password']));
$data = array();
$data = array('session[username_or_email]' => $username, 'session[password]' => $password);

$scrape = new Scrape(); 


$data['authenticity_token']=$authenticity_token;
$data['oauth_token']=$oauth_token;
$scrape->fetch($url,$data);

echo $scrape->result; 

?>