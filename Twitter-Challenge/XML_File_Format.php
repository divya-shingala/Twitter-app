<?php
session_start();
include_once("includes/config.php");
include_once("auth/twitteroauth.php");
error_reporting(0);
$user_name = $_SESSION['request_vars']['screen_name'];
$oauth_token 		= $_SESSION['request_vars']['oauth_token'];
$oauth_token_secret = $_SESSION['request_vars']['oauth_token_secret'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
if(isset($connection))
{
	$Download_data = $_SESSION['DownloadInfo'];
	$user = $Download_data['user_name'];
	$file = $Download_data['file_name'];
	
	header('Content-type: text/xml');
	
	header('Content-Disposition: attachment; filename='.$file.'.xml');
	
		$xml = new SimpleXMLElement("<?xml version=\"1.0\"?>  <followers></followers>");
		$cursor = -1;
		while ($cursor!=0) {
			$followers = $connection->get('followers/ids',array('count'=>5000,'screen_name'=>$user,'cursor'=>$cursor));
			$cursor = $followers->next_cursor_str;
			if(!isset($cursor))
			{	echo ("try after 15 mins");
				break;
			}
			$follower_array = array_chunk($followers->ids, 100);
			foreach($follower_array as $implode) {
				$info = $connection->get('users/lookup', array('user_id' => implode(',', $implode)));
				foreach($info as $item) {
					$child = $xml->addChild('follower');
					$child->addChild('name',$item->name);
					$child->addChild('username',$item->screen_name);
					$child->addChild('location',$item->location);
					$child->addChild('date',$item->created_at);
				}
			}
		}
		print($xml->asXML());
	}
	header('loaction:home.php');

?>
