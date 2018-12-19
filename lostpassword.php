<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
<?php include("template/$OJ_TEMPLATE/js.php");?>
<?php include("csrf.php") ?>
    <title><?php echo $OJ_NAME?></title>  
    <?php include("template/$OJ_TEMPLATE/css.php");?>	    


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>	   
    <div class="ui container padding">
     
      <!-- Main component for a primary marketing message or call to action -->
      <div class="ui top attached block header">找回密码</div>
<div class="ui bottom attached segment">
      <div class="ui form">
            <div class="one fields">
                <div class="field">
                    <label>User ID</label>
                    <div class="ui input">
                    <input size=50 type=text id="user_id" >
                    </div>
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <label>找回密码问题</label>
                    <div class="ui disabled input">
                    <input name="question" id="question" size=20 type=text>
                    </div>
                </div>
                <div class="field">
                    <label>找回密码答案</label>
                    <input name="answer" id="answer" size=20 type=text>
                </div>
            </div>
            <div class="three fields">
                <div class="field">
                    <label>新密码</label>
                    <input name="password" id="password" size=20 type=password>
                </div>
                <div class="field">
                    <label>再次输入</label>
                    <input name="repeat" id="repeat" size=20 type=password>
                </div>
                <div class="field">
                    <label>CAPTCHA</label>
                    <input name="vcode" id="vcode" size="10" style="width:60%" type=text><img alt="click to change" src="vcode.php" onclick="this.src='vcode.php?'+Math.random()" height="40px">
                </div>
            </div>
            <div class="fields">
                <div class="field">
                    <label></label>
                    <input class="ui primary button" value="Submit" name="submit" id="submit" type="submit">
                </div>
                <div class="field">
                    <label></label>
                    <input class="ui secondary button" value="Reset" name="reset" type="reset" onclick="location.href=location.href">
                </div>
            </div>
        </div>

    </div> <!-- /container -->
</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    	    
  </body>
  <script>
      $("#user_id").change(function(){
          $.post("resetdefault.php","user_id="+$("#user_id").val()+"&csrf=<?=$token?>",function(data){
              $("#question").val(data);
          })
      });
      $("#submit").on('click',function(data){
          $.post("resetdefault.php","user_id="+$("#user_id").val()+"&csrf=<?=$token?>&confirmanswer="+$("#answer").val()+"&newpass="+$("#password").val()+"&vcode="+$("#vcode").val(),function(data){
              if(data!="false")
              {
                  alert("修改成功!请登录");
                  location.href="newloginpage.php";
              }
              else
              {
                  if(data=="illegal")
                  {
                      alert("验证码错误！");
                      location.href=location.href;
                  }
                  else if(data=="false")
                  {
                      alert("答案错误!");
                      location.href=location.href;
                  }
              }
          })
      });
      $('.ui.form')
  .form({
    fields: {
      user_id     : 'empty',
      answer   : 'empty',
      password : 'empty',
      repeat : 'match[password]',
      vcode   : 'empty'
    }
  })
;
  </script>
  <?php include("template/$OJ_TEMPLATE/bottom.php");?>	    
</html>
