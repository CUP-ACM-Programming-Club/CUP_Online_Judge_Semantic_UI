<?php
////////////////////////////Common head
	$cache_time=10;
	$OJ_CACHE_SHARE=false;
	$HOMEPAGE=true;
//	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	require_once('./closed.php');
	$view_title= "Welcome To Online Judge";
	
///////////////////////////MAIN	
if($OJ_TEMPLATE!="semantic-ui")
{
	$view_news="";
	$sql=	"SELECT * "
			."FROM `news` "
			."WHERE `defunct`!='Y'"
			."ORDER BY `importance` ASC,`time` DESC "
			."LIMIT 50";
	$result=mysqli_query($mysqli,$sql);//mysql_escape_string($sql));
	if (!$result){
		$view_news= "<h3>No News Now!</h3>";
		$view_news.= mysqli_error($mysqli);
	}else{
		$view_news.= "<div style=\"position:relative;margin-top:400px;text-align:right;\"><table style=\"text-align:right;width:100%\">";
		
		while ($row=mysqli_fetch_object($result)){
			$view_news.= "<tr><td><td><big><b>".$row->title."</b></big>-<small>[".$row->user_id."]</small></tr>";
			$view_news.= "<tr><td><td>".$row->content."</tr>";
		}
		mysqli_free_result($result);
		$view_news.= "<tr><td width=20%><td>本<a href=http://cm.baylor.edu/welcome.icpc>ACM/ICPC</a>在线评测系统遵循<a href='gpl-2.0.txt' target='_blank'>GPL协议</a> 评测机基于<a href=https://github.com/zhblue/hustoj>HUSTOJ</a>内核</br><span class=\"fui-window\"></span>Theme  <a href=\"http://designmodo.github.io/Flat-UI/\" target=\"_blank\">Flat-UI</a> &&<a href='https://semantic-ui.com' target='_blank'> Semantic-UI</a> Author : <a href=\"https://www.haoyuan.info\" target=\"_blank\">Ryan Lee</a>
		    <br><a href='opensource.php' >开放源代码许可 Open Source License</a><div id=footer class=center ></div>
		</tr>";
		$view_news.= "</table></div>";
	}
$view_apc_info="";

$sql=	"SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution`  group by md order by md desc limit 100";
	$result=mysqli_query($mysqli,$sql);//mysql_escape_string($sql));
	$chart_data_all= array();
//echo $sql;
    
	while ($row=mysqli_fetch_array($result)){
		$chart_data_all[$row['md']]=$row['c'];
    }
    
$sql=	"SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where result=4 group by md order by md desc limit 100";
	$result=mysqli_query($mysqli,$sql);//mysql_escape_string($sql));
	$chart_data_ac= array();
//echo $sql;
    
	while ($row=mysqli_fetch_array($result)){
		$chart_data_ac[$row['md']]=$row['c'];
    }

$sql=	"SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution`  group by md order by md desc limit 1";
    $result=mysqli_query($mysqli,$sql);
    $today_all=(mysqli_fetch_array($result))['c'];
    
    $sql=	"SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where result=4 group by md order by md desc limit 1";
	$result=mysqli_query($mysqli,$sql);//mysql_escape_string($sql));
	$today_ac=(mysqli_fetch_array($result))['c'];


if(function_exists('apc_cache_info')){
	 $_apc_cache_info = apc_cache_info(); 
		$view_apc_info =_apc_cache_info;
}
if(isset($_GET['debug']))
{
    $sql=	"SELECT date(in_date) md,count(1) c FROM `solution` where result=4 group by md order by md desc limit 100";
	$result=mysqli_query($mysqli,$sql);//mysql_escape_string($sql));
	$chart_data_ac= array();
//echo $sql;
	while ($row=mysqli_fetch_array($result)){
		$chart_data_ac[$row['md']]=$row['c'];
    }
    $arr=[];
    foreach($chart_data_ac as $k=> $v)
    {
        echo date("Y-m",strtotime($k))."=>".$v."<br>";
        $arr[date("Y-m",strtotime($k))]+=intval($v);
      //  echo $v;
        //echo date("Y-m-d",$chart_data_all[$v]);
        //echo var_dump($v);
    }
    echo var_dump($arr);
    exit(0);
}
}
/////////////////////////Template
require("template/".$OJ_TEMPLATE."/index.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>
