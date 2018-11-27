<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <link href="/css/github-markdown.min.css" rel="stylesheet" type="text/css">
    <link href="/js/styles/github.min.css" rel="stylesheet" type="text/css">
    <link href="/template/semantic-ui/css/katex.min.css" rel="stylesheet">
    <link href="/template/semantic-ui/css/judge.css?ver=1.1" rel="stylesheet">
    <style>
        .subscript{
            font-size: 1rem;
        }
        .none-transform{
            text-transform: none !important;
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
 <?php include("template/semantic-ui/nav.php");?>	 
    <div class="ui container padding">
      <h2 class="ui dividing header">
            Source Code
        </h2>
      <!-- Main component for a primary marketing message or call to action -->
<!--
<link href='highlight/styles/shCore.css' rel='stylesheet' type='text/css'/>
<link href='highlight/styles/shThemeDefault.css' rel='stylesheet' type='text/css'/>
<script src='highlight/scripts/shCore.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushCpp.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushCss.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushJava.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushDelphi.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushRuby.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushBash.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushPython.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushPhp.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushPerl.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushCSharp.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushVb.js' type='text/javascript'></script>

<script language='javascript'>
SyntaxHighlighter.config.bloggerMode = false;
SyntaxHighlighter.config.clipboardSwf = 'highlight/scripts/clipboard.swf';
SyntaxHighlighter.all();
</script>
-->
    <script src="/template/semantic-ui/js/clipboard.min.js"></script>
<div class="ui existing segment" v-cloak>
    <div class="ui raised segment" v-cloak >
    <div class="ui tiny statistics" v-if="code">
        <div class="statistic">
            <div class="value none-transform">
                {{from+' '+problem_id}}
                <span class="subscript">&nbsp;</span>
            </div>
            <div class="label none-transform">
                Problem
            </div>
        </div>
        <div class="statistic">
            <div class="value none-transform">
                {{user_id}}
                <span class="subscript">&nbsp;</span>
            </div>
            <div class="label none-transform">
                User
            </div>
        </div>
        <div class="statistic">
            <div class="value none-transform">
                {{time}}
                <span class="subscript">ms</span>
            </div>
            <div class="label none-transform">
                Running Time
            </div>
        </div>
        <div class="statistic">
            <div class="value none-transform">
                {{memory}}
                <span class="subscript">KB</span>
            </div>
            <div class="label none-transform">
                Used Memory
            </div>
        </div>
        <div class="statistic">
            <div class="value none-transform">
                {{language}}
                <span class="subscript">&nbsp;</span>
            </div>
            <div class="label none-transform">
                Language
            </div>
        </div>
        <div class="statistic">
            <div :class="'value none-transform '+judge_color">
                <i :class="icon+' icon'"></i>
                {{result}}
             <span class="subscript">&nbsp;</span>
            </div>
            <div class="label none-transform">
                Result
            </div>
        </div>
        <div class="statistic" v-if="privilege">
            <div class="value none-transform">
                <a @click="rejudge" style="cursor:pointer">重新判题</a>
                <span class="subscript">&nbsp;</span>
            </div>
            <div class="label none-transform">
                <a @click="ban" style="cursor:pointer">封禁</a>
            </div>
        </div>
    </div>
    </div>
    <div class="ui raised segment" v-if="code">
        <div class="ui top right attached label"><a data-clipboard-target="#code" id="copy">Copy Source Code</a></div>
        <div v-html="code" id="code">
        </div>
    </div>
    <div class="ui existing segment" v-text="statement" v-if="statement">
        
    </div>
</div>
    <script>
    var clipboard = new Clipboard("#copy");
    
    clipboard.on("success",function(e){
        $('#copy')
        .popup({
            title   : 'Finished',
            content : 'Your code is in your clipboard',
            on      : 'click'
         })
         .popup("show");
    })
    var local;
    var id;
    var rejudge_mode;
    if(getParameterByName("id")) {
        local = "local";
        id = getParameterByName("id");
        rejudge_mode = true;
    }
    else {
        local = "vjudge"
        rejudge_mode = false;
        id = getParameterByName("hid");
    }
        $.get("/api/source/" + local + "/"+ id,function(data){
            if(data.status == "OK") {
            window.showsource = new Vue({
                el:".ui.container.padding",
                data:function() {
                    return {
                        code:data.data.code,
                        time:data.data.time,
                        memory:data.data.memory,
                        problem_id:Math.abs(data.data.problem),
                        result:data.data.result,
                        language:data.data.language,
                        user_id:data.data.user_id,
                        judge_color:data.data.judge_color,
                        icon:data.data.icon,
                        from:data.data.from||"",
                        statement:false,
                        privilege:data.privilege && rejudge_mode
                    }
                },
                methods:{
                    ban: function() {
                        var that = this;
                        $.post("../api/status/ban_submission",{solution_id:id}, function(data) {
                            alert("Server receive your request");
                            console.log(data);
                        })
                    },
                    rejudge: function() {
                        $.post("../api/status/rejudge", {solution_id:id}, function(data) {
                            alert("Server receive youre request");
                            console.log(data);
                        })
                    }
                }
            })
            }
            else {
                window.showsource = new Vue({
                    el:".ui.container.padding",
                    data:function(){
                        return {
                            code:false,
                            error:true,
                            statement:data.statement
                        }
                    }
                });
            }
        })
    </script>
<?php
/*
if ($ok==true){
if($view_user_id!=$_SESSION['user_id'])
echo "<a href='mail.php?to_user=$view_user_id&title=$MSG_SUBMIT $id'>Mail the auther</a>";
$hdulanguage_name=[];
$hdulanguage_name[0]="cpp";
$hdulanguage_name[1]="c";
$hdulanguage_name[2]="cpp";
$hdulanguage_name[3]="c";
$hdulanguage_name[4]="pascal";
$hdulanguage_name[5]="java";
$hdulanguage_name[6]="csharp";
$pojlanguage_name=[];
$pojlanguage_name[0]="cpp";
$pojlanguage_name[1]="c";
$pojlanguage_name[2]="java";
$pojlanguage_name[3]="pascal";
$pojlanguage_name[4]=$pojlanguage_name[1];
$pojlanguage_name[5]=$pojlanguage_name[0];
$pojlanguage_name[6]="fortran";
$uvalanguage_name=["","c","java","cpp","pascal","cpp",'python'];
$vjudge_lang_name=["HDU"=>$hdulanguage_name,"POJ"=>$pojlanguage_name,"UVA"=>$uvalanguage_name];
$hdu_pre_name=["G++","GCC","C++","C","Pascal","JAVA","C#"];
$poj_pre_name=["G++","GCC","JAVA","Pascal","C++","C","Fortran"];
$uva_pre_name=$vjudge_language_name["uva"];
$vjudge_pre_name=["HDU"=>$hdu_pre_name,"POJ"=>$poj_pre_name,"UVA"=>$uva_pre_name];
$brush="";
if(is_numeric($hid))
{
    $brush=strtolower($vjudge_lang_name[$oj_name][$slanguage]);
}
else
    $brush=strtolower($language_name[$slanguage]);
if(preg_match('/c\+\+',$brush))
{
    $brush='cpp';
}
else if(preg_match('/c/',$brush))
{
    $brush='c';
}
if ($brush=='pascal') $brush='delphi';
if ($brush=='obj-c') $brush='c';
if ($brush=='freebasic') $brush='vb';
if ($brush=='swift') $brush='csharp';
if($brush=='clang') $brush='c';
if($brush=='clang++')$brush='cpp';
echo "<pre class=\"brush:".$brush.";\">";
ob_start();
echo "/**************************************************************\n";
echo "\tProblem: $sproblem_id\n\tUser: $suser_id\n";
if(is_numeric($hid))
{
echo "\tLanguage: ".$vjudge_pre_name[$oj_name][$slanguage]."\n\tResult: ".$judge_result[$sresult]."\n";
}
else
echo "\tLanguage: ".$language_name[$slanguage]."\n\tResult: ".$judge_result[$sresult]."\n";
if ($sresult==4){
echo "\tTime:".$stime." ms\n";
echo "\tMemory:".$smemory." kb\n";
}*/
//echo "****************************************************************/\n\n";
/*
$auth=ob_get_contents();
ob_end_clean();
echo htmlentities(str_replace("\n\r","\n",$view_source),ENT_QUOTES,"utf-8")."\n".$auth."</pre>";
}else{
echo "I am sorry, You could not view this code!";
}
*/
?>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
     <?php include("template/semantic-ui/bottom.php");?>
  </body>
</html>
