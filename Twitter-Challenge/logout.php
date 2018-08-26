<?php

if(array_key_exists('logout',$_GET))
{
    @session_start();
    unlink('tmp_file/'.$_SESSION['user'].'_followers.csv');
    unlink('tmp_file/'.$_SESSION['user'].'_nextCursor.txt');
	session_destroy();
	header("Location:index.php");
}
?>