<?php session_start();
if (!isset($_SESSION['user_id'])){
	require_once("oj-header.php");
	echo "<a href='loginpage.php'>$MSG_Login</a>";
	require_once("oj-footer.php");
	exit(0);
}
require_once("include/db_info.inc.php");
require_once("include/const.inc.php");
  $now=strftime("%Y-%m-%d %H:%M",time());
$user_id=$_SESSION['user_id'];
$tid="";
$json="";
$pid=-1;
$cid="";
$mode=-1;
$json_type="";
if(isset($_POST['json']))
{
    $json=json_decode($_POST['json'],true);
    $json_type=$json['type'];
    if($json['cid'])
    {
        $mode=1;
        $sql="SELECT `problem_id` from `contest_problem` 
				where `num`='$pid' and contest_id=$cid";
    }
    else if($json['tid'])
    {
        $mode=2;
        $pid=intval($json['pid']);
        $tid=intval($json['tid']);
        $sql="SELECT `problem_id` from `special_subject_problem` where `num`='$pid' and topic_id=$tid";
    }
    else
    {
        $mode=0;
        $id=intval($json['id']);
	$sql="SELECT `problem_id` from `problem` where `problem_id`='$id' and problem_id not in (select distinct problem_id from contest_problem where `contest_id` IN (
			SELECT `contest_id` FROM `contest` WHERE 
			(`end_time`>'$now' or private=1)and `defunct`='N'
			))";
	if(!isset($_SESSION['administrator']))
		$sql.=" and defunct='N'";
    }
}
else
{
    if (isset($_POST['cid'])){
	$pid=intval($_POST['pid']);
	$cid=intval($_POST['cid']);
	$sql="SELECT `problem_id` from `contest_problem` 
				where `num`='$pid' and contest_id=$cid";
}else if(isset($_POST['tid']))
{
    $pid=intval($_POST['pid']);
    $tid=intval($_POST['tid']);
    $sql="SELECT `problem_id` from `special_subject_problem` where `num`='$pid' and topic_id=$tid";
}
else{
	$id=intval($_POST['id']);
	$sql="SELECT `problem_id` from `problem` where `problem_id`='$id' and problem_id not in (select distinct problem_id from contest_problem where `contest_id` IN (
			SELECT `contest_id` FROM `contest` WHERE 
			(`end_time`>'$now' or private=1)and `defunct`='N'
			))";
	if(!isset($_SESSION['administrator']))
		$sql.=" and defunct='N'";
}
}
//echo $sql;	

$res=mysqli_query($mysqli,$sql);
if ($res&&mysqli_num_rows($res)<1&&!isset($_SESSION['administrator'])&&!((isset($cid)&&$cid<=0)||(isset($id)&&$id<=0))){
		mysqli_free_result($res);
		$view_errors=  "Where do find this link? No such problem.<br>";
		require("template/".$OJ_TEMPLATE."/error.php");
		exit(0);
}
mysqli_free_result($res);



$test_run=false;
//echo var_dump($json);
if (isset($_POST['id'])||$json_type=="problem") 
{
    if($json['id'])$id=intval($json['id']);
    else
	$id=intval($_POST['id']);
        $test_run=($id<=0);
}else if ((isset($_POST['pid']) && isset($_POST['cid'])&&$_POST['cid']!=0)||$json_type=="contest"){
    if(isset($_POST['pid'])){
	$pid=intval($_POST['pid']);
	$cid=intval($_POST['cid']);
    }
    else
    {
        $pid=intval($json['pid']);
        $cid=intval($json['cid']);
    }
        $test_run=($cid<0);
	if($test_run) $cid=-$cid;
	// check user if private
	$sql="SELECT `private` FROM `contest` WHERE `contest_id`='$cid' AND `start_time`<='$now' AND `end_time`>'$now'";
	$result=mysqli_query($mysqli,$sql);
	$rows_cnt=mysqli_num_rows($result);
	if ($rows_cnt!=1){
		echo "You Can't Submit Now Because Your are not invited by the contest or the contest is not running!!";
		mysqli_free_result($result);
		require_once("oj-footer.php");
		exit(0);
	}else{
		$row=mysqli_fetch_array($result);
		$isprivate=intval($row[0]);
		mysqli_free_result($result);
		if ($isprivate==1&&!isset($_SESSION['c'.$cid])){
			$sql="SELECT count(*) FROM `privilege` WHERE `user_id`='$user_id' AND `rightstr`='c$cid'";
			$result=mysqli_query($mysqli,$sql) or die (mysqli_error($mysqli)); 
			$row=mysqli_fetch_array($result);
			$ccnt=intval($row[0]);
			mysqli_free_result($result);
			if ($ccnt==0&&!isset($_SESSION['administrator'])){
				$view_errors= "You are not invited!\n";
				require("template/".$OJ_TEMPLATE."/error.php");
				exit(0);
			}
		}
	}
	$sql="SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`='$cid' AND `num`='$pid'";
	$result=mysqli_query($mysqli,$sql);
	$rows_cnt=mysqli_num_rows($result);
	if ($rows_cnt!=1){
		$view_errors= "No Such Problem!\n";
		require("template/".$OJ_TEMPLATE."/error.php");
		mysqli_free_result($result);
		exit(0);
	}else{
		$row=mysqli_fetch_object($result);
		$id=intval($row->problem_id);
		if($test_run) $id=-$id;
		mysqli_free_result($result);
	}
}
else if (isset($_POST['pid']) && isset($_POST['tid'])&&$_POST['tid']!=0||$json_type=="topic"){
    $pid;
    $tid;
   // echo var_dump($json);
    if(isset($json['pid']))
    {
        $pid=intval($json['pid']);
        $tid=intval($json['tid']);
    }
    else{
	$pid=intval($_POST['pid']);
	$tid=intval($_POST['tid']);
    }
        $test_run=($tid<0);
	if($test_run) $tid=-$tid;
	// check user if private
	$sql="SELECT `private` FROM `special_subject` WHERE `topic_id`='$tid'";
	//echo $sql;
	$result=mysqli_query($mysqli,$sql);
	$rows_cnt=mysqli_num_rows($result);
	if ($rows_cnt!=1){
		echo "You Can't Submit Now Because The Special Subject Does Not Exist!";
		mysqli_free_result($result);
		require_once("oj-footer.php");
		exit(0);
	}else{
		$row=mysqli_fetch_array($result);
		$isprivate=intval($row[0]);
		if($isprivate&&!$_SESSION['administrator'])
		{
		    echo "You Can't Submit Now Because The Special Subject is Private!";
	    	mysqli_free_result($result);
	    	require_once("oj-footer.php");
	    	exit(0);
		}
		mysqli_free_result($result);
	}
	$sql="SELECT `problem_id` FROM `special_subject_problem` WHERE `topic_id`='$tid' AND `num`='$pid'";
	$result=mysqli_query($mysqli,$sql);
	$rows_cnt=mysqli_num_rows($result);
	if ($rows_cnt!=1){
		$view_errors= "No Such Problem!\n";
		require("template/".$OJ_TEMPLATE."/error.php");
		mysqli_free_result($result);
		exit(0);
	}else{
		$row=mysqli_fetch_object($result);
		$id=intval($row->problem_id);
		if($test_run) $id=-$id;
		mysqli_free_result($result);
	}
}

else{
       $id=0;
/*
	$view_errors= "No Such Problem!\n";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
*/
       $test_run=true;
}
$language="";
if(isset($_POST['json']))
{
    $language=$json['language'];
}
else
$language=intval($_POST['language']);

if ($language>count($language_name) || $language<0) $language=0;
$language=strval($language);

$source="";
if(isset($_POST['json']))
{
    $source=$json['source'];    
}
else
$source=$_POST['source'];
if(false&&(preg_match('/start\/html/',$source)||preg_match('/dev\/random/',$source)))
$source="#include <stdio.h> \n int main(){}";
//echo "alert($source)";
$input_text="";
if(isset($_POST['json']))
{
    $input_text=$json['input_text'];
}
else
$input_text=$_POST['input_text'];
if(get_magic_quotes_gpc()){
	$source=stripslashes($source);
	$input_text=stripslashes($input_text);

}
$input_text=preg_replace ( "(\r\n)", "\n", $input_text );
$source=mysqli_real_escape_string($mysqli,$source);
$input_text=mysqli_real_escape_string($mysqli,$input_text);
$source_user=$source;
if($test_run) $id=-$id;
//use append Main code
$prepend_file="$OJ_DATA/$id/prepend.$language_ext[$language]";
if(isset($OJ_APPENDCODE)&&$OJ_APPENDCODE&&file_exists($prepend_file)){
     $source=mysqli_real_escape_string($mysqli,file_get_contents($prepend_file)."\n").$source;
}
$rprepend = $database->select("prefile","*",["problem_id"=>$id,"type"=>$language,"prepend"=>1]);
if(count($rprepend)>0)
{
    $source = $rprepend[0]["code"].$source;
}
$rappend = $database->select("prefile","*",["problem_id"=>$id,"type"=>$language,"prepend"=>0]);
if(count($rappend)>0)
{
    $source = $source.$rappend[0]["code"];
}
$append_file="$OJ_DATA/$id/append.$language_ext[$language]";
if(isset($OJ_APPENDCODE)&&$OJ_APPENDCODE&&file_exists($append_file)){
     $source.=mysqli_real_escape_string($mysqli,"\n".file_get_contents($append_file));
}
//end of append 
if($language==6||$language==18)
   $source="# coding=utf-8\n".$source;
//if($test_run) $id=0;

$len=strlen($source);
//echo $source;




setcookie('lastlang',$language,time()+360000);

$ip=$_SERVER['REMOTE_ADDR'];

if ($len<2){
	//$view_errors="Code too short!<br>";
	//require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}
if ($len>65536){
	$view_errors="Code too long!<br>";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}

// last submit
$now=strftime("%Y-%m-%d %X",time()-10);
$sql="SELECT `in_date` from `solution` where `user_id`='$user_id' and in_date>'$now' order by `in_date` desc limit 1";
$res=mysqli_query($mysqli,$sql);
/*if (mysqli_num_rows($res)==1){
	//$row=mysqli_fetch_row($res);
	//$last=strtotime($row[0]);
	//$cur=time();
	//if ($cur-$last<10){
		$view_errors="You should not submit more than twice in 10 seconds.....<br>";
		$view_errors.=var_dump($res);
		require("template/".$OJ_TEMPLATE."/error.php");
		exit(0);
	//}
}*/


if((~$OJ_LANGMASK)&(1<<$language)){
$store_id=0;
if($test_run)$id = -abs($id);
if(isset($_SESSION['store_id'])) $store_id=$_SESSION['store_id'];
	if ($pid==-1||$mode==0){
	$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length)
		VALUES('$id','$user_id',NOW(),'$language','$ip','$len')";
	}else if($tid!=""||$mode==2){
	$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length,topic_id,num)
		VALUES('$id','$user_id',NOW(),'$language','$ip','$len','$tid','$pid')";
	}
	else
	{
	    $sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length,contest_id,num)
		VALUES('$id','$user_id',NOW(),'$language','$ip','$len','$cid','$pid')";
	}
	mysqli_query($mysqli,$sql);
	//echo $sql;
	$insert_id=mysqli_insert_id($mysqli);
	if(isset($_POST['json']))
	{
	    echo $insert_id;
	    //echo var_dump($json);
	}
	$sql="INSERT INTO `source_code_user`(`solution_id`,`source`)VALUES('$insert_id','$source_user')";
	mysqli_query($mysqli,$sql);

	$sql="INSERT INTO `source_code`(`solution_id`,`source`)VALUES('$insert_id','$source')";
	mysqli_query($mysqli,$sql);

	if($test_run){
		$sql="INSERT INTO `custominput`(`solution_id`,`input_text`)VALUES('$insert_id','$input_text')";
		mysqli_query($mysqli,$sql);
	}
	//echo $sql;
	//using redis task queue
        if($OJ_REDIS){
           $redis = new Redis();
           $redis->connect($OJ_REDISSERVER, $OJ_REDISPORT);
           $redis->lpush($OJ_REDISQNAME,$insert_id);
        }

}


	 $statusURI=strstr($_SERVER['REQUEST_URI'],"submit",true)."status.php";
	 if (isset($cid)) 
	    $statusURI.="?cid=$cid";
	 else if(isset($tid))
	     $statusURI.="?tid=$tid";
	    
        $sid="";
        if (isset($_SESSION['user_id'])){
                $sid.=session_id().$_SERVER['REMOTE_ADDR'];
        }
        if (isset($_SERVER["REQUEST_URI"])){
                $sid.=$statusURI;
        }
   // echo $statusURI."<br>";
  
        $sid=md5($sid);
        $file = "cache/cache_$sid.html";
    //echo $file;  
    if($OJ_MEMCACHE){
		$mem = new Memcache;
                if($OJ_SAE)
                        $mem=memcache_init();
                else{
                        $mem->connect($OJ_MEMSERVER,  $OJ_MEMPORT);
                }
        $mem->delete($file,0);
    }
	else if(file_exists($file)) 
	     unlink($file);
    //echo $file;
    
  $statusURI="status.php?user_id=".$_SESSION['user_id'];
  if (isset($cid)&&intval($cid)!=0)
	    $statusURI.="&cid=$cid";
  else if(isset($tid)&&intval($tid)!=0)
          $statusURI.="&tid=$tid";
	 
   if(!$test_run&&!$json)	
	header("Location: $statusURI");
   else if(!$json){
   	if(isset($_GET['ajax'])){
                echo $insert_id;
        }else{
		?><script>window.parent.setTimeout("fresh_result('<?php echo $insert_id;?>')",1000);</script><?php
        }
   }
?>
