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
                    输入答案正确的Solution ID
                </h2>
            </div>
            <div class="row">

                <div class="ui input">
                  <input type="text" v-model="solution_id" size="50">
                </div>
            </div>
            <div class="row">
                <h2 class="ui header">
                    题解正文
                </h2>
                </div>
                <div class="row">
                <mavon-editor v-model="content"></mavon-editor>
                </div>
                <div class="row">
                    <div class="two field">
    <div class="ui left input" style="width:auto">
                        <input v-model="captcha" type="text" placeholder="验证码"><img alt="click to change" src="/api/captcha?from=tutorial" onclick="this.src='/api/captcha?from=tutorial&random='+Math.random()" height="40px">
                    </div>
    </div>
                </div>
                <div class="row">
                    <div class="ui blue labeled submit icon button" @click="create_post">
      <i class="icon edit"></i> Modify Post
                </div>
                </div>
            </div>
    </div>
</div> <!-- /container -->
<script>
    var from = getParameterByName("from");
    var id = getParameterByName("id");
    var tid = getParameterByName("tutorial_id");
    $.get("../api/tutorial/"+tid,function(d){
        new Vue({
                el:".ui.container.padding",
                data:function(){
                    return {
                        content:d.data.content,
                        solution_id:d.data.solution_id,
                        captcha:"",
                        tutorial_id:tid
                    }
                },
                methods:{
                    create_post:function() {
                        var send = {
                            solution_id:this.solution_id,
                            content:this.content,
                            captcha:this.captcha
                        }
                        var that = this;
                        $.post("../api/tutorial/edit/"+this.tutorial_id,send,function(data){
                            if(data.status == "OK") {
                                alert("修改成功!");
                                location.href="/tutorial.php?from="+from + "&id="+id;
                            }
                            else {
                                alert("服务器遇到错误\n"+data.statement);
                            }
                        })
                    }
                }
    });
    })
    
</script>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

    <?php include("template/$OJ_TEMPLATE/bottom.php") ?>
</body>
</html>
