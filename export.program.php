<?php
ini_set("display_errors","On");
		ob_start();
		header ( "content-type:   application/excel" );
		header ( "content-disposition:   attachment;   filename=报名表.xls" );
?>
<?php require_once("./include/db_info.inc.php");
if(!isset($_SESSION['administrator']))
{
    exit(1);
}
if(isset($OJ_LANG)){
		require_once("./lang/$OJ_LANG.php");
}
require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");
function convert($val)
{
    return $val;
}

function bumen($val)
{
    if($val=="1")return "C语言组";
    else if($val=="2") return "JAVA语言组";
}

function csex($val)
{
    if($val=="0")return "男";
    else return "女";
}
function bornday($val)
{
    $result=explode("-",$val);
    return intval($result[0])."年".intval($result[1])."月";
}
//echo "<style> td{font-size:14} </style>";
//echo "<title>Contest RankList -- $title</title>";
$result=$database->select("cprogram","*");
echo "<center><h3>报名信息</h3></center>";
echo "<table border=1><tr><td>学号<td>姓名<td>性别<td>学院<td>专业<td>班级<td>指导老师<td>出生日期<td>QQ号<td>手机号<td>微信号<td>邮箱<td>组别";
echo "</tr>";
//	name	user_id	sex	scholar	subject	teacher	class	bornday	mobile_phone	qq	wechat	email	group
$user_cnt=count($result);
for ($i=0;$i<$user_cnt;$i++){
	if ($i&1) echo "<tr class=oddrow align=center>";
	else echo "<tr class=evenrow align=center>";
	echo "<td>".convert($result[$i]['user_id']);
	echo "<td>".convert($result[$i]['name']);
	echo "<td>".csex($result[$i]['sex'])."";
	echo "<td>".convert($result[$i]['scholar']);
	echo "<td>".$result[$i]["subject"];
	echo "<td>".$result[$i]["class"];
	echo "<td>".$result[$i]["teacher"];
	echo "<td>".bornday($result[$i]["bornday"]);
	echo "<td>".convert($result[$i]['qq']);
	echo "<td>".convert($result[$i]['mobile_phone']);
	echo "<td>".convert($result[$i]['wechat']);
	echo "<td>".convert($result[$i]['email']);
	echo "<td>".bumen($result[$i]['group']);
	echo "</tr>";
}
echo "</table>";

?>
