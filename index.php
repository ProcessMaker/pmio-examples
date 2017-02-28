<?php
require ".env";

if($_GET['user']) $_COOKIE['user']=$_GET['user'];

$user = (isset($_COOKIE['user'])?$_COOKIE['user']:'Test');
$key = $key[$user];

$file = str_replace("/","forms/", str_replace('?'.$_SERVER['QUERY_STRING'],'', $_SERVER['REQUEST_URI']));

if(!file_exists($file)) $file = 'forms/import_form.html';

include $file;
?>
