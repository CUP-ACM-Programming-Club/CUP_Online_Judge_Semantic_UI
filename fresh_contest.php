<?php
$aContext = array(
    'http' => array(
        'proxy' => 'tcp://127.0.0.1:8118',
        'request_fulluri' => true,
    ),
);
$cxContext = stream_context_create($aContext);
$json = file_get_contents('http://contests.acmicpc.info/contests.json', false, $cxContext);
$myfile=fopen("recent_contest.json","w");
  fwrite($myfile,$json);
fclose($myfile);
?>