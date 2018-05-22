<?php
  require_once("include/db_info.inc.php");
//  @session_start();
  require_once("include/my_func.inc.php");
  $token = getToken();
  if(!isset($_SESSION['csrf_keys'])){
	$_SESSION['csrf_keys']=array();
  }
  $temp=[];
  foreach($_SESSION['csrf_keys'] as $temp_rows)
  {
      $temp[]=$temp_rows;
  }
  $temp[]=$token;
  while(count($temp)>40)
  {
      array_shift($temp);
  }
  //array_push($_SESSION['csrf_keys'],$token);
  //while(count($_SESSION['csrf_keys'])>40) 
	//array_shift($_SESSION['csrf_keys']);
	$_SESSION["csrf_keys"]=$temp;
//	echo var_dump($_SESSION["csrf_keys"]);
  
?><input type="hidden" name="csrf" value="<?php echo $token?>" class="<?php echo in_array($token,$_SESSION['csrf_keys'])?>">
