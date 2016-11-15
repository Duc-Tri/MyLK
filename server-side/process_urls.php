<?php

include 'vars.php';

// $filename ="urls.txt";
$URLS_ARRAY = array();
$READ_COUNT = 0;
$WRITE_COUNT = 0;
$ERRORCODE = 0;
$CONTENT = '';
$SEPARATOR ='¤';

//-------------------------------------------------------------------------------------------------
// est ce que cet URL est autorisé ?
//-------------------------------------------------------------------------------------------------
function authorized_url($_u)
{
	return ! ( strripos($_u, 'dailymotion')===false  && //
	           strripos($_u, 'wikipedia')===false  && //
			   strripos($_u, 'youtube')===false );
}

//-------------------------------------------------------------------------------------------------
// renvoie le "type" d'URL
//-------------------------------------------------------------------------------------------------
function type($_url)
{
	if ( strripos($_url, 'dailymotion') !== false )
		return '[D]&nbsp;';
	elseif ( strripos($_url, 'wikipedia') !== false )
		return '[W]&nbsp;';
	elseif ( strripos($_url, 'youtube') !== false )
		return '[Y]&nbsp;';
	else
		return '[?]';
}

//-------------------------------------------------------------------------------------------------
// donne le temps enregistré 
//-------------------------------------------------------------------------------------------------
function get_time($_title, $_url)
{
	$temps = -1; // ERR
	
	if ( authorized_url($_url) )
	{
		global $URLS_ARRAY;
		
		read_urls();
		
		if(!empty($URLS_ARRAY[$_url]))
		{
			$temps = $URLS_ARRAY[$_url]['time'];
		}
	}
	return $temps;
}

//-------------------------------------------------------------------------------------------------
// rajoute du temps pour un site
//-------------------------------------------------------------------------------------------------
function add_time($_title, $_url, $_delay, $data)
{
	$temps = -1; // ERR
	
	if ( authorized_url($_url) )
	{
		global $URLS_ARRAY;
		
		read_urls();
		
		if(!empty($URLS_ARRAY[$_url]))
		{
			$temps = $URLS_ARRAY[$_url]['time'];
		}
		
		$temps = $temps + $_delay;
		$date = date('Y/m/d H:i:s');
		
		if($data=='')
		{
			$data="unauthorized site ???";
			
			if( strripos($_url, 'dailymotion') !== false )
				$data = processDailymotion($_url);
			elseif( strripos($_url, 'youtube') !== false )
				$data = processYoutube($_url);
			elseif( strripos($_url, 'wikipedia') !== false )
			{
				$data = processWikipedia($_url); 
				// $data='wikipedia';
			}
		}
		echo $data;
		$URLS_ARRAY[$_url] = array( 'url' => $_url, 
									'title' => $_title, 
									'time' => $temps,
									'last' => $date,
									'data' => $data  );
		write_urls();
	}
	return $temps;
}

//-------------------------------------------------------------------------------------------------
// Lecture du fichier texte de tous les temps
//-------------------------------------------------------------------------------------------------
function read_urls()
{
	global $URLS_ARRAY, $URLS_FILE, $CONTENT, $READ_COUNT, $SEPARATOR;

	if(file_exists($URLS_FILE))
	{
		$CONTENT = file_get_contents($URLS_FILE);
		$lines = explode("\n", $CONTENT);
		$READ_COUNT = 0;
		foreach($lines as $line)
		{
			$arr = explode($SEPARATOR, $line);
			if(sizeof($arr)==5)
			{
				$READ_COUNT++;
				$URLS_ARRAY[$arr[0]] = array( 	'url' => $arr[0], 
												'title' => $arr[1], 
												'data' => $arr[2],
												'time' => $arr[3],
												'last' => $arr[4]  );
			}
		}
	}
}

//-------------------------------------------------------------------------------------------------
// écriture dans le fichier texte de tous les temps
//-------------------------------------------------------------------------------------------------
function write_urls()
{
	global $URLS_FILE, $URLS_ARRAY, $ERRORCODE, $WRITE_COUNT, $SEPARATOR;

	$lines = '';
	$WRITE_COUNT = 0;
	foreach($URLS_ARRAY as $elt => $val)
	{
		$WRITE_COUNT++;
		//if( !empty($url) )		$lines = $lines . ( $url . '>' . $val[0] . '>' . $val[1] . "\n" );
		
		$lines = $lines . ( $val['url'] . $SEPARATOR . //
							$val['title'] . $SEPARATOR . //
							$val['data'] . $SEPARATOR . //
							$val['time'] . $SEPARATOR . //
							$val['last'] . "\n" );
	}

	$ERRORCODE = '['.$WRITE_COUNT.'] ' . file_put_contents( $URLS_FILE, $lines ); // $lines
}

//-------------------------------------------------------------------------------------------------
// renvoie le CONTENT formatté en tableau HTML
//-------------------------------------------------------------------------------------------------
function html_content()
{
	read_urls();
	global $CONTENT, $SEPARATOR;
	
	$lines_arr = explode("\n", $CONTENT);
	
	$html_str = '<table border=0><tr><th>TITLE</th><th>URL</th><th width="800px">DATA</th><th>TIME</th><th>LAST ACCESS</th></tr>';
	$count = 0;
	$color='';
	foreach($lines_arr as $line)
	{
		if( !empty($line) )
		{
			$items_array = explode($SEPARATOR, $line);
			if( sizeof($items_array)==5)
			{
				// temps format HH:MM:SS ----------------------------------------------------------
				$secondes = $items_array[3];
				
				$heures = (int)($secondes / 3600);
				if($heures<10) $heures = '0' . $heures;
				
				$minutes = (int)($secondes / 60) % 60;
				if($minutes<10) $minutes = '0' . $minutes;
				
				$secondes = $secondes % 60;
				if($secondes<10) $secondes = '0' . $secondes;
				
				// couleur de la ligne HTML -------------------------------------------------------
				$count++;
				$color= ($count%2===0) ? '#f0f0f0' : '#d0d0d0';
				
				// la ligne du tableau HTML -------------------------------------------------------
				$html_str = $html_str . '<tr bgcolor="' . $color . '"><td>' . $items_array[1] . '</td>' . // titre du site
				                        '<td>'. type($items_array[0]) .'<a href="' . $items_array[0] . '">link</a></td>' .           // url (lien)
				                        '<td>' . $items_array[2] . '</td>' .                              // extra data
										// '<td>' . $items_array[2] . '</td>' . // temps end secondes
										'<td>' . $heures . ':' . $minutes . ':' . $secondes . '</td>' .   // temps format HH:MM:SS
										'<td>' . $items_array[4] . '</td></tr>';                          // dernier accès
			}
		}
	}
	$html_str = $html_str . '</table>';
	
	return $html_str;
}

//-------------------------------------------------------------------------------------------------
// Retrieve data from a YOUTUBE URL
//-------------------------------------------------------------------------------------------------
function processYoutube($yt_url)
{
	//return '<youtube>';
	// example https://www.youtube.com/watch?v=SMs0GnYze34&list=PLw-VjHDlEOgs6-KNB6I3xb6M2WfeH35mm

	//-------------------------------------------
	// Extraction de l ID de la vidéo
	
	$args = substr(strrchr($yt_url, '?'), 1 );
	$args_arr= explode('&', $args);
	$video_id=''; //$video_ID = '_LuQFp1Lrfo'; // piper PIXAR short

	foreach($args_arr as $arg )
	{
		// identifiant de la vidéo 
		if( stripos($arg, 'v=') === 0 ) // TRIPLE = !!!
		{
			$video_id = substr($arg, 2 );
		}
	}
	
	if($video_id==='')
		return '<null>';

	//-------------------------------------------
	// Requête des champs
	$stats = 'stats';

	$google_api_key = 'AIzaSyCQ5dINWi6zb9av4cn9bFBuET6bszD6DOI';
	$video_with_key = 'https://www.googleapis.com/youtube/v3/videos?id=' . $video_id . //
					  '&key=' . $google_api_key . '&part=snippet,statistics'; // snippet,contentDetails,statistics,status
	$JSON = file_get_contents($video_with_key); // echo $JSON; // WORKS !!! 
	$JSON_Data = json_decode($JSON);
	$json_stats = $JSON_Data->{'items'}[0]->{'statistics'};
	$tags = $JSON_Data->{'items'}[0]->{'snippet'}->{'tags'};
	$stats = 	' <b>viewCount:</b>{' . $json_stats->{'viewCount'} . '}' . //
				' <b>likeCount:</b>{' . $json_stats->{'likeCount'} .'}' . //
				' <b>dislikeCount:</b>{' . $json_stats->{'dislikeCount'} . '}' . //
				' <b>tags:</b>{' . implode(', ', $tags) . '}';

	return $stats;
}

//-------------------------------------------------------------------------------------------------
// Retrieve data from a DAILYMOTION URL
// https://developer.dailymotion.com/api#api-reference
//-------------------------------------------------------------------------------------------------
function processDailymotion($dm_url)
{
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
	
	//-------------------------------------------
	// Requête des champs
	
	$dailymotion_data = 'https://api.dailymotion.com/video/' . $dailymotion_id . '?fields=tags,views_total,metadata_genre,comments_total';
	$JSON = file_get_contents($dailymotion_data); 
	$JSON_Data = json_decode($JSON);
	$tags = $JSON_Data->{'tags'};
	$genre = $JSON_Data->{'metadata_genre'};
	$comments_total = $JSON_Data->{'comments_total'};
	$stats = 	' <b>views_total:</b>{' . $JSON_Data->{'views_total'} . '}' . //
				' <b>tags:</b>{' . implode(', ', $tags) . '}' . // 
				' <b>genre:</b>{' . $genre . '}' . //
				' <b>comments_total:</b>{' . $comments_total . '}';

	return $stats;
}


//-------------------------------------------------------------------------------------------------
// Retrieve data from a WIKIPEDIA URL
// https://en.wikipedia.org/w/api.php
// https://www.mediawiki.org/wiki/API:Revisions
// example : https://fr.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&rvsection=0&titles=Charles_de_Gaulle
//-------------------------------------------------------------------------------------------------
function processWikipedia($wk_url)
{
	//-------------------------------------------
	// Extraction du nom de l'article

	$url_start = 'wikipedia.org/wiki/';
	$pos_start = stripos(  $wk_url,  $url_start );
	if( $pos_start === false)
		return '';
	//
	$pos_start += strlen( $url_start );
	$wiki_article = substr( $wk_url, $pos_start); // OK !!!
	
	//-------------------------------------------
	// Requête des champs
	
	$wiki_data = 'https://fr.wikipedia.org/w/api.php?action=query&format=json&prop=revisions&rvprop=content&indexpageids=true&rvsection=0&titles=' . $wiki_article;
	
	$JSON = file_get_contents($wiki_data); 
	$JSON_Data = json_decode($JSON);
	
	//return $wiki_data;
	//return $JSON_Data;
	//
	$pageid = $JSON_Data->{'query'}->{'pageids'}[0];
	//$pageid = $JSON_Data->{'query'}->{'pages'}->{0}->{'pageid'};
	
	$stats = ' <b>pageid </b>{' . $pageid . '}';

	return $stats;
}

?>