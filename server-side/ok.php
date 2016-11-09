<?php

class LearningPeriod
{
	public $url = '___no_url___';
	public $title = '___no_title___';
	public $time = 0;

	public function LearningPeriod($ur, $tit, $tim)
	{
		$this->url = $ur;
		$this->title = $tit;
		$this->time = $tim;
	}
}

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
$time_post = 10;
if (  !empty(  $_POST['time']  )  )
{
	$time_post = $_POST['time'];
}

//-------------------------------------------------------------------------------------------------
// Lecture du fichier texte de tous les temps
//-------------------------------------------------------------------------------------------------
$filename ="urls.txt";
$urls = array();

if(file_exists($filename))
{
	$content = file_get_contents($filename);
	$lines = explode("\n", $content);
	$count = 0;
	foreach($lines as $line)
	{
		$arr = explode(">",$line);
		if(sizeof($arr)==3)
		{
			//$urls[$arr[0]]= new LearningPeriod( $arr[0], $arr[1], $arr[2] );
			$urls[$arr[0]] = array( 'url'=>$arr[0], 
								    'title'=>$arr[1], 
								    'time'=>$arr[2] );
			/*
			$new_elt = new LearningPeriod( $arr[0], $arr[1], $arr[2] );
			$urls[$arr[0]] = $new_elt;
			*/
			
			/*
			$urls[$arr[0]] = array();
			$urls[$arr[0]] = array( $arr[1], $arr[2] );
			*/
		}
		/*
		else
		{
			$urls[$arr[0]]=0;
		}
		*/
	}
}

//-------------------------------------------------------------------------------------------------
// Mise à jour du temps passé
//-------------------------------------------------------------------------------------------------
$temps=0;
if(!empty($urls[$url_post]))
{
	$temps = $urls[$url_post]['time'];
}
$temps = ($temps + $time_post); // SECONDS
//else
//{
$urls[$url_post] = array( 'url'=>$url_post, 
						  'title'=>$title_post, 
						  'time'=>$temps );
/*}

$urls[$url_post]->time = $temps;
*/

//-------------------------------------------------------------------------------------------------
// écriture dans le fichier texte de tous les temps
//-------------------------------------------------------------------------------------------------
$lines='';
foreach($urls as $elt=>$val)
{
	//if( !empty($url) )		$lines = $lines . ( $url . '>' . $val[0] . '>' . $val[1] . "\n" );
	
	$lines = $lines . ( $val['url'] . '>' .
	                    $val['title'] . '>' . 
	                    $val['time'] . "\n" );
}

//=================================================================================================

$errorcode = file_put_contents( $filename, $lines ); // $lines
print ( $url_post . ">\n" . $title_post . ">\n" . $temps . ">\n" . ':: err=' . $errorcode  );
//print( $lines );

/*
foreach($_POST as $param_name => $param_val) {$url_post += $param_name." => ".$param_val."\n";}
foreach ($_POST as $p) {$url_post += $p . "\n";}
*/

?>


