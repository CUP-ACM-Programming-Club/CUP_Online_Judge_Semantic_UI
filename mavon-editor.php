<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>Online Editor -- <?php echo $OJ_NAME ?></title>
    <?php include("template/semantic-ui/css.php"); ?>
    <?php include("template/semantic-ui/extra_css.php") ?>
    <?php include("template/semantic-ui/js.php"); ?>
    <script src="/template/semantic-ui/js/clipboard.min.js"></script>
<script src="/js/mavon-editor.js"></script>
<script src='/MathJax/MathJax.js?config=TeX-AMS-MML_HTMLorMML' ></script>
<script>
     Vue.use(window["mavon-editor"]);
</script>
<script type="text/x-mathjax-config">
  MathJax.Hub.Config({
      showProcessingMessages:false,
      messageStyle:"none",
    tex2jax: {
      inlineMath: [
        ['$', '$'],
        ['\\(', '\\)']
      ],
      displayMath: [ ["$$","$$"] ],
      skipTags: ['script', 'noscript', 'style', 'textarea', 'pre','code','a'],
    },
    TeX: {
      equationNumbers: {
        autoNumber: ["AMS"],
        useLabelIds: true
      }
    },
    "HTML-CSS": {
      linebreaks: {
        automatic: true
      }
    },
    SVG: {
      linebreaks: {
        automatic: true
      }
    }
  });

</script>
<script src="/lab/dist/mermaid.min.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php include("template/semantic-ui/nav.php");
include("csrf.php");
?>
<div class="container">
    <!-- Main component for a primary marketing message or call to action -->
    <div class="ui container padding">
        <div class="ui grid">
            <div class="row">
                <h2 class="ui header">
                    Description
                </h2>
                </div>
                <div class="row">
                <mavon-editor ref=md v-model="description" @imgAdd="imgAdd1"></mavon-editor>
                </div>
                
            </div>
    </div>
</div> <!-- /container -->
<script>
    window.mVue = new Vue({
                el:".ui.container.padding",
                data:function(){
                    return {
                        description:""
                    }
                },
                methods:{
                    imgAdd1:function(pos, $file){
                        console.log($file);
                     }
                }
    });
</script>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

    <?php include("template/semantic-ui/bottom.php") ?>
</body>
</html>
