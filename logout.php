<?php session_start();
unset($_SESSION['user_id']);
unset($_SESSION['nick']);
setcookie("token","",time()-6000);
setcookie("user_id","",time()-6000);
session_destroy();
header("Location:index.php");
?>
