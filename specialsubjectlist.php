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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
 <?php include("template/$OJ_TEMPLATE/nav.php");?>	    
    <div class="pusher">
   
      <!-- Main component for a primary marketing message or call to action -->
      <div class="ui container padding">
<center>
<div>
<h3>专题<?php echo $tid?> - <?php echo $view_title ?></h3>
<p><?php echo $view_description?></p>

<?php
if ($view_private=='0')
echo "<span class=blue>Public</font>";
else
echo "&nbsp;&nbsp;<span class=red>Private</font>";
?>
<br>

</div>
<table id='problemset' class='ui padded celled selectable table'  width='90%'>
<thead>
<tr align=center class='toprow'>
<th width='5'>
<th style="cursor:hand" onclick="sortTable('problemset', 1, 'int');" ><?php echo $MSG_PROBLEM_ID?>
<th width='50%'><?php echo $MSG_TITLE?></th>
<th width='10%'><?php echo $MSG_SOURCE?></th>
<th style="cursor:hand" onclick="sortTable('problemset', 4, 'int');" width='10%'><?php echo $MSG_AC?></td>
<th style="cursor:hand" onclick="sortTable('problemset', 5, 'int');" width='10%'><?php echo $MSG_SUBMIT?></th>
</tr>
</thead>
<tbody>
<?php
$cnt=0;
foreach($view_problemset as $row){
if ($cnt)
echo "<tr class='oddrow' style='text-align: center'>";
else
echo "<tr class='evenrow' style='text-align: center'>";
foreach($row as $table_cell){
echo "<td>";
echo "\t".$table_cell;
echo "</td>";
}
echo "</tr>";
$cnt=1-$cnt;
}
?>
</tbody>
</table></center>
      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    	    
<script src="include/sortTable.js"></script>
<?php include("template/$OJ_TEMPLATE/bottom.php");?>
  </body>
</html>
