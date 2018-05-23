<?php

if(isset($_SERVER['HTTP_REFERER']))$dir=basename(dirname($_SERVER['HTTP_REFERER']));
else $dir="";
if($dir=="discuss3") $path_fix="../";
else $path_fix="";
require_once("include/db_info.inc.php");
if(isset($OJ_LANG)){
    require_once("lang/$OJ_LANG.php");
}else{
    require_once("lang/en.php");
}
$hasmail=false;
function checkmail(){
    $database=$GLOBALS["database"];
    $mysqli=$GLOBALS['mysqli'];
    /*
    $sql="SELECT count(1) FROM `mail` WHERE
				new_mail=1 AND `to_user`='".$_SESSION['user_id']."'";
    $result=mysqli_query($mysqli,$sql);
    */
    $result = $database->count("mail",["new_mail"=>1,"to_user"=>$_SESSION["user_id"]]);
    if(!$result) return false;
    $temp_row=[$result];
    //$temp_row=mysqli_fetch_temp_row($result);
    $retmsg="<span id=red>当前邮件  (".$temp_row[0].")</span>";
    //mysqli_free_result($result);
    $obj[0]=$retmsg;
    $obj[1]=intval($temp_row[0])>0;
    return $obj;
}
$data = $database->select("users","*",["user_id"=>$_SESSION["user_id"]]);
$money = $data[0]["money"];
$profile="<div class='ui two column grid'><div class='row'>";
$profile.="<div class='column'>";
$profile.="<img src='".(file_exists("./avatar/".$_SESSION["user_id"].".jpg")?"./avatar/".$_SESSION["user_id"].".jpg":"./assets/images/wireframe/white-image.png")."' class='ui small image'><h4 class='ui header'>".$_SESSION["user_id"]."<div class='sub header'>".$_SESSION["nick"]."</div></h4>";
$profile.="<p><i class='yen sign icon'></i>".$money."</p>";
$profile.="</div>";
$profile.="<div class='column'><div class='ui link list'> ";
if (isset($_SESSION['user_id'])){
    $sid=$_SESSION['user_id'];
    $profile.= "<a class='item' href=".$path_fix."modifypage.php><i class='edit icon'></i>$MSG_USERINFO</a><br>
    <a class='item' href='".$path_fix."userinfo.php?user=$sid'><i class='archive icon'></i>个人信息</a>";
    $mail=checkmail();
    if (($OJ_EMAIL_MODE||isset($_SESSION['administrator']))&&$mail)
    {
        $profile.= "<br><a class='item' href=".$path_fix."mail.php><i class='mail icon'></i>".$mail[0]."</a>";
        $hasmail=$mail[1];
    }
    $profile.="<br><a class='item' href='".$path_fix."status.php?user_id=$sid'><i class='send icon'></i>$MSG_MY_SUBMISSIONS</a>";
    $profile.="<br><a class='item' href='".$path_fix."contest.php?my'><i class='book icon'></i>$MSG_MY_CONTESTS</a>";

    $profile.= "<br><a class='logout item'><i class='remove user icon'></i>$MSG_LOGOUT</a>&nbsp;";
}else{

    $profile.= "<a class='item' href=".$path_fix."newloginpage.php>$MSG_LOGIN</a>&nbsp;";
    if($OJ_LOGIN_MOD=="hustoj"){
        $profile.= "<a class='item' href=".$path_fix."registerpage.php>$MSG_REGISTER</a>&nbsp;";
    }
}
if (isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])||isset($_SESSION['problem_editor'])){
    $profile.= "<br><a class='item' href=".$path_fix."admin/><i class='write icon'></i>$MSG_ADMIN</a>&nbsp;";
    $profile.="<br><a class='item' href=".$path_fix."announce.php><i class='send outline icon'></i>在线用户推送</a>&nbsp;";
    // $profile.="<a class='item' href='index.php?tp=flat-ui'>使用Flat-UI</a>&nbsp;";
}
//if(isset($OJ_CONTEST_MODE)&&!$OJ_CONTEST_MODE)
    //$profile.="<div class='item'><i class='right dropdown icon'></i><span class='text'><i class='grid layout icon'></i>Theme</span><div class='menu'><a class='item' href='index.php?tp=flat-ui'><span class='text'>Flat-UI</span></a><a class='item' href='index.php?tp=semantic'><span class='text'>Semantic-UI</span></a><a class='item' href='index.php?tp=semantic-ui'><span class='text'>New Semantic-UI</span></a></div></div>";
//  $profile.="</ul>";
    $profile.="</div></div></div></div>";
$dropdown_control=$profile;
$profile_control="";
if($hasmail)$profile_control.="您有新邮件";
else if(isset($sid))$profile_control.=strlen($_SESSION['nick'])>25?substr($_SESSION['nick'],0,13)."…":$_SESSION['nick'];
else $profile_control.=$MSG_LOGIN;
?>
