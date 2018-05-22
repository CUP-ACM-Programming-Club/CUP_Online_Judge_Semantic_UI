<?php
$cache_time=30;
$OJ_CACHE_SHARE=false;
        require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
        require_once('./include/setlang.php');
        $now=strftime("%Y-%m-%d %H:%M",time());
if (isset($_GET['cid'])) $ucid="&cid=".intval($_GET['cid']);
else $ucid="";
require_once("./include/db_info.inc.php");

        if(isset($OJ_LANG)){
                require_once("./lang/$OJ_LANG.php");
        }

$pr_flag=false;
$co_flag=false;
$condition=[];
$target=[];
$table_name="";
$langmask=$OJ_LANGMASK;
if (isset($_GET['id'])){
        // practice
        $id=intval($_GET['id']);
  //require("oj-header.php");
        if (!isset($_SESSION['administrator']) && $id!=1000&&!isset($_SESSION['contest_creator']))
        {
                $sql="SELECT * FROM `problem` WHERE `problem_id`=$id AND `defunct`='N' AND `problem_id` NOT IN (
                                SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN(
                                                SELECT `contest_id` FROM `contest` WHERE `end_time`>'$now' or `private`='1'))
                                ";
                                $table_name="problem";
                                $condition["problem_id"]=$id;
                                $condition["defunct"]="N";
                                $condition["problem_id[!]"]=$database->select("contest_problem","problem_id",[
                                    "contest_id"=>$database->select("contest","contest_id",[
                                        "OR"=>[
                                        "end_time[>]"=>$now,
                                        "private"=>"1"
                                        ]
                                        ])
                                    ]);
        }
        else{
                $sql="SELECT * FROM `problem` WHERE `problem_id`=$id";
                $table_name="problem";
                $target="*";
                $condition["problem_id"]=$id;
        }

        $pr_flag=true;
}else if (isset($_GET['cid']) && isset($_GET['pid'])){
        // contest
        $cid=intval($_GET['cid']);
        $pid=intval($_GET['pid']);
        
        if (!isset($_SESSION['administrator'])){
                $sql="SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=$cid AND `start_time`<='$now'";
                $target=["langmask","private","defunct"];
                $table_name="contest";
                $condition["defunct"]="N";
                $condition["contest_id"]=$cid;
                $condition["start_time[<=]"]=$now;
        }
        else{
                //$sql="SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=$cid";
                $target="contest";
                $table_name="contest";
                $condition["defunct"]="N";
                $condition["contest_id"]=$cid;
                $target=["langmask","private","defunct"];
        }
        $result=$database->select($table_name,$target,$condition);
       //$result=mysqli_query($mysqli,$sql);
        
        $rows_cnt=count($result);
        $row=$result[0];
        $contest_ok=true;
        if ($row[1] && !isset($_SESSION['c'.$cid])) $contest_ok=false;
        if ($row[2]=='Y') $contest_ok=false;
        if (isset($_SESSION['administrator'])) $contest_ok=true;
                               
       
    $ok_cnt=$rows_cnt==1;              
        $langmask=$row["langmask"];
       // $langmask=$OJ_LANGMASK;
        //mysqli_free_result($result);
        if ($ok_cnt!=1){
                // not started
                $view_errors=  "No such Contest!";
       
                require("template/".$OJ_TEMPLATE."/error.php");
                exit(0);
        }else{
                // started
                $sql="SELECT * FROM `problem` WHERE `defunct`='N' AND `problem_id`=(
                        SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=$cid AND `num`=$pid
                        )";
                        $target="*";
                        $table_name="problem";
                        $condition=[];
                        $condition["defunct"]="N";
                        $condition["problem_id"]=$database->select("contest_problem","problem_id",["contest_id"=>$cid,"num"=>$pid]);
        }
        // public
        if (!$contest_ok){
       
                $view_errors= "Not Invited!";
                require("template/".$OJ_TEMPLATE."/error.php");
                exit(0);
        }
        $co_flag=true;
}
else if(isset($_GET['tid']) && isset($_GET['pid']))
{
    //special_subject
    $tid=intval($_GET['tid']);
        $pid=intval($_GET['pid']);
        $sql="SELECT langmask,private,defunct FROM `special_subject` WHERE `defunct`='N' AND `topic_id`=$tid";
        $target=["langmask","private","defunct"];
        $table_name="special_subject";
        $condition=[];
        $condition["defunct"]="N";
        $condition["topic_id"]=$tid;
        //$result=mysqli_query($mysqli,$sql);
        $result=$database->select($table_name,$target,$condition);
        $rows_cnt=count($result);
        $row=$result[0];
        $contest_ok=true;
       // if ($row[1] && !isset($_SESSION['s'.$sid])) $contest_ok=false;
        //if ($row[2]=='Y') $contest_ok=false;
      //  if (isset($_SESSION['administrator'])) $contest_ok=true;
                               
       
    $ok_cnt=$rows_cnt==1;              
        $langmask=$row[0];
        mysqli_free_result($result);
        if ($ok_cnt!=1){
                // not started
                $view_errors=  "No such Contest!";
       
                require("template/".$OJ_TEMPLATE."/error.php");
                exit(0);
        }else{
                // started
                if(isset($_SESSION['administrator']))
                {
                    $sql="SELECT * FROM `problem` WHERE  `problem_id`=(
                        SELECT `problem_id` FROM `special_subject_problem` WHERE `topic_id`=$tid AND `num`=$pid
                        )";
                        $target="*";
                        $table_name="problem";
                        $condition=[];
                        $condition["problem_id"]=$database->select("special_subject_problem","problem_id",[
                            "topic_id"=>$tid,
                            "num"=>$pid
                            ]);
                }
                else
                {
                $sql="SELECT * FROM `problem` WHERE `defunct`='N' AND `problem_id`=(
                        SELECT `problem_id` FROM `special_subject_problem` WHERE `topic_id`=$tid AND `num`=$pid
                        )";
                        $target="*";
                        $table_name="problem_id";
                        $condition=[];
                        $condition["defunct"]="N";
                        $condition["problem_id"]=$database->select("special_subject_problem","problem_id",[
                            "topic_id"=>$tid,
                            "num"=>$pid
                            ]);
                }
        }
        // public
        if (!$contest_ok){
       
                $view_errors= "Not Invited!";
                require("template/".$OJ_TEMPLATE."/error.php");
                exit(0);
        }
        $co_flag=true;
}
else{
    echo "test";
        $view_errors=  "<title>$MSG_NO_SUCH_PROBLEM</title><h2>$MSG_NO_SUCH_PROBLEM</h2>";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
}
//$result=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
if(strlen($target)==0)$target="*";

$result=$database->select($table_name,$target,$condition);
       //echo var_dump($result);
if (count($result)!=1){
   $view_errors="";
   if(isset($_GET['id'])){
      $id=intval($_GET['id']);
      
           //mysqli_free_result($result);
           $sql="SELECT  contest.`contest_id` , contest.`title`,contest_problem.num FROM `contest_problem`,`contest` WHERE contest.contest_id=contest_problem.contest_id and `problem_id`=$id and defunct='N'  ORDER BY `num`";
           //echo $sql;
           $result=mysqli_query($mysqli,$sql);
          // $result=$database->query($sql)->fetchAll();
           if($i=mysqli_num_rows($result)){
              $view_errors.= "This problem is in Contest(s) below:<br>";
                   for (;$i>0;$i--){
                                $row=mysqli_fetch_row($result);
                                $view_errors.= "<a href=problem.php?cid=$row[0]&pid=$row[2]>Contest $row[0]:$row[1]</a><br>";
                               
                        }
                                 
                               
                }else{
                        $view_title= "<title>$MSG_NO_SUCH_PROBLEM!</title>";
                        $view_errors.= "<h2>$MSG_NO_SUCH_PROBLEM!</h2>";
                }
   }else{
                $view_title= "<title>$MSG_NO_SUCH_PROBLEM!</title>";
                $view_errors.= "<h2>$MSG_NO_SUCH_PROBLEM!</h2>";
        }
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
}else{
       // $row=mysqli_fetch_object($result);
       $row=$result[0];
        $view_title= $row['title'];
}
//mysqli_free_result($result);


/////////////////////////Template
require_once("template/".$OJ_TEMPLATE."/problem.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
   require_once('./include/cache_end.php');
?>

