<?php
    require_once('./include/db_info.inc.php');
if(isset($_SESSION['user_id']))
{
    echo $_SESSION['user_id'];
}
else
{
    echo "false";
}
?>