<?php 
	$cache_time=10;
	$OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	$view_title= "Welcome To Online Judge";
	require_once("./include/check_post_key.php");
	require_once("./include/my_func.inc.php");


$err_str="";
$err_cnt=0;
$len;
$user_id=$_SESSION['user_id'];
$email=trim($_POST['email']);
$school=trim($_POST['school']);
$nick=trim($_POST['nick']);
$blog = trim($_POST["blog"]);
$github = trim($_POST["github"]);
$confirmquestion=trim($_POST['confirmquestion']);
$confirmanswer=trim($_POST['confirmanswer']);
$len=strlen($nick);
if ($len>100){
	$err_str=$err_str."Nick Name Too Long!";
	$err_cnt++;
}else if ($len==0) $nick=$user_id;
$password=$_POST['opassword'];
//$sql="SELECT `user_id`,`password` FROM `users` WHERE `user_id`='".$user_id."'";
$result=$database->select("users",["user_id","password"],["user_id"=>$user_id]);
//$result=mysqli_query($mysqli,$sql);
//$row=mysqli_fetch_array($result);
$row=$result[0];
if ($row && pwCheck($password,$row['password'])) $rows_cnt = 1;
else $rows_cnt = 0;
//mysqli_free_result($result);
if ($rows_cnt==0){
	$err_str=$err_str."Old Password Wrong";
	$err_cnt++;
}
$len=strlen($_POST['npassword']);
if ($len<6 && $len>0){
	$err_cnt++;
	$err_str=$err_str."Password should be Longer than 6!\\n";
}else if (strcmp($_POST['npassword'],$_POST['rptpassword'])!=0){
	$err_str=$err_str."Two Passwords Not Same!";
	$err_cnt++;
}
$len=strlen($_POST['school']);
if ($len>100){
	$err_str=$err_str."School Name Too Long!";
	$err_cnt++;
}
$len=strlen($_POST['email']);
if ($len>100){
	$err_str=$err_str."Email Too Long!";
	$err_cnt++;
}
$len=strlen($_POST['confirmquestion']);
if ($len>100){
    $err_str=$err_str."Confirm Question Too Long!";
    $err_cnt++;
}
$len=strlen($_POST['confirmanswer']);
if($len>100){
    $err_str=$err_str."Confirm Answer Too Long!";
}
if ($err_cnt>0){
	print "<script language='javascript'>\n";
	echo "alert('";
	echo $err_str;
	print "');\n history.go(-1);\n</script>";
	exit(0);
	
}
if (strlen($_POST['npassword'])==0) $password=pwGen($_POST['opassword']);
else $password=pwGen($_POST['npassword']);
$nick=mysqli_real_escape_string($mysqli,htmlentities ($nick,ENT_QUOTES,"UTF-8"));
$school=mysqli_real_escape_string($mysqli,htmlentities ($school,ENT_QUOTES,"UTF-8"));
$email=mysqli_real_escape_string($mysqli,htmlentities ($email,ENT_QUOTES,"UTF-8"));
$confirmquestion=mysqli_real_escape_string($mysqli,htmlentities($confirmquestion,ENT_QUOTES,"UTF-8"));
if(strlen($confirmanswer)!=0){
$confirmanswer=pwGen($confirmanswer);
$sql="UPDATE `users` SET"
."`password`='".($password)."',"
."`nick`='".($nick)."',"
."`school`='".($school)."',"
."`email`='".($email)."' ,"
."`blog`='".($blog)."' ,"
."`github`='".($github)."' ,"
."`confirmquestion`='".($confirmquestion)."',"
."`confirmanswer`='".($confirmanswer)."' "
."WHERE `user_id`='".mysqli_real_escape_string($mysqli,$user_id)."'"
;
}
else
{
    $sql="UPDATE `users` SET"
."`password`='".($password)."',"
."`nick`='".($nick)."',"
."`school`='".($school)."',"
."`email`='".($email)."' ,"
."`blog`='".($blog)."' ,"
."`github`='".($github)."' ,"
."`confirmquestion`='".($confirmquestion)."' "
."WHERE `user_id`='".mysqli_real_escape_string($mysqli,$user_id)."'"
;
}
$_SESSION['nick']=$nick;
//echo $sql;
//exit(0);
mysqli_query($mysqli,$sql);// or die("Insert Error!\n");
	print "<script language='javascript'>\n";
	echo "alert('";
	echo "更改成功!";
	print "');\n history.go(-1);\n</script>";
?>
