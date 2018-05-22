<?php 
require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
<script src="js/jquery.min.js"></script>
<script src="js/flat-ui.min.js"></script>
<script>
alert(location.href);
    function posttext(){
        var response;
        var user_id=document.getElementById('user_id').value;
        if(user_id.length==0)
        {
            alert("请输入账号");
            return ;
        }
        if(document.getElementById('question').style.visibility=="hidden")
        {
            var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
        response="找回问题:";
    response+=xmlhttp.responseText;
    if(response!="false")
    {
        var question=document.getElementById('question');
        document.getElementById('confirmanswer').style.visibility="visible";
        $("#confirmanswer").fadeIn();
        question.style.visibility="visible"
        $("#question").fadeIn();
        question.innerText=response;
        document.getElementById('user_id').disabled=true;
    }
    else
    {
        alert("账号不存在!");
        return ;
    }
    }
  }
xmlhttp.open("POST","resetdefault.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send("user_id="+user_id);
        }
        else
        {
            var answer=document.getElementById('confirmanswer').value;
            var xmlhttp;
            if(answer.length!=0){
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    response=xmlhttp.responseText;
    if(response!="false")
    {
        alert('成功！\n你的密码已恢复为默认设置:12345678\n请及时到账号页面修改你的账号密码!');
        location.href="http://"+location.hostname;
    }
    else
    {
        alert("答案不正确!");
        return ;
    }
    }
  }
xmlhttp.open("POST","resetdefault.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send("user_id="+user_id+"&confirmanswer="+answer);
        }
        else
        {
            alert("找回密码答案不能为空!");
            return ;
        }
    }
    }
</script>
    <title><?php echo $OJ_NAME?></title>  
    <?php include("template/$OJ_TEMPLATE/css.php");   
require_once("./include/my_func.inc.php");
$pass="12345678";
if (isset($_SESSION['administrator'])){
//	echo pwGen($pass);
}
else{
    echo "<script>alert('No Admin permission!')</script>";
}
?>
<body>
    <div class="container">
        
    <?php include("template/$OJ_TEMPLATE/nav.php");?>	
        <?php if(isset($_SESSION['administrator']))
        {?>
        <form action="resetdefault.php" method="post">
    <input type="text" class="form-control" size="4" value="" placeholder="输入你的账号" name="user_id" id="user_id" style="margin: auto;width:25%;text-align: center"/>
    <p id="question" style="margin: auto;width:25%;text-align: center;visibility:hidden;display:none"></p>
    <input type="text" class="form-control" size="4" value="" placeholder="输入你的找回密码答案" name="user_id" id="confirmanswer" style="margin: auto;width:25%;text-align: center;visibility:hidden;display:none"/><br>
    <center><a class="btn btn-info btn-lg" name="submit" onclick="posttext()"  style="margin:auto;text-align:center">提交</a>&nbsp;<a class="btn btn-info btn-lg" name="reset" onclick="location.reload();"  style="margin:auto;text-align:center">重置</a>
    </form>
    <?php } ?>
    </div>
    </form>
</body>
</html>