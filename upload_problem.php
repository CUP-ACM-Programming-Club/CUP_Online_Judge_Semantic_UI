<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>Upload Problem -- <?php echo $OJ_NAME ?></title>
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <?php include("template/$OJ_TEMPLATE/extra_css.php") ?>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <?php include("template/$OJ_TEMPLATE/extra_js.php") ?>
    <script src="/js/dist/g2.min.js"></script>
    <script src="/js/dist/data-set.min.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php") ?>
    <div class="ui container padding" style="min-height:400px">
        <h2 class="ui dividing header">Upload Problem</h2>
        <div class="ui grid">
            <div class="row">
                <div class="ui center aligned segment" style="min-height:400px">
                    <div class="ui main container">
                <form action="/api/upload/user" method="post" class="ui form" enctype="multipart/form-data">
	<h3>Import Problem(RPK):</h3>
	<div class="field">
	    <a class="ui button" @click="selectFile">{{fileStatus}}</a>
	    <input style="display:none" type="file" name="fps" id="file" @change="fileChange">
	   </div>
	<div class="two field">
	    <div class="ui left input" style="width: auto;"><input name="captcha" type="text" placeholder="验证码" id="vcode"><img alt="click to change" id="vcode_graph" src="/api/captcha?from=upload" onclick="this.src='/api/captcha?from=upload&random='+Math.random()" height="40px"></div>
	</div>
	<div class="field">
	    <input type="submit" class="ui blue button" value="Import">
	</div>
	
    
    </form>
    </div>
</div>
            </div>
    </div>
</div> <!-- /container -->
<script>
    window.upload_file = new Vue({
        el:".ui.container.padding",
        data:function(){
            return {
                fileStatus:"选择文件"
            };
        },
        methods:{
            selectFile:function(){
                $("#file").click();
            },
            fileChange:function($event) {
                this.fileStatus = $event.target.files[0].name;
            }
        },
        mounted:function(){
            
        }
    })
</script>
    <?php include("template/semantic-ui/bottom.php") ?>
</body>
</html>
