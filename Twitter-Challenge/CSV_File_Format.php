<?php
session_start();
error_reporting(0);
include_once("includes/config.php");
include_once("auth/twitteroauth.php");
$user_name = $_SESSION['request_vars']['screen_name'];
$oauth_token 		= $_SESSION['request_vars']['oauth_token'];
$oauth_token_secret = $_SESSION['request_vars']['oauth_token_secret'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);

if(isset($connection))
{
	$Download_data = $_SESSION['DownloadInfo'];
	$file = $Download_data['file_name'];
	$user = $Download_data['user_name'];
	
	header('Content-Type: application/excel');
	
	header('Content-Disposition: attachment; filename='.$file.'.csv');
	
	$fp = fopen('php://output', 'w');
		$data = array("Name", "Username", "Location", "CreatedOn");
		fputcsv($fp, $data);
		$cursor = -1;
		while ($cursor!=0) {
			$followers = $connection->get('followers/ids',array('count'=>5000,'screen_name'=>$user,'cursor'=>$cursor));
			$cursor = $followers->next_cursor_str;
			if(!isset($cursor))
			{	echo ("<p>try after 15 mins</p>");
				break;
			}
			$followers_arrays = array_chunk($followers->ids, 100);
			foreach($followers_arrays as $implode) {
				$info = $connection->get('users/lookup', array('user_id' => implode(',', $implode)));
				foreach($info as $item) {
					fputcsv($fp, array(
						$item->name,
						'@' . $item->screen_name,
						$item->location,
						$item->created_at));
				}
			}
		}
	
	fclose($fp);
}
?>
