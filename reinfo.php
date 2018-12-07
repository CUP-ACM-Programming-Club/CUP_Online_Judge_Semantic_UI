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
    <link type="text/css" rel="stylesheet" href="mergely/codemirror.css" />
	<link type="text/css" rel="stylesheet" href="mergely/mergely.css?ver=1.0.1" />
      <?php include("template/$OJ_TEMPLATE/js.php");?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="mergely/codemirror.js"></script>
	
	<!-- Requires Mergely -->
	<script type="text/javascript" src="mergely/mergely.js"></script>
  </head>

  <body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>	    

    <div class="ui container padding">
        <h2 class="ui dividing header">
            Runtime Error Information
        </h2>
        <div id="compare"></div>
    <div class="ui main raised segment">
      <!-- Main component for a primary marketing message or call to action -->
	
<pre id='errtxt' class="alert alert-error"><?php echo $view_reinfo?></pre>

<div id='errexp'>Explain:</div>

	
	
<script>
var pats=new Array();
var exps=new Array();
pats[0]=/A Not allowed system call.* /;
exps[0]="使用了系统禁止的操作系统调用，看看是否越权访问了文件或进程等资源";
pats[1]=/Segmentation fault/;
exps[1]="段错误，检查是否有数组越界，指针异常，访问到不应该访问的内存区域";
pats[2]=/Floating point exception/;
exps[2]="浮点错误，检查是否有除以零的情况";
pats[3]=/buffer overflow detected/;
exps[3]="缓冲区溢出，检查是否有字符串长度超出数组的情况";
pats[4]=/Killed/;
exps[4]="进程因为内存或时间原因被杀死，检查是否有死循环";
pats[5]=/Alarm clock/;
exps[5]="进程因为时间原因被杀死，检查是否有死循环，本错误等价于超时TLE";
function explain(){
//alert("asdf");
var errmsg=document.getElementById("errtxt").innerHTML;
var expmsg="辅助解释：<br>";
for(var i=0;i<pats.length;i++){
var pat=pats[i];
var exp=exps[i];
var ret=pat.exec(errmsg);
if(ret){
expmsg+=ret+":"+exp+"<br>";
}
}
document.getElementById("errexp").innerHTML=expmsg;
//alert(expmsg);
}
explain();
var segmentSize = parseInt($(".ui.main.segment").css("height"))
if(segmentSize < window.innerHeight - 300) {
$(".ui.main.segment").css({
    height:window.innerHeight - 300
})
}
</script>

</div>
    </div> <!-- /container -->
<script type="text/javascript">
        $(document).ready(function () {
			
			var text = $("#errtxt").html();
			
			var left = text.substring(text.indexOf("------测试输出前100行-----"),text.indexOf("------用户输出前100行-----"));
			var right = text.substring(text.indexOf("------用户输出前100行-----"),text.indexOf("------测试输出(左)与用户输出(右)前200行的区别-----"));
			
			console.log(left);
			console.log(right);
			if (text && text.length && left && left.length) {
			    $('#compare').mergely({
				cmsettings: { readOnly: false, lineWrapping: true },
				ignorews: true,
				ignorecase: true
			});
			$('#compare').mergely('lhs', left);
			$("#compare").mergely('resize');
			$('#compare').mergely('rhs', right);
			$("#compare").mergely('resize');
			$("#errtxt").hide();
			$(".ui.raised.main.segment").hide();
			}
		});
	</script>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
<?php include("template/$OJ_TEMPLATE/bottom.php");?>
  </body>
</html>
