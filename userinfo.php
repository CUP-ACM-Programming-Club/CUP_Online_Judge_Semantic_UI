<?php
 $cache_time=10; 
 $OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	require_once('./closed.php');
	require_once("./include/const.inc.php");
	require_once("./include/my_func.inc.php");
 // check user
$user=$_GET['user'];
if (!is_valid_user_name($user)){
	echo "No such User!";
	exit(0);
}
$view_title=$user ."@".$OJ_NAME;
$user_mysql=mysqli_real_escape_string($mysqli,$user);
$sql="SELECT `school`,`email`,`nick` FROM `users` WHERE `user_id`='$user_mysql'";
$result=$database->select("users",[
    "school","email","nick","github","blog"
    ],
    [
        "user_id"=>$user_mysql
        ]);
//$result=mysqli_query($mysqli,$sql);
//$row_cnt=mysqli_num_rows($result);
$row_cnt=count($result);
if ($row_cnt==0){ 
	$view_errors= "No such User!";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}

$row=$result[0];
$school=$row['school'];
$email=$row['email'];
$nick=$row['nick'];
$blog = $row["blog"];
$github = $row["github"];
// count solved
//$sql="SELECT count(DISTINCT problem_id) as `ac` FROM `solution` WHERE `user_id`='".$user_mysql."' AND `result`=4";
$AC=count(array_unique($database->select("solution", "problem_id",[
    "user_id"=>$user_mysql,
    "result"=>4
    ])));
//mysqli_free_result($result);
// count submission
//$sql="SELECT count(solution_id) as `Submit` FROM `solution` WHERE `user_id`='".$user_mysql."' and  problem_id>0";
//$result=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
//$row=mysqli_fetch_object($result);
$Submit=count($database->select("solution", "solution_id",[
    "user_id"=>$user_mysql,
    "problem_id[>]"=>0
    ]));
//mysqli_free_result($result);
// update solved 
$database->update("users",[
    "solved"=>strval($AC),
    "submit"=>strval($Submit)
    ],
    [
        user_id=>$user_mysql
        ]
    );
//$sql="SELECT count(*) as `Rank` FROM `users` WHERE `solved`>$AC";
//$result=mysqli_query($mysqli,$sql);
$result=$database->count("users",[
    "solved[>]"=>$AC
    ]);
$Rank=intval($result)+1;

 if (isset($_SESSION['administrator'])){
$sql="SELECT * FROM `loginlog` WHERE `user_id`='$user_mysql' order by `time` desc LIMIT 0,10";
$result=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
$result=$database->select("loginlog","*",[
    "user_id"=>$user_mysql,
    "ORDER"=>[
        "time"=>"DESC"
        ],
        "LIMIT"=>[0,10]
    ]);
$view_userinfo=array();
$i=0;
foreach ($result as $row){
	$view_userinfo[$i]=$row;
	$i++;
}
echo "</table>";
//mysqli_free_result($result);
}
$sql="SELECT result,count(1) FROM solution WHERE `user_id`='$user_mysql'  AND result>=4 group by result order by result";
$aclangresult=$database->select("solution","language",["user_id"=>$user_mysql,"result"=>4]);
$aclang=[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
foreach($aclangresult as $v)
{
    $aclang[$v]++;
}
	//$result=mysqli_query($mysqli,$sql);
	$result=$database->query($sql)->fetchAll();
	$view_userstat=array();
	$i=0;
	foreach($result as $row){
		$view_userstat[$i++]=$row;
	}
	//mysqli_free_result($result);

$sql=	"SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where  `user_id`='$user_mysql'   group by md order by md desc ";
	$result=mysqli_query($mysqli,$sql);//mysql_escape_string($sql));
	$chart_data_all= array();
//echo $sql;
    
	while ($row=mysqli_fetch_array($result)){
		$chart_data_all[$row['md']]=$row['c'];
    }
    
$sql=	"SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where  `user_id`='$user_mysql' and result=4 group by md order by md desc ";
	//$result=mysqli_query($mysqli,$sql);//mysql_escape_string($sql));
	$chart_data_ac= array();
	$result=$database->query($sql)->fetchAll();
//echo $sql;
    
	foreach($result as $row){
		$chart_data_ac[$row['md']]=$row['c'];
    }
    $sql="SELECT prev.solution_id as prevsid,solution.solution_id as resid,prev.problem_id,prev.user_id as uid,solution.user_id as cid FROM (SELECT * FROM (SELECT * FROM solution WHERE user_id='".$user."') solution left join `sim` on solution.solution_id=sim.s_id WHERE solution.solution_id=sim.s_id ) prev left join `solution` on prev.sim_s_id=solution.solution_id and prev.user_id<>solution.user_id WHERE solution.user_id is not null";
$result=$database->query($sql)->fetchAll();
$copy_cnt=count($result);
 $privilege=$database->count("privilege",["user_id"=>$user,"rightstr"=>"administrator"]);
 $privilege=intval($privilege)>0;
  //mysqli_free_result($result);
    if(!isset($_GET['old']))
    {
        $sql=	"SELECT date(in_date) md,count(1) c FROM `solution` where  `user_id`='$user_mysql' and in_date>'".date("Y-m-d",strtotime("- 3 month",time()))."'   group by md order by md desc ";
	$result=mysqli_query($mysqli,$sql);//mysql_escape_string($sql));
	$chart_data_all= array();
//echo $sql;
    
	while ($row=mysqli_fetch_array($result)){
		$chart_data_all[$row['md']]=$row['c'];
    }
    $sql=	"SELECT date(in_date) md,count(1) c FROM `vjudge_solution` where  `user_id`='$user_mysql' and in_date>'".date("Y-m-d",strtotime("- 3 month",time()))."'   group by md order by md desc ";
	$result=mysqli_query($mysqli,$sql);//mysql_escape_string($sql));
//echo $sql;
    
	while ($row=mysqli_fetch_array($result)){
	    if(isset($chart_data_all[$row['md']]))
		$chart_data_all[$row['md']]+=$row['c'];
		else
		$chart_data_all[$row['md']]=$row['c'];
    }
    mysqli_free_result($result);
$sql=	"SELECT date(in_date) md,count(1) c FROM `solution` where  `user_id`='$user_mysql' and in_date>'".date("Y-m-d",strtotime("- 3 month",time()))."' and result=4 group by md order by md desc ";
	//$result=mysqli_query($mysqli,$sql);//mysql_escape_string($sql));
	$chart_data_ac= array();
	$result=$database->query($sql)->fetchAll();
//echo $sql;
    
	foreach($result as $row){
		$chart_data_ac[$row['md']]=$row['c'];
    }
    $sql=	"SELECT date(in_date) md,count(1) c FROM `vjudge_solution` where  `user_id`='$user_mysql' and in_date>'".date("Y-m-d",strtotime("- 3 month",time()))."' and result=4 group by md order by md desc ";
    $result=$database->query($sql)->fetchAll();
    foreach($result as $row){
        if(isset($chart_data_ac[$row['md']]))
		$chart_data_ac[$row['md']]+=$row['c'];
		else
		$chart_data_ac[$row['md']]=$row['c'];
    }
    }
$arr=[];
foreach($chart_data_ac as $k=> $v)
    {
        $arr[date("Y年m月d日",strtotime($k))][1]+=intval($v);
    }
    foreach($chart_data_all as $k=> $v)
    {
        $arr[date("Y年m月d日",strtotime($k))][0]+=intval($v);
    }
    foreach($arr as $k=>$v)
     {
        if(!isset($v[0])||!isset($v[1]))
        {
            unset($arr[$k]);
        }
    }
/////////////////////////Template
if(isset($_GET['old'])||$OJ_TEMPLATE!="semantic-ui")
require("template/".$OJ_TEMPLATE."/userinfo.php");
else{
    require("template/$OJ_TEMPLATE/newuserinfo.php");
}
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>

