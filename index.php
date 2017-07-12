<?php

if (isset($_GET['Test']) && isset($_GET['host'])) {
    setcookie("Test", $_GET['Test'], time()+86400, '/');
    setcookie("host", $_GET['host'], time()+86400, '/');
    $_COOKIE['host'] = $_GET['host'];
    $_COOKIE['Test'] = $_GET['Test'];
}

isset($_COOKIE['host']) ? $host = $_COOKIE['host'] : $host='your.domain';
isset($_COOKIE['Bob']) ? $key['Bob'] = $_COOKIE['Bob'] : $key['Bob'] ='Key for Bob';
isset($_COOKIE['Alice']) ? $key['Alice'] = $_COOKIE['Alice'] : $key['Alice'] ='Key for Alice';
isset($_COOKIE['Test']) ? $key['Test'] = $_COOKIE['Test'] : $key['Test'] ='Key for Test';



if(isset($_GET['user']) ) $_COOKIE['user']=$_GET['user'];

$user = (isset($_COOKIE['user'])?$_COOKIE['user']:'Test');
setcookie('user', $user, time()+86400, '/');
$key = $key[$user];
switch ($user) {
    case 'Alice':
        $bgcolor = '#fff0f0';
        $avatar = '/images/alice.jpg';
        break;
    case 'Bob':
        $bgcolor = '#f0fff0';
        $avatar = '/images/bob.jpg';
        break;
    default:
        $bgcolor = '#f0f0ff';
        $avatar = '/images/test-user.jpg';
}

$file = str_replace("/","forms/", str_replace('?'.$_SERVER['QUERY_STRING'],'', $_SERVER['REQUEST_URI']));

if(!is_file($file)) $file = 'forms/readme.html';

include $file;

?>
