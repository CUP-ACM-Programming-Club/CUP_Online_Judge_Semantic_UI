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
<script type="text/javascript" src="include/jquery.tablesorter.js"></script>
<script>
	    $(document).ready(function()
    {
    $("#result-tab").tablesorter();
}
);
	</script>
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
      <div>
<div align=center class="input-append">
</div>
          <br>
<div id=center>
<table id=result-tab class="ui padded celled selectable table" align=center width=80%>
<thead>
<tr class='toprow' >
<th ><?php echo "运行编号"?>
<th ><?php echo "题号"?>
<th ><?php echo "用户"?>
<th ><?php echo "被抄袭用户"?>
</tr>
</thead>
<tbody>
<?php
$cnt=0;
foreach($result as $row){
if ($cnt)
echo "<tr class='oddrow'>";
else
echo "<tr class='evenrow'>";
$i=0;
$cnt=count($row);
for($a=0;$a<$cnt/2;$a++){
	if($i>3&&$i!=8)
		echo "<td class='hidden-xs'>";
	else
		echo "<td>";
		if($a==2)
		echo "<a href='newsubmitpage.php?id=".$row[$a]."'>";
		else if($a>=3)
		echo "<a href='userinfo.php?user=".$row[$a]."'>";
		else if($a==0)
		echo "<a class='ui button blue' href='comparesource.php?left=".$row[$a]."&right=".$row[$a+1]."'>";
	if($a==0)
	echo "代码对比(".$row[0].",".$row[1].")";
	else
	echo $row[$a];
	echo "</a>";
	if($a==0)$a=1;
	echo "</td>";
	$i++;
}
echo "</tr>";
$cnt=1-$cnt;
}
?>
</tbody>
</table>
</div>
<div id=center>
<?php echo "[<a href=status.php?".$str2.">Top</a>]&nbsp;&nbsp;";
if (isset($_GET['prevtop']))
echo "[<a href=status.php?".$str2."&top=".intval($_GET['prevtop']).">Previous Page</a>]&nbsp;&nbsp;";
else
echo "[<a href=status.php?".$str2."&top=".($top+20).">Previous Page</a>]&nbsp;&nbsp;";
echo "[<a href=status.php?".$str2."&top=".$bottom."&prevtop=$top>Next Page</a>]";
?>
</div>

      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script>

	    $('.toggle.checkbox')
  .checkbox()
  .first().checkbox({
    onChecked: function() {
      timeago(null, 'zh_CN').render($('.need_to_be_rendered'));
    },
     onUnchecked: function() {
         timeago.cancel();
      var render=document.querySelectorAll(".need_to_be_rendered");
      render.forEach(function(e){
          e.innerHTML=e.getAttribute("datetime");
      });
    }
  })
;
		var judge_result=[<?php
		foreach($judge_result as $result){
		echo "'$result',";
		}
		?>''];

		var judge_color=[<?php
		 foreach($judge_color as $result){
		 echo "'$result',";
		 }
		?>''];
	</script>
	<script src="template/<?php echo $OJ_TEMPLATE?>/auto_refresh.js" ></script>
	<?php include("template/$OJ_TEMPLATE/bottom.php");?>
  </body>
</html>
