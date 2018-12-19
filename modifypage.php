<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
<style>
    .padding {
            padding-left: 1em;
            padding-right: 1em;
            padding-top: 1em;
            padding-bottom: 1em;
        }
</style>
    <title><?php echo $OJ_NAME?></title>  
    <?php include("template/$OJ_TEMPLATE/css.php");?>	    
<?php include("template/$OJ_TEMPLATE/js.php");?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
    <div class="ui container pusher">
    	    
      <!-- Main component for a primary marketing message or call to action -->
      <div class="padding">
          <div class="ui grid">
          <div class="five wide column">
              <div class="column">
          <div class="ui card" style="">
      <div class="image">
        <img id="head" src="<?php if(file_exists("./avatar/".$_SESSION['user_id'].".jpg")){ ?>avatar/<?=$_SESSION['user_id']?>.jpg<?php }else{ ?>https://semantic-ui.com/images/wireframe/square-image.png<?php } ?>" onclick="document.getElementById('myinput').click()"><input type="file" name="pic[]" multiple id="myinput" style="display:none">
      </div>
      <div class="content">
        <a class="header" href="javascript:buttonclick();"><i class="upload icon"></i>上传头像</a>
      </div>
    </div>
    </div>
    </div>
    <div class="eleven wide column">
        <form action="modify.php" method="post">
<div class="ui top attached block header">Update Information</div>
<div class="ui bottom attached segment">
<table>
    <?php require_once('./include/set_post_key.php');?>
        <div class="ui form">
            <div class="two fields">
                <div class="field">
                    <label>User ID</label>
                    <div class="ui disabled input">
                    <input size=50 type=text value="<?php echo $_SESSION['user_id']?>" >
                    </div>
                </div>
                <div class="field">
                    <label>Nick Name</label>
                    <input name="nick" size=50 type=text value="<?php echo htmlentities($row["nick"],ENT_QUOTES,"UTF-8")?>" >
                </div>
            </div>
            <div class="three fields">
                <div class="field">
                    <label>Old Password</label>
                    <input name="opassword" size=20 type=password placeholder="请输入密码后更改其他信息">
                </div>
                <div class="field">
                    <label>New Password</label>
                    <input name="npassword" size=20 type=password>
                </div>
                <div class="field">
                    <label>Repeat New Password</label>
                    <input name="rptpassword" size=20 type=password>
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <label>School</label>
                    <input name="school" size=30 type=text value="<?php echo htmlentities($row["school"],ENT_QUOTES,"UTF-8")?>" >
                </div>
                <div class="field">
                    <label>Email</label>
                    <input name="email" size=30 type=text value="<?php echo htmlentities($row["email"],ENT_QUOTES,"UTF-8")?>" >
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <label>找回密码问题</label>
                    <input name="confirmquestion" size=30 type=text value="<?php echo htmlentities($row["confirmquestion"],ENT_QUOTES,"UTF-8")?>" >
                </div>
                <div class="field">
                    <label>找回密码答案</label>
                    <input name="confirmanswer" size=30 type=text value="" >
                </div>
            </div>
            <div class="four fields">
                <div class="field vjudge">
                    <label>HDU账号(仅提供查看)</label>
                    <input name="hdu" size="15" type="text" value="<?=$hdu?>">
                </div>
                <div class="field vjudge">
                    <label>POJ账号(仅提供查看)</label>
                    <input name="poj" size="15" type="text" value="<?=$poj?>">
                </div>
                <div class="field vjudge">
                    <label>UVa账号(仅提供查看)</label>
                    <input name="uva" size="15" type="text" value="<?=$uva?>">
                </div>
                <div class="field vjudge">
                    <label>Vjudge账号(仅提供查看)</label>
                    <input name="vjudge" size="15" type="text" value="<?=$vj?>">
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <label>Blog</label>
                    <input name="blog" type="text" value="<?=$row["blog"]?>">
                </div>
                <div class="field">
                    <label>GitHub</label>
                    <input name="github" type="text" value="<?=$row["github"]?>">
                </div>
            </div>

                <div class="field">
                    <label>Biography</label>
                    <input name="biography" type="text" value="<?=$row["biography"]?>">
                </div>
            <div class="fields">
                <div class="field">
                    <label></label>
                    <input class="ui primary button" value="Submit" name="submit" type="submit">
                </div>
                <div class="field">
                    <label></label>
                    <input class="ui secondary button" value="Reset" name="reset" type="reset" onclick="location.href=location.href">
                </div>
                <div class="field">
                    <label>
                    </label>
                    <div class="ui button">
<a href=export_ac_code.php>Download All AC Source</a></div>
                </div>
            </div>
        </div>
</table>
</div>
</form>
    </div>
    </div>
  </div>
	
<br>
<br>
      </div>

    </div> <!-- /container -->
<script>
$(document).ready(function(){
    $("#head").css({height:$("#head")[0].width*$("#head")[0].naturalHeight/$("#head")[0].naturalWidth})
})
$(".vjudge").popup({
    title    : '只读内容',
    content  : '本条目为Virtual Judge爬取做题记录之用，若需要添加请联系管理员: Ryan Lee(李昊元)'
  })
function readImage(obj){
		    var fd = new FormData();

			fd.append("pic", document.getElementById("myinput").files[0]);
			
			$.ajax({
				url:"upload.php",
				type:"post",
				data:fd,
				processData:false,
				contentType:false,
				success:function(res){
					console.log(res);
				},
				fail:function(res)
				{
				    console.log(res);
				},
				dataType:"json"
			})
			}
function buttonclick()
{
    document.getElementById('myinput').click();
}
$("#myinput").on('change',function(){
   var file=$("#myinput")[0].files[0];
   var reader=new FileReader();
   reader.onloadend=function()
   {
       var dataURL=reader.result;
       var img=$("#head")[0];
       img.src=dataURL;
       readImage();
   }
   reader.readAsDataURL(file);
});
		function uploadFile(){
            var files=document.getElementById("myinput").files;
            console.log(files)
    for(var i=0;i<files.length;i++){

    	readImage(files[i])
    }
		}
	</script>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    	    
    	    <?php include("template/$OJ_TEMPLATE/bottom.php");?>
    	    <?php include("csrf.php") ?>
  </body>
</html>
