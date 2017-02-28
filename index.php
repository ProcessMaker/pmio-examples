<?php
$key = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjNkZGQ0MzEyYzdkZGE1OGZiOTUxMmUzODg0ZWI0OGZhNTFlY2M1NzhiOWY1M2EyMTJjODFlY2Q4YWVmODEyMzI4MzIzMTNjZWQ4ZDExN2EyIn0.eyJhdWQiOiIxIiwianRpIjoiM2RkZDQzMTJjN2RkYTU4ZmI5NTEyZTM4ODRlYjQ4ZmE1MWVjYzU3OGI5ZjUzYTIxMmM4MWVjZDhhZWY4MTIzMjgzMjMxM2NlZDhkMTE3YTIiLCJpYXQiOjE0ODgzMDI5MDUsIm5iZiI6MTQ4ODMwMjkwNSwiZXhwIjoxNTE5ODM4OTA1LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.G7QZQRucUklXHB9hdnqljpebmsABeiTN9ketUjlC8KXcSSLMhSANQLC-tuhm4Yr_tbpDMClT615WjQyIrNNx_Si9px8omH2owbl4HZ9Opo6CHqh6R9cTFIjYkzG_HOKOU3mMouQvz10LlaxerWvN_ic2yv90iaziUB4iBg73Ls5P9pH8BeQGePPKPtyqd1k7Mn0WDzHFVsaINeWnIkAGEJEVHUmtAogKX6l0mzD_rEY9TZTCgbUr_WmGbASUZBEAvV6JHpCmSraNZcSSxtIHn4eVxuRQ72SK4DTihLJwJylfBjuiflvEgU4SGHS2I9rKURBPfj2-cuCXpTF6qMIHzThfneH0qN0Y2zCbEnz3cHRs-1bNwWVZiji5dV9yyy48h0gfBsaO2ieXnOWT45y26AfPRp-18CJmSoh3tNMivo0NRbnpoCxBYwyVSKjjLz8RrfBA02dFhr3czrXelHBqwUTt1D-GxYXajlCaLp-1Wqk3WG15f165SuzGyQUv729YzYRbvWoO5IULyCB_yX0HrzLLnMm1AZH93Cy8e2r-vx0QvlxSQVziSycLKDF3KVX2iIliQVqNq-M9q1q98rEAzv6azHjUt0DfUPr388-fzuzwJ7W5XXv8-s5xgp4k37ykbEWnASij7Dlk7zVDJcbLQVZHDL3U8dFzudJwmDa6d3g';
$host = '4.0.0-mvp1-test.qacore.processmaker.net';

if($_GET['user']) $_COOKIE['user']=$_GET['user'];

$user = (isset($_COOKIE['user'])?$_COOKIE['user']:'Test');

$file = str_replace("/","forms/", str_replace('?'.$_SERVER['QUERY_STRING'],'', $_SERVER['REQUEST_URI']));
//echo "<pre>";var_dump($_SERVER);
//echo $file;
if(file_exists($file)) {
include $file;
//$content = file_get_contents($file);
//$content = str_replace('%HOST%',,$content);
//$content = str_replace('%KEY%',$key,$content);
//echo($content);
exit;
}
else include "forms/import_form.html";
?>
