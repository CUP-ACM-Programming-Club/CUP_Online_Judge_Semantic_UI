<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <?php include("template/$OJ_TEMPLATE/css.php");?>
    <!-- Site Properties -->
    <title>ProblemSet -- CUP Online Judge Virtual Judge </title>
    <?php include("template/$OJ_TEMPLATE/js.php");?>
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<div class="ui padding container pusher">
    <h2 class="ui dividing header">Problem Set</h2>
        <center>
            <!--<h3>如题目未被爬取，请输入题号，按Enter进入题目</h3>-->
        <div class="ui search">
                <form class="form-inline" action="vjudgeproblemset.php">
                <div class="ui icon input">
                    <input class="prompt" type="text" name="search" placeholder="Problem,ID or Keyword">
                    <i class="search icon"></i>
                </div>
                <button class="ui button">Search</button>
                <div class="results"></div>
                </form>
            </div>
        </center>
        <br>
        <div align="center">
        <div class="ui pagination menu">
            <a class="<?php if($page==1) echo "disabled"; ?> icon item" id="page_prev" href="<?php if(intval($page)>1) echo "vjudgeproblemset.php?page=".($page-1);else echo "javascript:void(0)"; ?>">
                <i class="left chevron icon"></i>
            </a>
            <br>
                <?php
                for ($i=max($page-4,1);$i<=min(max($page+4,10),$view_total_page);$i++)
                {
                    if($i==$page)
                        echo "<a class='active item' href=''#'>$i</a>";
                    else
                        echo "<a class='item' href='vjudgeproblemset.php?page=".$i."'>".$i."</a>";
                }
                ?>
            <a class="<?php if(intval($page)==intval($view_total_page)) echo "disabled"; ?> icon item" href="<?php if(intval($page)<intval($view_total_page)) echo "vjudgeproblemset.php?page=".(intval($page)+1);else echo "javascript:void(0)"; ?>" id="page_next">
                <i class="right chevron icon"></i>
            </a>
        </div>
        </div>
        <br>
            <div class="ui toggle checkbox" id="show_tag">
          <style id="show_tag_style"></style>
          <script>
          if (localStorage.getItem('show_tag') === '1') {
            document.write('<input type="checkbox" checked>');
            document.getElementById('show_tag_style').innerHTML = '.show_tag_controled { white-space: nowrap; overflow: hidden; }';
          } else {
            document.write('<input type="checkbox">');
            document.getElementById('show_tag_style').innerHTML = '.show_tag_controled { width: 0; white-space: nowrap; overflow: hidden; }';
          }
          </script>
          <label>显示分类标签</label>
          </div>
          <script>
          $(function () {
            $('#show_tag').checkbox('setting', 'onChange', function () {
              let checked = $('#show_tag').checkbox('is checked');
              localStorage.setItem('show_tag', checked ? '1' : '0');
              if (checked) {
                document.getElementById('show_tag_style').innerHTML = '.show_tag_controled { white-space: nowrap; overflow: hidden; }';
              } else {
                document.getElementById('show_tag_style').innerHTML = '.show_tag_controled { width: 0; white-space: nowrap; overflow: hidden; }';
              }
            });
          });
          </script>
            <table id='problemset' width='90%'class='ui trow unstackable very basic center align large table'>
                <thead >
                <tr class='toprow'>
                    <th width='5%'></th>
                    <th width='9%' ><?php echo $MSG_PROBLEM_ID?></th>
                    <th width='66%'><?php echo $MSG_TITLE?></th>
                    <th class='hidden-xs' width='10%'><?php echo $MSG_SOURCE?></th>
                    <th style="cursor:hand" width=6% ><?php echo $MSG_AC?></th>
                    <th style="cursor:hand" width=6% ><?php echo $MSG_SUBMIT?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $cnt=0;
                foreach($view_problemset as $row){
                    if ($cnt)
                        echo "<tr style='vertical-align:middle'>";
                    else
                        echo "<tr style='vertical-align:middle'>";
                    $i=0;
                    foreach($row as $table_cell){
                        if($i==1||$i==3)echo "<td  class='hidden-xs'>";
                        else echo "<td>";
                        echo "\t".$table_cell;
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

<?php include("template/$OJ_TEMPLATE/bottom.php");?>
</body>
</html>