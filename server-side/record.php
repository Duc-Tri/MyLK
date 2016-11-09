<?php

include 'vars.php';
include 'process_urls.php';

global $ERRORCODE, $READ_COUNT, $WRITE_COUNT, $URLS_ARRAY;

//read_urls();

//-------------------------------------------------------------------------------------------------
// Récupération des variables POST
//-------------------------------------------------------------------------------------------------
$url_post = '===NO_URL===';
if (  !empty(  $_POST['url']  )  )
{
	$url_post = $_POST['url'];
}
$title_post = '===NO_TITLE===';
if (  !empty(  $_POST['title']  )  )
{
	$title_post = $_POST['title'];
}
$time_post = 6;
if (  !empty(  $_POST['time']  )  )
{
	$time_post = $_POST['time'];
}
$data = '';
if (  !empty(  $_POST['data']  )  )
{
	$data = $_POST['data'];
}

//-------------------------------------------------------------------------------------------------
// Mise à jour du temps passé
//-------------------------------------------------------------------------------------------------
$temps = add_time($title_post, $url_post, $time_post, $extradata);

print (  "@@@ " . $url_post . "\n@@@ " . $title_post . "\n@@@ " . $temps . "\n@@@ " . $extradata . "\n______ ERR=" . $ERRORCODE  . "\n{" . $READ_COUNT . " <<< " . $WRITE_COUNT . "}\n" );

//print( $lines );

/*
foreach($_POST as $param_name => $param_val) {$url_post += $param_name." => ".$param_val."\n";}
foreach ($_POST as $p) {$url_post += $p . "\n";}
*/

?>


