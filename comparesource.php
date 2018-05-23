<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
	<link type="text/css" rel="stylesheet" href="mergely/codemirror.css" />
	<link type="text/css" rel="stylesheet" href="mergely/mergely.css" />
    <style>
        .container.padding{
            margin:auto;
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
    <div class="container padding">
   
      <!-- Main component for a primary marketing message or call to action -->
<!-- Requires jQuery -->

	  
		<div id="compare" >
		</div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    	    
 <!-- <script language="javascript" type="text/javascript" src="include/jquery-latest.js"></script>-->
 	<!-- Requires CodeMirror 2.16 -->
	<script type="text/javascript" src="mergely/codemirror.js"></script>
	
	<!-- Requires Mergely -->
	<script type="text/javascript" src="mergely/mergely.js"></script>
	
	<script type="text/javascript">
        $(document).ready(function () {
			$('#compare').mergely({
				cmsettings: { readOnly: false, lineWrapping: true }
			});
			$.ajax({
				type: 'GET', async: true, dataType: 'text',
				url: 'getsource.php?id=<?php echo intval($_GET['left'])?>',
				success: function (response) {
					$('#compare').mergely('lhs', response);
					$('#compare').mergely('resize')
				}
			});
			$.ajax({
				type: 'GET', async: true, dataType: 'text',
				url: 'getsource.php?id=<?php echo intval($_GET['right'])?>',
				success: function (response) {
					$('#compare').mergely('rhs', response);
					$('#compare').mergely('resize')
				}
			});
		});
	</script>
<?php include("template/$OJ_TEMPLATE/bottom.php"); ?>
 </body>
</html>
