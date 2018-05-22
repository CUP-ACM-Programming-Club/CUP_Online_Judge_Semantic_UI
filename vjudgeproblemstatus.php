<?php
$cache_time=10;
$OJ_CACHE_SHARE=false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title= "Welcome To Online Judge";
require_once("./include/const.inc.php");

$id=strval(intval($_GET['pid']));
if (isset($_GET['page']))
    $page=strval(intval($_GET['page']));
else $page=0;

?>

<?php
$view_problem=array();
$oj=$_GET['oj'];
$oj=substr($oj,0,3);
// total submit
$sql="SELECT count(*) FROM vjudge_solution WHERE problem_id='$id' and oj_name='$oj'";
$result=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
$row=mysqli_fetch_array($result);
$view_problem[0][0]=$MSG_SUBMIT;
$view_problem[0][1]=$row[0];
$total=intval($row[0]);
mysqli_free_result($result);

// total users
$sql="SELECT count(DISTINCT user_id) FROM vjudge_solution WHERE problem_id='$id' and oj_name='$oj'";
$result=mysqli_query($mysqli,$sql);
$row=mysqli_fetch_array($result);

$view_problem[1][0]="$MSG_USER($MSG_SUBMIT)";
$view_problem[1][1]=$row[0];
mysqli_free_result($result);

// ac users
$sql="SELECT count(DISTINCT user_id) FROM vjudge_solution WHERE problem_id='$id' and oj_name='$oj' AND result='4'";
$result=mysqli_query($mysqli,$sql);
$row=mysqli_fetch_array($result);
$acuser=intval($row[0]);

$view_problem[2][0]="$MSG_USER($MSG_SOVLED)";
$view_problem[2][1]=$row[0];
mysqli_free_result($result);

//for ($i=4;$i<12;$i++){
$i=3;
$sql="SELECT result,count(1) FROM vjudge_solution WHERE problem_id='$id' and oj_name='$oj' AND result>=4 group by result order by result";
$result=mysqli_query($mysqli,$sql);
while($row=mysqli_fetch_array($result)){
    if(intval($row[0]<14)){
$view_problem[$i][0] ="<a href=hdu_status.php?problem_id=$id&jresult=".$row[0]." >".$jresult[$row[0]]."</a>";
                $view_problem[$i][1] =$row[1];
                $i++;
    }
}
mysqli_free_result($result);

//}

?>


<?php $pagemin=0; $pagemax=intval(($acuser-1)/20);

if ($page<$pagemin) $page=$pagemin;
if ($page>$pagemax) $page=$pagemax;
$start=$page*20;
$sz=20;
if ($start+$sz>$acuser) $sz=$acuser-$start;



// check whether the problem in a contest

// check whether the problem is ACed by user
$AC=false;
if ((isset($OJ_AUTO_SHARE)&&$OJ_AUTO_SHARE&&isset($_SESSION['user_id']))||isset($_SESSION['administrator'])){
    $sql="SELECT 1 FROM vjudge_solution where
                        result=4 and problem_id=$id and user_id='".$_SESSION['user_id']."'";
    $rrs=mysqli_query($mysqli,$sql);
    $AC=(intval(mysqli_num_rows($rrs))>0);
    mysqli_free_result($rrs);
}

$sql=" select * from
(

SELECT count(*) att ,user_id, min(10000000000000000000+time *100000000000 + memory *100000 + code_length ) score
FROM vjudge_solution
WHERE problem_id =$id
AND result =4
 and oj_name='$oj'
GROUP BY user_id
ORDER BY score, in_date

)c
left join
(
SELECT solution_id ,user_id,language,oj_name,10000000000000000000+time *100000000000 + memory *100000 + code_length  score, in_date
FROM vjudge_solution
WHERE problem_id =$id
 and oj_name='$oj'
AND result =4

ORDER BY score, in_date
)b
on b.user_id=c.user_id and b.score=c.score
order by c.score,in_date
 limit $start,100";

$result=mysqli_query($mysqli,$sql);
$result = $database->query($sql)->fetchAll();
$view_solution=array();
$j=0;
$last_user_id='';
$cnt = 0;
for ($i=$start+1;$row=$result[$cnt++];$i++){

    if($row["user_id"]==$last_user_id) continue;
    $sscore=strval($row["score"]);
    $s_time=intval(substr($sscore,1,8));
    $s_memory=intval(substr($sscore,9,6));
    $s_cl=intval(substr($sscore,15,5));
    $memory=["KB","MB","GB"];
    $ctime=["ms","s","min"];
    $clength=["B","KB","MB"];
    $temp=intval($s_memory);
    $memory_cnt=0;
    while($temp/1024>=1)
    {
        $memory_cnt++;
        $temp/=1024;
    }
    $s_t=intval($s_time);
    $t_cnt=0;
    while($s_t/1000>=1)
    {
        $t_cnt++;
        $s_t/=1000;
    }
    $s_l=intval($s_cl);
    $l_cnt=0;
    while($s_l/1024>=1)
    {
        $l_cnt++;
        $s_l/=1024;
    }
    $view_solution[$j][0]= $j+1;
    $view_solution[$j][1]=  $row["solution_id"];
    if (intval($row["att"])>1) $view_solution[$j][1].=  "(".$row["att"].")";
    $view_solution[$j][2]=  "<a href='userinfo.php?user=".$row["user_id"]."'>".$row["user_id"]."</a>";
    $view_solution[$j][3]=  number_format($temp,2).$memory[$memory_cnt];

    $view_solution[$j][4]=  number_format($s_t,$t_cnt?2:0).$ctime[$t_cnt];

    if (!(isset($_SESSION['user_id'])&&!strcasecmp($row["user_id"],$_SESSION['user_id']) ||
        isset($_SESSION['source_browser'])||
        (isset($OJ_AUTO_SHARE)&&$OJ_AUTO_SHARE&&$AC)||isset($_SESSION['administrator']))){
        $view_solution[$j][5]= $vjudge_language_name[strtolower($row["oj_name"])][$row["language"]];
    }else{
        $view_solution[$j][5]=  "<a target=_blank href=showsource.php?hid=".$row["solution_id"].">".$vjudge_language_name[strtolower($row["oj_name"])][$row["language"]]."</a>";
    }
    $view_solution[$j][6]=  number_format($s_l,$l_cnt?2:0)." ".$clength[$l_cnt];
    $view_solution[$j][7]= $row["in_date"];
    $j++;
    $last_user_id=$row["user_id"];
}

$view_recommand=Array();
if(isset($_SESSION['user_id'])&&isset($_GET['id'])){
    $id=intval($_GET['pid']);
    $user_id=mysqli_real_escape_string($mysqli,$_SESSION['user_id']);
    $sql="select problem_id,count(1) people from  (
                                SELECT * FROM vjudge_solution ORDER BY solution_id DESC LIMIT 10000 )solution
                                 where
                                problem_id!=$id and result=4
                                and user_id in(select distinct user_id from vjudge_solution where result=4 and problem_id=$id  and oj_name='$oj' )
                                and problem_id not in (select distinct problem_id from vjudge_solution where user_id='$user_id' )
                                group by `problem_id` order by people desc limit 12";
    $result = $database->query($sql)->fetchAll();
    $i=0;
    foreach($result as $row)
    {
        $view_recommand[$i][0] = $row["problem_id"];
        ++$i;
    }
}

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/vjudgeproblemstatus.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>

