<?php
require_once('./include/const.inc.php');
require_once('./include/db_info.inc.php');
$prepend_file="$OJ_DATA/";
$append_file=$prepend_file;
$id="";
$cid="";
$pid="";
$file_name="";
if(isset($_GET['ajax']))
{   
    if(isset($_POST["id"])){
    $id=$_POST['id'];
    }
    if(isset($_POST["cid"])){
        $cid = intval($_POST["cid"]);
        $pid = intval($_POST["pid"]);
        $result=$database->select("contest_problem","*",["contest_id"=>$cid,"num"=>$pid]);
        $id=$result[0]["problem_id"];
    }
    if(isset($_GET['cid']))
    {
        $result=$database->select("contest_problem","*",["contest_id"=>$cid,"num"=>$pid]);
        $id=$result[0]["problem_id"];
    }
    $prepend_file.=$id."/";
    $append_file.=$id."/";
    $file_name=$_POST['file_name'];
    $prepend_file.="prepend.".$file_name;
    $append_file.="append.".$file_name;
}
if(isset($_GET['tjax']))
{
    $id=$_GET['id'];
    $prepend_file.=$id."/";
    $append_file.=$id."/";
    $file_name=$_GET['file_name'];
    $prepend_file.="prepend.".$file_name;
    $append_file.="append.".$file_name;
}
 //echo $prepend_file;
 //echo $prepend_file;
 $result=$database->select("prefile","*",["problem_id"=>$id,"prepend"=>1]);
 $json=[];
 $json["empty"]=true;
if(count($result)>0)
{
  //  echo $prepend_file;
    //echo "prepend_file_start:";
    $json["prepend"]=$result[0]["code"];
    $json["empty"]=false;
    //echo htmlentities($result[0]["code"]);
    //echo htmlentities(file_get_contents($prepend_file));
    //echo "prepend_file_end;\n";
}
$result=$database->select("prefile","*",["problem_id"=>$id,"prepend"=>0]);
if(count($result)>0)
{
  //  echo $append_file;
    //echo "append_file_start:";
    //echo htmlentities($result[0]["code"]);
   // echo htmlentities(file_get_contents($append_file));
    //echo "append_file_end;\n";
    $json["append"]=$result[0]["code"];
    $json["empty"]=false;
}
if(file_exists($prepend_file))
{
    $json["empty"]=false;
    $json["prepend"]=file_get_contents($prepend_file);
}
if(file_exists($append_file))
{
    $json["empty"]=false;
    $json["append"]=file_get_contents($append_file);
}
echo json_encode($json);
?>