<?php
require ".env";

if(isset($_GET['user'])) $_COOKIE['user']=$_GET['user'];

$user = (isset($_COOKIE['user'])?$_COOKIE['user']:'Test');
setcookie("user", $user, time()+3600);
$key = $key[$user];
switch ($user) {
    case 'Alice':
        $bgcolor = '#fff0f0';
        $avatar = 'https://t4.ftcdn.net/jpg/00/40/86/67/160_F_40866761_aJbSYnSCRu5HCcp5ZocbavSmb1LJZcO0.jpg';
        break;
    case 'Bob':
        $bgcolor = '#f0fff0';
        $avatar = 'http://iconfever.com/images/portfolio/spongebob.jpg';
        break;
    default:
        $bgcolor = '#f0f0ff';
        $avatar = 'https://www.maxcdn.com/wp-content/uploads/2015/07/team-tdondich-BW.png';
}

$file = str_replace("/","forms/", str_replace('?'.$_SERVER['QUERY_STRING'],'', $_SERVER['REQUEST_URI']));

if(!file_exists($file)) $file = 'forms/import_form.html';

include $file;
?>
