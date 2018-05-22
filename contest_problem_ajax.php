<?php
    require_once('./include/db_info.inc.php');
$contest_id="";
if(isset($_GET['cid']))
{
    $contest_id=intval($_GET['cid']);
}
if(strlen(strval($contest_id))<3||!isset($_SESSION['user_id']))
{
    $view_errors="非法操作";
    require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}
$acproblem=$database->select("contest_problem(problem)",[
	"[>]solution(sol)"=>[
		"result","user_id","problem_id","contest_id"
	],
	"[>]solution(sol)"=>[
		"problem.problem_id"=>"problem_id",
		"problem.contest_id"=>"contest_id"
	]
],	[
		"problem.problem_id(pid)","problem.num(num)"
	],
	[
	"AND"=>[
		"sol.result"=>4,
		"sol.user_id"=>$_SESSION['user_id'],
		"sol.solution_id[!]"=>null,
		"problem.contest_id"=>$contest_id
	],
	"GROUP"=>["problem.problem_id"]
	]
	);
	
	$totalproblem=$database->select("contest_problem",[
	    "problem_id(pid)","num"
	    ],[
	        "contest_id"=>$contest_id
	        ]);
	        
	  $rest=[];
	  $cnt=0;
	  foreach($totalproblem as $v)
	  {
	      $flag=true;
	      foreach($acproblem as $a)
	      {
	          if($a['pid']==$v['pid'])
	          {
	              $flag=false;
	              break;
	          }
	      }
	      if($flag)
	      {
	          $rest[$cnt++]=$v;
	      }
	  }
	  $obj=[];
	  $cnt=0;
	  foreach($rest as $r)
	  {
	      $title=$database->select("problem","title",["problem_id"=>$r['pid']]);
	      $submit=$database->count("solution",["problem_id"=>$r['pid'],"contest_id"=>$contest_id]);
	      $accept=$database->count("solution",["problem_id"=>$r['pid'],"contest_id"=>$contest_id,"result"=>4]);
	      $obj[$cnt]['num']=chr(ord('A')+$r['num']);
	      $obj[$cnt]['submit']=$submit;
	      $obj[$cnt]['accept']=$accept;
	      $obj[$cnt]['url']="newsubmitpage.php?cid=".$contest_id."&pid=".$r['num'];
	      $obj[$cnt++]['title']=$title[0];
	  }
	  echo json_encode($obj);
?>