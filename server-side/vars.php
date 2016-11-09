<?php

$URLS_FILE = '';

$ip = $_SERVER['REMOTE_ADDR'];

if($ip==='')
{
	$URLS_FILE = 'mylk.txt';
}
else
{
	$URLS_FILE =  $ip . '.txt';
}

?>