<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title><?php echo $OJ_NAME ?></title>
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <?php include("template/$OJ_TEMPLATE/extra_css.php") ?>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <?php include("template/$OJ_TEMPLATE/extra_js.php") ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php");
include("csrf.php");
?>
<div class="container">
    <!-- Main component for a primary marketing message or call to action -->
    <div class="ui container padding">
        <div class="ui grid">
            <div class="row">
                <h2 class="ui header">
                    Title
                </h2>
            </div>
            <div class="row">

                <div class="ui input">
                  <input type="text" v-model="title" size="50">
                </div>
            </div>
            <div class="row">
                <h2 class="ui header">
                    Content
                </h2>
                </div>
                <div class="row">
                <mavon-editor v-model="content"></mavon-editor>
                </div>
                <div class="row">
                    <div class="two field">
    <div class="ui left input" style="width:auto">
                        <input v-model="captcha" type="text" placeholder="验证码"><img alt="click to change" src="/api/captcha?from=newpost" onclick="this.src='/api/captcha?from=newpost&random='+Math.random()" height="40px">
                    </div>
    </div>
                </div>
                <div class="row">
                    <div class="ui blue labeled submit icon button" @click="create_post">
      <i class="icon edit"></i> Add New Post
                </div>
                </div>
            </div>
    </div>
</div> <!-- /container -->
<script>
    new Vue({
                el:".ui.container.padding",
                data:function(){
                    return {
                        content:"",
                        title:"",
                        captcha:""
                    }
                },
                methods:{
                    create_post:function() {
                        var send = {
                            title:this.title,
                            content:this.content,
                            captcha:this.captcha
                        }
                        $.post("../api/discuss/newpost",send,function(data){
                            if(data.status == "OK") {
                                alert("添加成功!");
                                location.href="/discuss.php";
                            }
                            else {
                                alert("服务器遇到错误\n"+data.statement);
                            }
                        })
                    }
                }
    });
</script>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

    <?php include("template/$OJ_TEMPLATE/bottom.php") ?>
</body>
</html>
