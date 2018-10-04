## Twitter-Timeline Challenge

 Develop by : Divya Shingala
 
 Twitter    : @Divya_shingala
 
 Email      : divudpatel89@gmail.com
 

	In Twitter-Timeline Challenge we can used for search particular follower and see their tweets on slider and home timeline. we can download the any user's follower in any format.
	
## Demo

http://aviddivyatwitter.xyz/Twitter-Challenge/process.php

## library

	Twitter API  			: v1.1 
	Google API 	 		: v2.0
	Bootstrap    			: v3.3.7
	jQuery       			: v1.10.2
	FPDF 	     			: v1.81
	
## Features

	1. Visits http://aviddivyatwitter.xyz/Twitter-Challenge/process.php page.
	2. We will connect on script using Twitter account using Twitter Auth.
		- If login user than view their profile on script.
	3. After authentication, we will display latest 10 tweets on “home” timeline.
	4. 10 tweets will be displayed slideshow.
	5. display list followers .
	6. Also, display a search followers box.In that auto-suggest support. That means as soon as the user starts typing, and 		   followers will start showing up.
	7. When user will click on a follower name, 10 tweets from that follower’s user-timeline will be displayed in the same slider, 		   without page refresh (use AJAX).
	8. There will be a follower download link to download all followers of any user(we will input user @handler).
	9. Once the user clicks follower download link all followers of the specified user should be downloaded.
	
## Directory

	auth
		-OAuth.php
		-twitteroauth.php
	css
  
		-bootstrap.min
		-font-awesome.css
		-notification.css
		-home.css
		-style.css
    
	js
		-jquery.vide.min.js
		-jquery-2.1.4.min.js
		-script.js
		-slider.js
    
	bootstrap
		-css
		-js
		-fonts
    
	lib
		-fpdf
    
	include
		-config.php
		-users.php
	
