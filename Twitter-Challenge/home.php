<?php
ob_start();
@session_start();
error_reporting(0);
include_once("includes/config.php");
include_once("auth/twitteroauth.php");
if(isset($_REQUEST['google_download']))
{
		header('location:google_spreadsheet.php?screen_name='.$_REQUEST['screen_name']);
}
if(isset($_REQUEST['btn_Download']))
{
	$EmailID = $_REQUEST['EmailID'];
	$username = $_REQUEST['user_name'];
	$fileformat = $_REQUEST['file_format'];
	$arr = array("EmailID"=>$EmailID,"user_name"=>$username);
	$_SESSION['DownloadInfo'] = $arr;		
	if($fileformat == 'CSV')
		header('location:CSV_File_Format.php?');
	else if($fileformat == 'XML')
		header('location:XML_File_Format.php?');
	else if($fileformat == 'PDF')
		header('location:PDF_File_Format.php?');
		ob_clean();
}

	?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Twitter Challenge </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
	<link href="css/notification.css" rel="stylesheet" />
	 <link href="css/home.css" rel="stylesheet" />
    <script src="bootstrap/js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script>
        // Follower live search and 
        $(function() {
            $("#search").autocomplete({
                source: "livesearch-follower.php",
            });
        });
        //
        function livesearch(name)
        {
            $.ajax({
                type: "POST",
                url: "tweetslider.php",
                data: {'screen_name': name},
                success: function(dataString) {
                    $('#panel_body').html(dataString);
                }
            });
        }
    function close()
    {
        document.getElementById("close").click();
    }
    </script>
</head>
<body>
<?php
if($_SESSION['status'] == 'verified' && isset($_SESSION['status']) )
{
    //Retrive session value
    $oauth_token 		= $_SESSION['request_vars']['oauth_token'];
    $oauth_token_secret = $_SESSION['request_vars']['oauth_token_secret'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
    $user = $connection->get('account/verify_credentials');
	 $sc_name = $_SESSION['request_vars']['screen_name'];
    $_SESSION['user']=$user->screen_name;
}

?>
	<!-- post tweet Modal -->
	<div class="modal fade" id="tweetmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		<form method="post" action="home.php">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-center" id="myModalLabel">Post New Tweet</h4>
		  </div>
		  <div>
		  
			<div class="row">
				<div class="col-sm-1 text-center">
					<img src=<?php if(isset($user)) echo $user->profile_image_url_https; ?> alt="user" class="img-circle" width="30" height="30"/>
				</div>
			
				<div class="col-sm-11 text-right">
					<textarea class="form-control ftxt" rows="4" placeholder="What's happening?.." name="Postweets"></textarea>
					<br />
					<button type="submit" class="btn btn-success tweet_btn" >Tweet</button>
							<br />
				</div>
		
			</div>
		  </div>
		</form>
		</div>
	  </div>
	</div>
    <nav class="navbar navbar-inverse">
        <div class="container">
          
            <div  id="myNavbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
					<li><a href="#" ><img src="img/twitter-logo.png" class="my_twitter_logo"/><span>Twitter Challenge</span></a> </li>
				</ul>
				 <ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<img src=<?php if(isset($user)) echo $user->profile_image_url_https; ?> class="img-circle" height="20px" width="20px"/>
						</a>
						
						
					</li>
				
					<li><a href="logout.php?logout"><span> Logout</span></a></li>
				
                </ul>
				 <form role="search" class="navbar-form navbar-center" >
				  <div class="form-group">
					<input class="form-control"  placeholder='Search by follower UserName ' type="text" onchange='livesearch(this.value)'  id="search" 
						 name="search"  style=" width: 100%;margin-left: 161px;" />
				</form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="  well col-sm-3 ">
             		<div class="frame">
					<div class="center">
					<!--Profile of user..  user informations are display.-->
						<div class="profile">
							<div class="image">
								<div class="circle-1"></div>
								<div class="circle-2"></div>
								<img src=<?php if(isset($user)) echo $user->profile_image_url_https; ?>  width="70" height="70" alt="Jessica Potter">
							</div>
							
							<div class="name"><?php if(isset($user)) echo $user->name; ?></div>
							<div class="job"><?php if(isset($user)) echo '@'.$user->screen_name; ?></div>
							<!--over here one button for post new tweet on site-->
							<div class="actions">
										<button class="btn"  data-toggle="modal" data-target="#tweetmodal" class="tweet_btn">Tweet</button>
										<button class="btn"  data-toggle="modal" data-target="#download" class="tweet_btn">Download</button>
										<button class="btn"  class="dropdown" data-toggle="modal" data-target="#Google_download" class="tweet_btn">Google sheet</button>
							</div>
						</div>
						<!--counting of total no of tweets , following, followers-->
						<div class="stats">
							<div class="box">
								<span class="value"><?php if(isset($user)) echo $user->statuses_count; ?></span>
								<span class="parameter">Tweets</span>
							</div>
							<div class="box">
								<span class="value"><?php if(isset($user)) echo $user->friends_count; ?></span>
								<span class="parameter">Following</span>
							</div>
							<div class="box">
								<span class="value"><?php if(isset($user)) echo $user->followers_count; ?></span>
								<span class="parameter">Followers</span>
							</div>
						</div>
					</div>
				</div>
				<div class="page-header text-center primary">
				  <h4 class="blue">Latest Tweet</h4>
				</div>
					<?php
						$tweets = $connection->get('statuses/user_timeline', array('screen_name' => $sc_name, 'count' => 20));
						foreach ($tweets  as $tweet) {
							echo '<p><span class="list-group-item">'.$tweet->text.' <br />-<i>'.$tweet->created_at.'</i><span></p>';
						}
					?>
            </div>

			<div class="col-sm-6">
                <div class="row well">
				<?php 
						//File uploading notifiction
						if(isset($_GET['fileName']))
						{?>
							
						<div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<strong>Sucessfully</strong> Your file is backuped in drive !. 
							<a href="https://www.google.com/drive/" class="alert-link" target="_blank">Goto Google drive</a>
						</div>
						<?php
						}
						?>
						
					<?php
					//Tweet Notification
					if(isset($_POST["Postweets"])) 
					{
						$my_update = $connection->post('statuses/update', array('status' => $_POST["Postweets"]));
						if($my_update)
						{
					?>
					<div class="alert alert-success alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Sucessfully</strong> Your tweet is posted !. 
						<a href="home.php" class="alert-link">Refresh</a>
					</div>
					<?php }$my_update=""; } ?>
					
					
					<?php			
						
						if(isset($connection))
						{
							$tweets = $connection->get('statuses/home_timeline',array('count' => 10));
							if(count($tweets) < 2)
							{
								echo "Please try after 15mins ";
								exit();
							}
						}
					?>
                    <div class="col-sm-12" id="tweetslider">
                        <div class="panel panel-default text-left">
                            <div class="panel-body" id="panel_body">
                                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                    
                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner text-center">
									<div class="item active">
										<center>
										<img src="<?php if(isset($tweets)) echo $tweets[0]->user->profile_image_url;?>" class="img-circle"/>
										</center>
											<p><?php if(isset($tweets)) echo $tweets[0]->text; ?></p>
											<p><?php if(isset($tweets)) echo $tweets[0]->created_at; ?></p>
                                    </div>
										
                   
                                     <?php
										foreach ($tweets  as $tweet) {
											if($tweets[0]->id == $tweet->id)
												continue;
										?>
										<div class="item">
										<center>
										<img src="<?php if(isset($tweets)) echo $tweet->user->profile_image_url;?>" class="img-rounded"/>
										
											<p><?php if(isset($tweets)) echo $tweet->text; ?></p>
											
											<p><?php if(isset($tweets)) echo $tweet->created_at; ?></p>
                                        </div>
										</center>
										<?php } ?>
                                    </div>

                                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                        <span class="glyphicon glyphicon-chevron-left"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                        <span class="glyphicon glyphicon-chevron-right"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
				<?php 
				//display tweets
						foreach ($tweets  as $tweet) {				
				?>
				<div class="row">
	
        
        <div class="col-sm-12 well" style=" margin-top: 20px; padding-top:  20px;  padding-bottom: 20px; ">
            <div id="tb-tweet" class="tweet tweet-primary">
                <div class="tweet-section">
                   <?php echo $tweet->text; ?>
                </div>
                <div class="tweet-desc">
                    <img src="<?php echo $tweet->user->profile_image_url;?>" alt="" />
                    <div class="tweet-writer">
                    	<div class="tweet-writer-name"><?php echo "<b>".$tweet->user->name."</b>"; ?></div>
                    	<div class="tweet-writer-designation"><?php echo "    @".$tweet->user->screen_name; ?></div>
                    	<div class="tweet-writer-company"><?php echo $tweet->created_at; ?></div>
                    </div>
                </div>
            </div>   
		</div>
        
       	</div>
               	<?php } ?>

            </div>
            <div class="col-sm-3 well">
                <div style="background-color:white;">
                    <p style="font-weight:bold;" class="text-center blue">Follower</p>
					<hr/>
					<?php if(isset($connection))
					{
						$follower = $connection->get('followers/list',array('count' => 10));
						$counter=0;	
						//print_r($following->users[1]->name);
						while($counter < count($follower->users)) {
							echo '<p>
							<span class="list-group-item follower-hover">'.
							'<img src='.$follower->users[$counter]->profile_image_url.' class="img-circle" width="30" height="30"/>'
							."  ".$follower->users[$counter]->name;'</span>
							</p>';
							echo '<span class="user_name"> @'.$follower->users[$counter]->screen_name;'</span>';
							$counter = $counter + 1;
						}	
					}
					?>
                </div>
				
            </div>
		</div>
    </div>
	
	
    <footer class="container-fluid text-center">
        <p>Developed by @Divya_shingala</p>
    </footer>
	

	<div class="modal fade" id="Google_download" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-center" id="myModalLabel">Google Spreadsheet</h4>
		  </div>
		  <br />
		  <div>
			<div class="row">			
				<div class="col-sm-12 text-right">
				<form method="get">
					<input type="text"  class="form-control ftxt" placeholder="Enter User Name" name="screen_name" id="screen_name"/>
					<br />
					<input type="submit" class="btn btn-success tweet_btn" value="Submit" name="google_download" 
					    onclick="close();"/>
					</form>
				</div>
			</div>
		  </div>
		</form>
		</div>
	  </div>
	</div>
	<!-- Modal for Download -->
	<div class="modal fade" id="download" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		<form method="post">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-center" id="myModalLabel">Follower Download</h4>
		  </div>
		  <br />
		  <div>
			<div class="row">
				<div class="col-sm-12">
				<input type="text"  class="form-control ftxt" placeholder="Enter User Name" name="user_name">
				</div>
			</div>
		<br />
		</div>
		<div class="row">
				<div class="col-sm-6">
					<input type="text"  class="form-control ftxt" placeholder="Enter EmailID" name="EmailID">
					<br/>
					<button type="button" class="btn btn-success tweet_btn" data-dismiss="modal">Cancel</button>
				</div>
				
				<div class="col-sm-6 text-right">
					<select class="form-control" name="file_format">
					<!--  <option value="XML">XML</option>-->
					  
					  <option value="PDF">PDF</option>
					  <option value="CSV">CSV</option>
					</select>
					<br/>
					<button type="submit" class="btn btn-success tweet_btn" onclick="close();" name ="btn_Download">Submit</button>
				</div>					
			</div>
			<br/>
		  </div>
		</form>
		</div>
	  </div>
	</div>
	<!-- Modal  -->
	
</body>
</html>
