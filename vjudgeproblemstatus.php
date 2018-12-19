<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    
    <title><?php echo $OJ_NAME?></title>  
    <?php include("template/$OJ_TEMPLATE/css.php");?>	    
<?php include("template/$OJ_TEMPLATE/js.php");?>
<script src="/template/semantic-ui/js/Chart.bundle.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>	    
    <div>
    <?php
    $color=["black","black","black","green","red","yellow","yellow","yellow","yellow"];
    ?>
      <!-- Main component for a primary marketing message or call to action -->
      
      <div class="ui container padding">
          <h1 class="ui dividing header">
              Problem <?=$id?> Status
              </h1>
              <div class="ui stacked segment">
                      <div class="ui statistics" >
                      <?php
                      $cnt=0;
                      foreach($view_problem as $row){ 
                      ?>
                      <div class="<?=$color[$cnt++]?> statistic">
                          <div class="value">
                              <?=$row[1]?>
                          </div>
                          <div class="label">
                              <?=$row[0]?>
                          </div>
                      </div>
                      <?php } ?>
                  </div>
                  </div>
                  <div class="ui piled segment">
                      <div id="pie_chart_language_legend" align="center">
                   </div>
                  <div id="canvas-holder" style="width:100%" align="center">
        <canvas id="chart-area" />
    </div>
              </div>
          <div class="ui grid">
              <div class="eight wide column">
                  
              </div>
              
              <div class="seven wide column">
                  
              </div>
          </div>
          
<h1 class="ui dividing header">Submissions</h1>
<table id=problemstatus class="ui striped table"><thead>
<tr class=toprow><th style="cursor:hand"><?php echo $MSG_Number?>
<th>RunID
<th><?php echo $MSG_USER?>
<th ><?php echo $MSG_MEMORY?>
<th ><?php echo $MSG_TIME?>
<th><?php echo $MSG_LANG?>
<th ><?php echo $MSG_CODE_LENGTH?>
<th><?php echo $MSG_SUBMIT_TIME?></tr></thead><tbody>
<?php
$cnt=0;

foreach($view_solution as $row){
if ($cnt)
echo "<tr class='oddrow'>";
else
echo "<tr class='evenrow'>";
foreach($row as $table_cell){
echo "<td>";
echo "\t".$table_cell;
echo "</td>";
}
echo "</tr>";
$cnt=1-$cnt;
}
?>
</table>
<?php
echo "<a href='problemstatus.php?id=$id'>[TOP]</a>";
echo "&nbsp;&nbsp;<a href='status.php?problem_id=$id'>[STATUS]</a>";
if ($page>$pagemin){
$page--;
echo "&nbsp;&nbsp;<a href='problemstatus.php?id=$id&page=$page'>[PREV]</a>";
$page++;
}
if ($page<$pagemax){
$page++;
echo "&nbsp;&nbsp;<a href='problemstatus.php?id=$id&page=$page'>[NEXT]</a>";
$page--;
}
?>


      </div>

    </div> <!-- /container -->
    <?php include("template/$OJ_TEMPLATE/bottom.php") ?>
<script>
    var config = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    ...$(".value").text().trim().split("\n").map(function(data){return data.trim()}).slice(3)
                ],
                backgroundColor: [
                    ...Object.values(window.chartColors),
                            "#af63f4",
                            "#00b5ad",
                            "#350ae8",
                            "#E2EAE9"
                ],
                label: 'Status'
            }],
            labels: [
                ...$(".label").text().trim().split("\n").map(function(data){return data.trim()}).slice(3)
            ]
        },
        options: {
            responsive: true
        }
    };

    window.onload = function() {
        var ctx = document.getElementById("chart-area").getContext("2d");
        window.myPie = new Chart(ctx, config);
     //  $("#pie_chart_language_legend").html(myPie.generateLegend());
    };

    </script>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    	    
  </body>
</html>
