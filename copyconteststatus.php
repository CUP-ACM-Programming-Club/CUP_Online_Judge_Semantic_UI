<!DOCTYPE html>
<?php
$sql="SELECT muid,count(*) FROM (SELECT msg.num as pid,msg.solution_id as msid,solution.solution_id as csid,msg.user_id as muid,solution.user_id as cuid,msg.sim as msim FROM (SELECT * FROM (SELECT user_id,solution_id,num FROM solution WHERE contest_id =".intval($_GET['cid']).") solution left join `sim` on sim.s_id=solution.solution_id WHERE sim.s_id=solution.solution_id) msg left join `solution` on msg.sim_s_id=solution.solution_id WHERE msg.sim_s_id=solution.solution_id ) copy group by muid order by count(*) desc ";
$cnt_res=$database->query($sql)->fetchAll();
foreach($cnt_res as $row)
{
    $sim_arr[$row[0]]->total_cnt=$row[1];
    $sim_arr[$row[0]]->id=$row[0];
}
foreach($sim_arr as $value)
{
    $value->cnt=count($value->problem);
    $this_user_id=$value->id;
    $nick=$database->select("users","nick",[
        "user_id"=>$this_user_id
        ]);
        $value->nick=$nick[0];
}
if(isset($_GET['debug']))
{
   // echo var_dump($sim_arr);
    exit(0);
}
?>
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
<script type="text/javascript" src="include/jquery.tablesorter.js"></script>
<script>
	    $(document).ready(function()
    {
    $("#result-tab").tablesorter();
    $("#ranking_tab").tablesorter();
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
    <center><h2>点击表头可以排序</h2></center>
      <!-- Main component for a primary marketing message or call to action -->
      <div >
          <br>
<div id=center>
    <center>
    <?php if(isset($_SESSION['administrator'])){ ?>
<table id=result-tab class="ui padded celled selectable table" align=left style="float:left;width:50%">
<thead>
<tr class='toprow' >
    <th ><?php echo "题号"?>
<th ><?php echo "运行编号"?>
<th ><?php echo "抄袭用户"?>
<th ><?php echo "被抄袭用户"?>
<th ><?php echo "重复率"?>
</tr>
</thead>
<tbody>
<?php
$cnt=0;
if(isset($_GET['debug']))
{
    echo var_dump($result);
    exit(0);
}
foreach($result as $row){
    ?>
    <tr>
        <td><?php
        echo "<a href='newsubmitpage.php?cid=".intval($_GET['cid'])."&pid=".$row["pid"]."'>".chr(65+intval($row["pid"]));
        ?></td>
        <td><?php
		echo "<a class='ui button blue' href='comparesource.php?left=".$row[1]."&right=".$row[2]."'>";
	    echo "代码对比(".$row[1].",".$row[2].")";
	    echo "</a>";
        ?></td>
        <td>
        <a href='userinfo.php?user=<?=$row["muid"]?>'><?=$row["muid"]?><br><?=$row["mnick"]?></a>
        </td>
        <td>
            <a href='userinfo.php?user=<?=$row["cuid"]?>'><?=$row["cuid"]?><br><?=$row["cnick"]?></a>
        </td>
        <td>
            <?=$row["msim"]?>%
        </td>
        </tr>
    <?php
}
?>
</tbody>
</table>
<?php } ?>
<table id=ranking_tab class="ui padded celled selectable table" align=center style="width:40%;<?php if(isset($_SESSION['administrator'])){?>float:right;width:45%;<?php } ?>;margin:0;text-align:center;">
<thead>
<tr class='toprow' >
    <th ><?php echo "ID"?>
    <th ><?php echo "Nick"?>
<th ><?php echo "本次竞赛提交判重次数"?>
<th ><?php echo "最后一次提交判重次数"?>
<th ><?php echo "最后一次提交判重题目"?>
</tr>
</thead>
<tbody>
<?php
$cnt=0;
$sum=0;

foreach($sim_arr as $row)
{
    $cnt+=intval($row->total_cnt);
    $sum+=intval($row->cnt);
    echo "<tr>";
    echo "<td>";
    if(isset($_SESSION['administrator'])||$_SESSION['user_id']==$row->id){
    echo "<a href='userinfo.php?user=".$row->id."'>";
    echo $row->id;
    echo "</a>";
    }
    else
          echo "----";
     echo "</td>";
      echo "<td>";
      if(isset($_SESSION['administrator'])||$_SESSION['user_id']==$row->id){
      echo "<a href='status.php?user_id=".$row->id."&cid=".$cid."&jresult=4'>";
    echo $row->nick;
    echo "</a>";
      }
      else
          echo "----";
     echo "</td>";
     echo "<td>";
    echo $row->total_cnt;
     echo "</td>";
     echo "<td>";
    echo $row->cnt;
     echo "</td>";
     echo "<td>";
    foreach($row->problem as $problem)
    {
        echo "<a href='newsubmitpage.php?cid=".$cid."&pid=".$problem."'>";
        echo chr(65+$problem)." ";
        echo "</a>";
    }
     echo "</td>";
    echo "</tr>";
}
echo "<tr>";
echo "<td>合计：</td><td></td>";
echo "<td>".$cnt."</td>"."<td>".$sum."</td><td></td>";
?>
</tbody>
</table>
</center>
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
		function max(a,b)
		{
		    if(a>b)return a;
		    else return b;
		}
		$("#center").css({height:max($("#result-tab").height(),$("#ranking_tab").height())});
	</script>
	<?php include("template/$OJ_TEMPLATE/bottom.php");?>
  </body>
</html>
