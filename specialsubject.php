<?php

    require_once('./include/db_info.inc.php');
    require_once('./include/setlang.php');
    require_once('./closed.php');
	require_once('./include/my_func.inc.php');
	require_once('./include/const.inc.php');
	require_once('./include/cache_start.php');
	if(isset($_GET['tid']))
	{
	    $tid=intval($_GET['tid']);
	    $sql="SELECT * FROM `special_subject` WHERE `topic_id`=".$tid;
	    $result=$database->select("special_subject","*",[
	        "topic_id"=>$tid
	        ]);
	   // $result=mysqli_query($mysqli,$sql);
	    $row=$result[0];
	    $view_title=$row['title'];
	    $view_private=$row['private'];
	    $view_description=$row['description'];
	    $is_vjudge=$row['vjudge'];
	    $is_vjudge=intval($is_vjudge)==1;
	    if(!isset($_SESSION['administrator'])&&!isset($_SESSION['source_browser']))
	    {
	        if($view_private)
	        {
	            $view_errors= "该专题尚未开放！";
                require("template/".$OJ_TEMPLATE."/error.php");
                exit(0);
	        }
	    }
	    $langmask=$row['langmask'];
	    $sql="";
	    if($is_vjudge)
	    {
	    $sql="select * from (SELECT `vjudge_problem`.`title` as `title`,`vjudge_problem`.`problem_id` as `pid`,source as source,special_subject_problem.num as pnum

		FROM `special_subject_problem`,`vjudge_problem`

		WHERE `special_subject_problem`.`problem_id`=`vjudge_problem`.`problem_id` 

		AND `special_subject_problem`.`topic_id`=$tid AND `special_subject_problem`.`oj_name`=`vjudge_problem`.`source` ORDER BY `special_subject_problem`.`num` ) problem left join (select problem_id as pid1,count(1) as accepted from vjudge_solution where result=4 group by pid1) p1 on problem.pid=p1.pid1
                left join (select problem_id as pid2,count(1) as submit from vjudge_solution  group by pid2) p2 on problem.pid=p2.pid2
		order by pnum
             ";
	    }
	    else
	    {
	    $sql="select * from (SELECT `problem`.`title` as `title`,`problem`.`problem_id` as `pid`,source as source,special_subject_problem.num as pnum

		FROM `special_subject_problem`,`problem`

		WHERE `special_subject_problem`.`problem_id`=`problem`.`problem_id` 

		AND `special_subject_problem`.`topic_id`=$tid ORDER BY `special_subject_problem`.`num` ) problem
                left join (select problem_id pid1,count(1) accepted from solution where result=4 and ( 0=1 ";
                $temp_lang=$langmask;
                $cnt=0;
                while($temp_lang)
                {
                    if(!(1&$temp_lang))
                    {
                        $sql.="or language=".$cnt." ";
                    }
                    $cnt++;
                    $temp_lang>>=1;
                }
                $sql.=" ) group by pid1) p1 on problem.pid=p1.pid1
                left join (select problem_id pid2,count(1) submit from solution where ( 0=1 ";
                $temp_lang=$langmask;
                $cnt=0;
                while($temp_lang)
                {
                    if(!(1&$temp_lang))
                    {
                        $sql.="or language=".$cnt." ";
                    }
                    $cnt++;
                    $temp_lang>>=1;
                }
                $sql.=" ) group by pid2) p2 on problem.pid=p2.pid2
		order by pnum
;";
}
if(isset($_GET['debug']))
{
    echo $sql;
    exit(0);
}
$result=$database->query($sql)->fetchAll();
//$result=mysqli_query($mysqli,$sql);
			$view_problemset=Array();
			$cnt=0;
			foreach($result as $row){
			 //   echo var_dump($row);
				$view_problemset[$cnt][0]="";
				if (isset($_SESSION['user_id'])) 
				{
				    if($is_vjudge)
				    $view_problemset[$cnt][0]=check_vsac($tid,$cnt,$langmask);
				    else
					$view_problemset[$cnt][0]=check_sac($tid,$cnt,$langmask);
				}
				$view_problemset[$cnt][1]= $row['pid']." Problem &nbsp;".$PID[$cnt];
				if($is_vjudge)
				$view_problemset[$cnt][2]= "<a href='".strtolower($row['source'])."submitpage.php?tid=$tid&pid=$cnt' target='_blank'>".$row['title']."</a>";
				else
				$view_problemset[$cnt][2]= "<a href='newsubmitpage.php?tid=$tid&pid=$cnt'>".$row['title']."</a>";
				$view_problemset[$cnt][3]=$row['source'] ;
				$view_problemset[$cnt][4]=$row['accepted'] ;
				$view_problemset[$cnt][5]=$row['submit'] ;
				$cnt++;
			}
			if(isset($_GET['debug']))
			{
			    echo var_dump($view_problemset);
			}
		    if(isset($_GET['adebug']))
		    {
		        exit(0);
		    }
			mysqli_free_result($result);
	}
	else {
	    $sql="SELECT * FROM `special_subject` WHERE defunct='N' ORDER BY `topic_id` ASC limit 1000";
	    $result=$database->select("special_subject","*",[
	        "defunct"=>"N",
	        "ORDER"=>[
	            "topic_id"=>"ASC"
	            ],
	            "LIMIT"=>"1000"
	        ]);
		//	$result=mysqli_query($mysqli,$sql);
			$rows_cnt=count($result);
	}
	if(isset($_GET['debug']))
	{
	    exit(0);
	}
	if(!isset($_GET['tid'])){
	require("template/".$OJ_TEMPLATE."/specialsubject.php");
	}
	else
	{
	    require("template/".$OJ_TEMPLATE."/specialsubjectlist.php");
	}
?>