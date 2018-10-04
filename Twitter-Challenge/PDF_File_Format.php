<?php
@session_start();
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
	$EmailID = $Download_data['EmailID'];
	$user = $Download_data['user_name'];
	
	
	include 'lib/fpdf/htmlpdf.php';
	$pdf = new PDF_HTML();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',16);

		$pdf->WriteHTML('<br/><p align="center">Followers</p><br><hr>');
		$pdf->SetFontSize(10);
		$count = 1;
		$cursor = -1;
		while($cursor != 0)
		{
			$follower = $connection->get('followers/ids',array('count'=>5000,'screen_name'=>$user,'cursor'=>$cursor));
			$cursor = $follower->next_cursor;
			if(!isset($cursor))
			{	$pdf->WriteHTML("<p>try after 15 mins</p>");
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
		$filenamepdf=$user.'.pdf';
		//$output=
		$pdf->Output('F',$filenamepdf);
		// file_put_contents($filenamepdf, $output);
	
}

if ($cursor == 0)
{
    require '/home/wejnxgbtf05d/public_html/Twitter-Challenge/PHPMailer-master/src/Exception.php';
    require '/home/wejnxgbtf05d/public_html/Twitter-Challenge/PHPMailer-master/src/PHPMailer.php';
    require '/home/wejnxgbtf05d/public_html/Twitter-Challenge/PHPMailer-master/src/SMTP.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsMail();
    $mail->Host = 'relay-hosting.secureserver.net';
    $mail->Port = 25;
    $mail->SMTPAuth = false;
    $mail->SMTPSecure = false;
    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->IsHTML(true);
    $mail->Username = "divudpatel89@gmail.com";  
    $mail->Password = "95175385245669495192";
    $mail->SetFrom('divudpatel89@gmail.com', 'Twitter-data');
    $mail->Subject = "Followers Data";
    $mail->AltBody = "";
    $mail->AddAddress($EmailID);
    $mail->MsgHTML("Your requested follower data is in file attached below");
      
        $mail->AddAttachment('/home/wejnxgbtf05d/public_html/Twitter-Challenge/'.$filenamepdf);    
      
    $mail->Send();
  
   header('location:home.php');
 
   // echo __DIR__;
    }
?>
