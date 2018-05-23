<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $OJ_NAME?></title>  
    <?php include("template/$OJ_TEMPLATE/css.php");?>	    
     <?php include("template/$OJ_TEMPLATE/js.php");?>
     <meta charset="utf-8" />
	<link rel="stylesheet" href="kindeditor/themes/default/default.css" />
	<link rel="stylesheet" href="kindeditor/plugins/code/prettify.css" />
	<script charset="utf-8" src="kindeditor/kindeditor.js"></script>
	<script charset="utf-8" src="kindeditor/lang/zh_CN.js"></script>
	<script charset="utf-8" src="kindeditor/plugins/code/prettify.js"></script>
	<script>
		KindEditor.ready(function(K) {
			var editor1 = K.create('textarea[class="kindeditor"]', {
				cssPath : 'kindeditor/plugins/code/prettify.css',
				uploadJson : 'kindeditor/php/upload_json.php',
				fileManagerJson : 'kindeditor/php/file_manager_json.php',
				allowFileManager : false,
				afterCreate : function() {
					var self = this;
					K.ctrl(document, 13, function() {
						self.sync();
						K('form[name=example]')[0].submit();
					});
					K.ctrl(self.edit.doc, 13, function() {
						self.sync();
						K('form[name=example]')[0].submit();
					});
					document.getElementsByClassName("ke-container-default")[0].style+=";margin:auto";
				}
			});
			prettyPrint();
		});
	</script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>	    
    <div class="container pusher">
      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <div style="text-align:center;margin:auto">
            <div><h2>在线用户信息推送</h2></div>
            <textarea class="kindeditor" rows=13 cols=80 id="submit_text" style="width:500px;height:250px" placeholder="支持输入HTML代码"></textarea>
            <br>
            <button class="ui btn" onclick="test()">测试发送</button>
            <button class="ui btn" onclick="send()">发送</button>
        </div>
      </div>
    </div> <!-- /container -->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
   	    
 <script language="javascript" type="text/javascript" src="include/jquery.flot.js"></script>

  </body>
  <script>
  function test()
  {
      var send_text=$(document.getElementsByClassName("ke-edit-iframe")[0].contentWindow.document.body).html();
      var data={
        user_id:"<?=$_SESSION['user_id']?>",
        nick:"<?=$_SESSION['nick']?>",
        content:send_text
    }
      $(".msg.header.item").attr("data-html","<div class='header'>From:"+data['user_id']+"<br>"+data['nick']+"</div><div class='content'>"+data['content']+"</div>")
            .popup("show").popup("set position","bottom center");
      //var sidebar=document.getElementById("sidebar_text");
     // sidebar.innerHTML=send_text;
      //$(".ui.sidebar.message").sidebar('toggle');
  }
function send()
{
    var send_text=$(document.getElementsByClassName("ke-edit-iframe")[0].contentWindow.document.body).html();
    var obj={
        user_id:"<?=$_SESSION['user_id']?>",
        nick:"<?=$_SESSION['nick']?>",
        content:send_text
    }
    //var json=JSON.stringify(obj);
    socket.emit("msg",obj);
    //ws.send(json);
}
  </script>
</html>
