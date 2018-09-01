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
	$file = $Download_data['file_name'];
	$user = $Download_data['user_name'];
	
	
	include 'lib/fpdf/htmlpdf.php';
	$pdf = new PDF_HTML();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',16);

		$pdf->WriteHTML('<br/><p align="center">Followers</p><br><hr>');
		$pdf->SetFontSize(10);
		$count = 1;
		$cursor = -1;
		while($count != 0)
		{
			$follower = $connection->get('followers/ids',array('count'=>5000,'screen_name'=>$user,'cursor'=>$cursor));
			$cursor = $follower->next_cursor;
			if(!isset($cursor))
			{	$pdf->WriteHTML("<p>try after 15 min...</p>");
				break;
			}
			
			$follower_arrays= array_chunk($follower->ids, 100);
			foreach($follower_arrays as $implode) {
				$info = $connection->get('users/lookup', array('user_id' => implode(',', $implode)));
				foreach($info as $item) {
					
					$pdf->WriteHTML("<p>{$count} .{$item->name} (@{$item->screen_name}) </p><br />");
					$count++;
				}
			}
		}
		ob_end_clean();
		$pdf->Output('D',$file.'.pdf');
	
}
?>
