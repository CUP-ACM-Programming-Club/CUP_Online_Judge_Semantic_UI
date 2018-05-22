<?php
require_once('./include/db_info.inc.php');
function checknosql($str)
    {
        $len=strlen($str);
        for($i=0;$i<$len;$i++)
        {
            if(!($str[$i]>='0'&&$str[$i]<='9'||$str[$i]>='a'&&$str[$i]<='z'||$str[$i]>='A'&&$str[$i]<='Z'))return false;
        }
        return true;
    }
$user_id="";
//$sql="";
if(isset($_POST['user_id']))
{
    //if(checknosql($_POST['user_id']))
    //{
       // $sql="SELECT nick FROM users WHERE user_id='".$_POST['user_id']."'";
        $result=$database->select("users","nick",[
            user_id=>$_POST['user_id']
            ]);
      //  $result=mysqli_query($mysqli,$sql);
        $row=$result;
        echo "{ \"name\":\"".$row[0]."\",\"id\":\"".$_POST['user_id']."\"}";
   // }
    //else{
      //  echo "null";
        //exit(0);
    //}
}
?>