<?php
require ".env";

if($_GET['user']) $_COOKIE['user']=$_GET['user'];

$user = (isset($_COOKIE['user'])?$_COOKIE['user']:'Test');
setcookie("user", $user, time()+3600);
$key = $key[$user];
switch ($user) {
    case 'Alice':
        $bgcolor = '#fff0f0'; break;
    case 'Bob':
        $bgcolor = '#f0fff0'; break;
    default:
        $bgcolor = '#f0f0ff';
}

$file = str_replace("/","forms/", str_replace('?'.$_SERVER['QUERY_STRING'],'', $_SERVER['REQUEST_URI']));

if(!file_exists($file)) $file = 'forms/import_form.html';

include $file;
?>
