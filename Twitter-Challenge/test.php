<?php
session_start();
include_once("includes/config.php");
include_once("auth/twitteroauth.php");
//error_reporting(0);
ini_set('display_errors', 1); 
error_reporting(E_ALL);
//memory limit set to necessary
ini_set('memory_limit', '-1');
$user_name = $_SESSION['request_vars']['screen_name'];
$oauth_token 		= $_SESSION['request_vars']['oauth_token'];
$oauth_token_secret = $_SESSION['request_vars']['oauth_token_secret'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
	
	$filecron = fopen("cron.txt","a");
	$email = $_REQUEST['email'];
	$format = $_REQUEST['format'];
	$follower_name = $_REQUEST['follower_name'];
	$file_name = $_REQUEST['file_name'];
	
	
	$arr = array("file_name"=>$file_name,"user_name"=>$follower_name,"email"=>$email);
	$_SESSION['DownloadInfo'] = $arr;		
	if($format == 'CSV')
		$str = "*/15 * * * * php ".getcwd()."/CSV_File_Format.php ".$format." -1 ".$follower_name." ".$email." \n";
//header('location:CSV_File_Format.php?');
	else if($format == 'XML')
		//header('location:XML_File_Format.php?');
			$str = "*/15 * * * * php ".getcwd()."/XML_File_Format.php ".$format." -1 ".$follower_name." ".$email." \n";
	else if($format == 'PDF'){
	echo "hi";
	//	header('location:PDF_File_Format.php?');
	    	$str = "*/15 * * * * php ".getcwd()."/PDF_File_Format.php ".$format." -1 ".$follower_name." ".$email." \n";
	}
		//ob_clean();


	$result = fwrite($filecron,$str);
	if($result == true)
	{
	   
	echo shell_exec('sh ./cron.sh');
            $output = exec('crontab -l');
            echo "cron Running output ==> ".$output;
		
	}
	

	
?>
