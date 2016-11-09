<?php

echo '<h1>YOUTUBE TEST</h1>';

$yt_url = 'https://www.youtube.com/watch?v=SMs0GnYze34&list=PLw-VjHDlEOgs6-KNB6I3xb6M2WfeH35mm';

$args = substr(strrchr($yt_url, '?'), 1 );

echo ' ARGS = ' . $args . ' <br>';

$args_arr= explode('&', $args);
$string='';
$index=0;
$video_id='';

foreach($args_arr as $arg )
{
	$string .= ' ____________________ ' . ($index++) . '#' . $arg . ' <br> ';
	
	// identifiant de la vidéo 
	if( stripos($arg, 'v=') === 0 ) // TRIPLE = !!!
	{
		$video_id = substr($arg, 2 );
	}
}


echo $string . "<p>";

echo '#___' . $video_id . '___#<p>';

//##########################################################################################

$video_ID = '_LuQFp1Lrfo'; // piper PIXAR short

$google_api_key = 'AIzaSyCQ5dINWi6zb9av4cn9bFBuET6bszD6DOI';

$video_with_key = 'https://www.googleapis.com/youtube/v3/videos?id=' . $video_ID . //
                  '&key=' . $google_api_key . '&part=snippet,statistics'; // snippet,contentDetails,statistics,status

$JSON = file_get_contents($video_with_key); 
 echo $JSON; // WORKS !!! 
 echo '<p>';
$JSON_Data = json_decode($JSON);
$json_stats = $JSON_Data->{'items'}[0]->{'statistics'};

$stats = 	' viewCount:' . $json_stats->{'viewCount'} .
			' likeCount:' . $json_stats->{'likeCount'} .
			' dislikeCount:' . $json_stats->{'dislikeCount'};

echo $stats;

echo '<p>';




//#################################################################################################

$dm_url = 'https://www.dailymotion.com/video/x32b8a2_taylor-swift-love-story_music';
	
// example https://www.dailymotion.com/video/x32b8a2_taylor-swift-love-story_music

//-------------------------------------------
// Extraction de l ID de la vidéo

$url_start = 'dailymotion.com/video/';
$pos_start = stripos(  $dm_url,  $url_start );
if( $pos_start === false)
	return '';
//
$pos_start += strlen( $url_start );

$pos_end = strpos(  $dm_url,  '_', $pos_start);
$dailymotion_id = substr( $dm_url, $pos_start, ( $pos_end - $pos_start) );

echo '<h1>DAILYMOTION TEST</h1>';	
	
echo '_________________' . $dailymotion_id . '_________________' ;

$dailymotion_data = 'https://api.dailymotion.com/video/' . $dailymotion_id . '?fields=tags,views_total';
echo '<p>';

$JSON = file_get_contents($dailymotion_data); 
echo $JSON; // WORKS !!! 

echo '<p>';



?>