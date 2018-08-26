<?php

@session_start();
include_once 'google/Google_Client.php';
include_once 'google/contrib/Google_Oauth2Service.php';
require_once 'google/contrib/Google_DriveService.php';

$client = new Google_Client();
$client->setClientId('Enter_client_id');
$client->setClientSecret('Enter_client_secret');
$client->setRedirectUri('Enter_redirect_uri');
$client->setScopes(array('https://www.googleapis.com/auth/drive.file'));


if (isset($_GET['code']) || (isset($_SESSION['access_token']))) {
	
	$service = new Google_DriveService($client);
    if (isset($_GET['code'])) {
		$client->authenticate($_GET['code']);
		$_SESSION['access_token'] = $client->getAccessToken();		
    } else
        $client->setAccessToken($_SESSION['access_token']);
	
    
   //prepare a csv file for drive
    $fileName=$_SESSION['fileName'];
    $path = 'tmp_file/'.$fileName;
	$file = new Google_DriveFile();
    $file->setTitle($fileName);
    $file->setMimeType('application/vnd.google-apps.spreadsheet');
    $file->setDescription('Uploading the all follower of the user');
	
    $createdFile = $service->files->insert($file, array(
			'data' => file_get_contents($path),
			'mimeType' => 'text/csv',
			'uploadType' => 'multipart',
			'fields' => 'id'));
	unlink($path);
	ob_clean();
    header('location:home.php?fileName=user');
} else {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit();
}
?>