<?php
        $OJ_CACHE_SHARE=true;
        $cache_time=10;
        require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
        require_once('./include/setlang.php');
        $view_title= $MSG_CONTEST.$MSG_RANKLIST;
        $title="";
        require_once("./include/const.inc.php");
        require_once("./include/my_func.inc.php");
class TM{
        var $solved=0;
        var $time=0;
        var $p_wa_num;
        var $p_ac_sec;
        var $user_id;
        var $nick;
        function TM(){
                $this->solved=0;
                $this->time=0;
                $this->p_wa_num=array(0);
                $this->p_ac_sec=array(0);
        }
        function Add($pid,$sec,$res){
//              echo "Add $pid $sec $res<br>";
                if (isset($this->p_ac_sec[$pid])&&$this->p_ac_sec[$pid]>0)
                        return;
                if ($res!=4){
                        if(isset($this->p_wa_num[$pid])){
                                $this->p_wa_num[$pid]++;
                        }else{
                                $this->p_wa_num[$pid]=1;
                        }
                }else{
                        $this->p_ac_sec[$pid]=1;
                        $this->solved++;
                        if(!isset($this->p_wa_num[$pid])) $this->p_wa_num[$pid]=0;
                        $this->time+=$sec+$this->p_wa_num[$pid]*1200;
//                      echo "Time:".$this->time."<br>";
//                      echo "Solved:".$this->solved."<br>";
                }
        }
}

function s_cmp($A,$B){
//      echo "Cmp....<br>";
        if ($A->solved!=$B->solved) return $A->solved<$B->solved;
        else return $A->time>$B->time;
}
function n_cmp($A,$B)
{
    return strcmp($A['user_id'],$B['user_id']);
}
if (!isset($_GET['tid'])) die("No Such Contest!");
$tid=intval($_GET['tid']);
$is_vjudge=$database->select("special_subject","vjudge",["topic_id"=>$tid]);
//echo var_dump($is_vjudge);
$is_vjudge=intval($is_vjudge[0])==1;
// contest start time


$sql="SELECT `title` FROM `special_subject` WHERE `topic_id`=$tid";
//$result=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
//$rows_cnt=mysqli_num_rows($result);

$result=$database->select("special_subject","title",[
    "topic_id"=>$tid
    ]);
     //   $result = mysqli_query($mysqli,$sql);// or die("Error! ".mysqli_error($mysqli));
        if($result) $rows_cnt=count($result);
        else $rows_cnt=0;



$start_time=0;
$end_time=0;
if ($rows_cnt>0){
//      $row=mysqli_fetch_array($result);

      //  if($OJ_MEMCACHE)
              //  $row=$result[0];
      //  else
                //$row=mysqli_fetch_array($result);
        $title=$result[0];
}
if(!$OJ_MEMCACHE)mysqli_free_result($result);
//echo $lock.'-'.date("Y-m-d H:i:s",$lock);


$sql="SELECT count(1) as pbc FROM `special_subject_problem` WHERE `topic_id`='$tid'";
//$result=mysqli_query($mysqli,$sql);


        $result = mysqli_query($mysqli,$sql);// or die("Error! ".mysqli_error($mysqli));
        if($result) $rows_cnt=mysqli_num_rows($result);
        else $rows_cnt=0;


        $row=mysqli_fetch_array($result);

//$row=mysqli_fetch_array($result);
$pid_cnt=intval($row['pbc']);
if(!$OJ_MEMCACHE)mysqli_free_result($result);
$vjudge_record=[];
if($is_vjudge)
{
    $sql="SELECT
        users.user_id,users.nick,special_subject_problem.num,solution.result
                FROM
                        (SELECT * FROM `vjudge_solution` WHERE problem_id in(SELECT problem_id FROM special_subject_problem where topic_id=$tid) and result=4) solution
                left join users
                on users.user_id=solution.user_id 
left join special_subject_problem on special_subject_problem.problem_id=solution.problem_id
        ORDER BY users.user_id;";
        $vjsql="SELECT
        users.user_id,users.nick,special_subject_problem.num
                FROM
                        (SELECT * FROM `vjudge_record` WHERE problem_id in(SELECT problem_id FROM special_subject_problem where topic_id=$tid)
                         and oj_name in(SELECT oj_name FROM special_subject_problem where topic_id=$tid) 
) solution
                left join users
                on users.user_id=solution.user_id 
left join special_subject_problem on special_subject_problem.problem_id=solution.problem_id
        ORDER BY users.user_id;";
        $vjudge_record=$database->query($vjsql)->fetchAll();
}
else
$sql="SELECT
users.user_id,users.nick,solution.result,solution.in_date,solution.problem_id,special_subject_problem.num
FROM
(SELECT * FROM `solution` WHERE problem_id in(SELECT problem_id FROM special_subject_problem where topic_id=$tid) and problem_id>0  and result=4) solution
left join users
on users.user_id=solution.user_id
left join special_subject_problem on special_subject_problem.topic_id=$tid and special_subject_problem.problem_id=solution.problem_id
ORDER BY users.user_id,in_date
";
//echo $sql;
//$result=mysqli_query($mysqli,$sql);


        $result = mysqli_query($mysqli,$sql);// or die("Error! ".mysqli_error($mysqli));
        $result=$database->query($sql)->fetchAll();
        $rows_cnt=count($result);
     //   if($result) $rows_cnt=mysqli_num_rows($result);
   //     else $rows_cnt=0;

if($is_vjudge)
{
    foreach($vjudge_record as $row)
    {
        $row['result']="4";
        array_push($result,$row);
    }
}
//echo print_r($result);
$user_cnt=0;
$user_name='';
uasort($result,"n_cmp");
$U=array();
//$U[$user_cnt]=new TM();
foreach($result as $row)
{
   // echo $user_name."<br>";
    $n_user=$row['user_id'];
        if (strcmp($user_name,$n_user)){
                $user_cnt++;
                $U[$user_cnt]=new TM();

                $U[$user_cnt]->user_id=$row['user_id'];
                $U[$user_cnt]->nick=$row['nick'];

                $user_name=$n_user;
        }
       // if($user_name=="2016011254")echo print_r($row);
       // echo var_dump($row);
        	   $U[$user_cnt]->Add($row['num'],0,intval($row['result']));
}
//if(!$OJ_MEMCACHE) mysqli_free_result($result);
usort($U,"s_cmp");

////firstblood
$first_blood=array();
for($i=0;$i<$pid_cnt;$i++){
      $first_blood[$i]="";
}

$sql="select num,user_id from
        (select num,user_id from solution where topic_id=$tid and result=4 order by solution_id ) special_subject
        group by num";


        $fb = mysqli_query($mysqli,$sql);// or die("Error! ".mysqli_error($mysqli));
        if($fb) $rows_cnt=mysqli_num_rows($fb);
        else $rows_cnt=0;


for ($i=0;$i<$rows_cnt;$i++){

                $row=mysqli_fetch_array($fb);
         $first_blood[$row['num']]=$row['user_id'];
}



/////////////////////////Template
require("template/".$OJ_TEMPLATE."/specialsubjectrank.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
        require_once('./include/cache_end.php');
?>
