<?php
////////////////////////////Common head
	$cache_time=1200;
	$OJ_CACHE_SHARE=false;
  require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	require_once('./closed.php');
	require_once('./include/cache_start.php');
	$view_title= "Recent Contests from Naikai-contest-spider";
$myfile = fopen("recent_contest.json", "r");
$rows="";
$json="";
$aContext = array(
    'http' => array(
        'proxy' => 'tcp://127.0.0.1:8118',
        'request_fulluri' => true,
    ),
);
$cxContext = stream_context_create($aContext);
if($myfile)
{
    //echo fread($myfile,filesize("recent_contest.json"));
    $txt=file_get_contents("recent_contest.json");
    $json=$txt;
    $rows=json_decode($json, true);
}
else
{
$json = file_get_contents('http://contests.acmicpc.info/contests.json', False, $cxContext);
 // $json = @file_get_contents('http://contests.acmicpc.info/contests.json');
  $rows = json_decode($json, true);
  $myfile=fopen("recent_contest.json","w");
  fwrite($myfile,$json);
}
fclose($myfile);
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/recent-contest.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>



