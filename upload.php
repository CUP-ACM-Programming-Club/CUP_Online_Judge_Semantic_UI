<?php
require_once('./include/db_info.inc.php');
require_once('./include/cache_start.php');
require_once('./include/setlang.php');
require "./Upload.class.php";
if(!isset($_SESSION['user_id']))
{
    exit(0);
}
$upload = new Upload();


$upload->maxSize   =     5*1024*1024 ;// 设置附件上传大小
$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
$upload->rootPath  =     './avatar/'; // 设置附件上传根目录
$upload->replace=true;
//$upload->autoSub  =    "";  //子目录

$info = $upload->upload();

//定义结果
$result = array();
if (!$info) {

	$result["error"] = 1;
	$result["message"] = $upload->getError();

} else {
	$result["error"] = 0;
	$result["data"] = $info;
}
if(file_exists("./avatar/".$_SESSION["user_id"].".jpg")){
$database->update("users",["avatar"=>true],["user_id"=>$_SESSION["user_id"]]);
}
echo json_encode($result);


