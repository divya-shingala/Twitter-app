
<?php

@session_start();
$file = file('tmp_file/'.$_SESSION['user'].'_followers.csv');
$input = $_REQUEST['term'] ;
$result = array_filter($file, function ($value) use ($input) {
    if (stripos($value, $input) !== false) {
        return true;
    }
    return false;
});
echo (json_encode($result));
exit;
?>