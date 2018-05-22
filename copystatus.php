<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

////////////////////////////Common head
	$cache_time=2;
	$OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	$view_title= "$MSG_STATUS";
	$user="";
if(isset($_GET['user']))
$user=$_GET['user'];
        
require_once("./include/my_func.inc.php");
if(isset($OJ_LANG)){
                require_once("./lang/$OJ_LANG.php");
        }
require_once("./include/const.inc.php");
 if (!isset($_SESSION['user_id'])){

	$view_errors= "<a href=newloginpage.php>$MSG_Login</a>";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
//	$_SESSION['user_id']="Guest";
}
if($OJ_TEMPLATE!="classic") 
	$judge_color=Array("btn btn-default","btn btn-info","btn btn-warning","btn btn-warning","btn btn-success","btn btn-danger","btn btn-danger","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-info");

$str2="";
$lock=false;
$lock_time=date("Y-m-d H:i:s",time());
if(isset($_GET['cid']))
{
    $sql="SELECT msg.num as pid,msg.solution_id as msid,solution.solution_id as csid,msg.user_id as muid,solution.user_id as cuid,msg.sim as msim FROM (SELECT * FROM (SELECT user_id,solution_id,num FROM solution WHERE contest_id =".intval($_GET['cid'])." ) solution left join `sim` on sim.s_id=solution.solution_id WHERE sim.s_id=solution.solution_id) msg left join `solution` on msg.sim_s_id=solution.solution_id WHERE msg.sim_s_id=solution.solution_id and msg.user_id<>solution.user_id ";
}
else{
$sql="SELECT prev.solution_id as prevsid,solution.solution_id as resid,prev.problem_id,prev.user_id as uid,solution.user_id as cid FROM (SELECT * FROM (SELECT * FROM solution WHERE user_id='".$user."') solution left join `sim` on solution.solution_id=sim.s_id WHERE solution.solution_id=sim.s_id ) prev left join `solution` on prev.sim_s_id=solution.solution_id and prev.user_id<>solution.user_id WHERE solution.user_id is not null";
}
$result=$database->query($sql)->fetchAll();
$contest_id="";
$user_array=[];
$user_id=[];
$cnt=0;
$sim_arr=[];
$num_cnt=0;
$re="";
if(isset($_GET['cid']))
{
    $re=$database->query("SELECT DISTINCT user_id FROM solution where contest_id = '".intval($_GET['cid'])."' and result=4 order by user_id")->fetchAll();
    $contest_id=intval($_GET['cid']);
foreach($re as $row)
{
    $user_id[$cnt++]=$row[0];
//    echo "<br>";
}
$user_id=array_unique($user_id);
$user_problem=[];
foreach($user_id as $value)
{
    $sqle=$database->select("solution","num",[
        "user_id"=>$value,
        "contest_id"=>$contest_id,
        "result"=>4,
        "ORDER"=>[
            "num"=>"ASC"
            ]
        ]);
        $user_problem[$value]=$sqle;
        foreach($sqle as $pnum)
        {
            $uid=$database->select("solution","solution_id",[
        "user_id"=>$value,
        "contest_id"=>$contest_id,
        "result"=>4,
        "num"=>$pnum,
        "ORDER"=>[
            "solution_id"=>"DESC"
            ]
        ]);
        $user_array[$value][$pnum]=$uid[0];
        //$user_problem[$value][$pnum]=$uid[0];
        }
        //$user_array[$value]=$sqle;
}




foreach($user_id as $id)
{
    foreach($user_problem[$id] as $num){
      //  echo $user_array[$id][$num]."<br>";
        //echo $id."     ".$num."       <br>";
      //  echo var_dump($user_array[$id])."<br>";
    $sim_ans=$database->select("sim","s_id",[
        "s_id"=>$user_array[$id][$num]
        ]);
        if(count($sim_ans)!=0)
        {
            $sim_arr[$id]->id=$id;
            if(isset($sim_arr[$id]->problem))
            {
                array_push($sim_arr[$id]->problem,$num);
                $sim_arr[$id]->problem=array_unique($sim_arr[$id]->problem);
            }
            else
            {
                $sim_arr[$id]->problem=[];
                array_push($sim_arr[$id]->problem,$num);
            }
        }
       // echo var_dump($sim_ans);
    }
}




}

//echo var_dump($result);
if(isset($_GET['cid']))
{
    if(file_exists("template/".$OJ_TEMPLATE."/copyconteststatus.php"))
    require("template/".$OJ_TEMPLATE."/copyconteststatus.php");
}
else
if(file_exists("template/".$OJ_TEMPLATE."/copystatus.php"))
    require("template/".$OJ_TEMPLATE."/copystatus.php");

?>