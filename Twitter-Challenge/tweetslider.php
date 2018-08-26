<?php
@session_start();
include_once("includes/config.php");
include_once("auth/twitteroauth.php");

if(isset($_SESSION['status']) && $_SESSION['status'] == 'verified')
{
    $oauth_token 		= $_SESSION['request_vars']['oauth_token'];
    $oauth_token_secret = $_SESSION['request_vars']['oauth_token_secret'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
	$sc_name = $_REQUEST['screen_name'];
    $tweets = $connection->get('statuses/user_timeline', array('screen_name' => $sc_name, 'count' => 10));
  
    if(count($tweets) < 1)
    {
        echo "Tweets not available.";
        exit;
    }
    ?>
<div id="myCarousel" class="carousel slide" data-ride="carousel">

    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
        <span class="sr-only">Next</span>
    </a>
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


</div>

<?php } ?>