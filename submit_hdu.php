<?php
require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
require_once('./include/setlang.php');
if(!isset($_SESSION['user_id']))exit(0);
function  submit ($pid,$language,$code){
    echo $pid."\n".$language."\n".$code."\n";
    $cookie_jar=dirname(__FILE__)."/hdu.cookie";
    /*
    $url="http://acm.hdu.edu.cn/";
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL ,$url);
	curl_setopt($ch,CURLOPT_HEADER ,0);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER ,true);
	curl_setopt($ch,CURLOPT_COOKIEJAR ,$cookie_jar);
	$content=curl_exec($ch);
	curl_close($ch);
	*/
    $post="username=cupvjudge&userpass=2016011253";
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL ,"http://acm.hdu.edu.cn/userloginex.php?action=login");
    curl_setopt($ch,CURLOPT_HEADER ,false);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER ,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS ,$post);
    curl_setopt($ch,CURLOPT_COOKIEFILE ,$cookie_jar);
    curl_setopt($ch,CURLOPT_COOKIEJAR ,$cookie_jar);
    $result=curl_exec($ch);
//	curl_close($ch);

    $post=["check"=>"0","problemid"=>$pid,"language"=>$language,"usercode"=>$code];
//	$ch=curl_init();
    curl_setopt($ch,CURLOPT_URL ,"http://acm.hdu.edu.cn/submit.php?action=submit");
//	curl_setopt($ch,CURLOPT_HEADER ,false);
//	curl_setopt($ch,CURLOPT_RETURNTRANSFER ,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS ,$post);
//	curl_setopt($ch,CURLOPT_COOKIEFILE ,$cookie_jar);//发送Cookie
//	curl_setopt($ch,CURLOPT_COOKIEJAR ,$cookie_jar);//保存Cookie
    $result=curl_exec($ch);
    curl_close($ch);
}
/*
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL ,"http://acm.hdu.edu.cn/userstatus.php?user=cupvjudge");
curl_setopt($ch,CURLOPT_HEADER ,false);
curl_setopt($ch,CURLOPT_HEADER ,0);
curl_setopt($ch,CURLOPT_RETURNTRANSFER ,0);
curl_setopt($ch,CURLOPT_COOKIEFILE ,$cookie_jar);
$html=curl_exec($ch);
echo $html;
// var_dump($html);
curl_close($ch);
*/
$now=strftime("%Y-%m-%d %H:%M",time());
$code="";
$pid="";
$language="";
$run="";
$sid="";
$json="";
$is_json=false;
$oj_name="";
$mode=0;
$cid="";
$num="";
if(isset($_POST['json']))
{
    $is_json=true;
    $json=json_decode($_POST['json'],true);
    if($json['cid'])
    {
        $pid=intval($json['pid']);
        $num=$pid;
        $cid=intval($json['cid']);
        $mode=1;
        $result=$database->select("contest_problem","problem_id",["num"=>$pid,"contest_id"=>$cid]);
        if(count($result)==0)
        {
            exit(0);
        }
        $pid=$result[0];
        $result=$database->select("contest","private",["contest_id"=>$cid,"start_time[<=]"=>$now,"end_time[>]"=>$now]);
        if(count($result)==0)
        {
            exit(0);
        }
        if($isprivate==1&&!isset($_SESSION['c'.$cid]))
        {
            $cnt=$database->count("privilege",["user_id"=>$user_id,"rightstr"=>"c$cid"]);
            if($cnt==0&&!isset($_SESSION['administrator']))
            {
                exit(0);
            }
        }
    }
    else
    {
        $pid=$json['id'];
    }
    $code=$json['source'];
    $language=intval($json['language']);
    $oj_name=$json['oj_name'];
}
if(isset($_POST['sid']))
{
    $sid=$_POST['sid'];
}
if(isset($_POST['run']))
{
    $run=true;
}
if(isset($_POST['code']))
{
    $code=$_POST['code'];
    // echo "code success";
}
if(isset($_POST['pid']))
{
    $pid=$_POST['pid'];
    //   echo "pid success";
}
if(isset($_POST['language']))
{
    $language=intval($_POST['language']);
    // echo "language success";
}
$conditions=[
    "problem_id"=>$pid,
    "user_id"=>$_SESSION['user_id'],
    "#in_date"=>"NOW()",
    "ip"=>$_SERVER['REMOTE_ADDR'],
    "code_length"=>strlen($code),
    "oj_name"=>$oj_name,
    "language"=>$language,
    "judger"=>"vjudge",
    "runner_id"=>"empty"
];
if($mode==1)
{
    $conditions["contest_id"]=$cid;
    $conditions["num"]=$num;
}
$database->insert("vjudge_solution",$conditions);
$problem_id=$database->select("vjudge_problem","submit",[
    "problem_id"=>$pid,
    "source"=>$oj_name
]);
$database->update("vjudge_problem",[
    "submit[+]"=>1
],[
    "problem_id"=>$pid,
    "source"=>$oj_name
]);
$database->update("users",[
        "vjudge_submit[+]"=>1
    ],[
        "user_id"=>$_SESSION["user_id"]]);
$solution_id=$database->select("vjudge_solution","solution_id",[
    "user_id"=>$_SESSION['user_id'],
    "code_length"=>strlen($code),
    "ORDER"=>["in_date"=>"DESC"],
    "oj_name"=>$oj_name,
    "LIMIT"=>20
]);
//echo var_dump($solution_id);
$database->insert("vjudge_source_code",[
    "solution_id"=>$solution_id[0],
    "source"=>$code
]);
if($is_json)
{
    echo $solution_id[0];
}
//echo var_dump($database->error());
//echo var_dump($database->error());

?>