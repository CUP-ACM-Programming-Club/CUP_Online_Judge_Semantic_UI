<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title><?php echo $view_title?></title>
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
    <div class="padding container">
                <h2 class="ui header center aligned">ACM Rating Board</h2>
                <h4 class="ui header center aligned">10分钟更新一次榜单</h4>
        <div class="ui two column grid center aligned">
            <div class="eight wide column center aligned">
                
                <?php echo $view_table; ?>
            </div>
            <div class="seven wide column center aligned">
                <table id="acmsubmit" class="ui fixed single line celled table center aligned" v-cloak>
                    <thead>
                        <th width="20%">用户名</th>
                        <th width="20%">昵称</th>
                        <th>题目</th>
                        <th>提交时间</th>
                    </thead>
                    <tbody>
                        <tr v-for="row in table">
                            <td>{{row.user_id}}</td>
                            <td><a :href="'userinfo.php?user='+row.user_id" target="_blank">{{row.nick}}</a></td>
                            <td><a target="_blank" :href="getProblemSource(row.oj_name,row.problem_id)">{{row.oj_name+" "+row.problem_id}}</a></td>
                            <td>{{new Date(row.time).toLocaleString()}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> <!-- /container -->
<?php include("template/$OJ_TEMPLATE/bottom.php");?>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script language="javascript" type="text/javascript" src="include/jquery.flot.js"></script>
<script>
$.get("../api/acmsubmit",function(d){
    var acmsubmit = window.acm = new Vue({
        el:"#acmsubmit",
        data:{
             table:d.data
        },
        computed:{
            tables:{
                get:function(){
                    return {
                        list:this.table
                    }
                },
                set:function(val){
                    this.table = val.splice(0);
                }
            }
        },
        methods:{
            getProblemSource:function(oj,id) {
                switch (oj.toUpperCase()) {
                    case "LOCAL":
                        return "/newsubmitpage.php?id="+id;
                    case "HDU":
                        return "http://acm.hdu.edu.cn/showproblem.php?pid="+id;
                    case "POJ":
                        return "http://poj.org/problem?id="+id;
                    case "JSK":
                        return "https://nanti.jisuanke.com/t/"+id;
                    case "HUSTOJ_UPC":
                        return "http://exam.upc.edu.cn/problem.php?id="+id;
                    case "UVA":
                        return "/uvasubmitpage.php?id="+id;
                    case "ATCODER":
                        return "https://vjudge.net/problem/AtCoder-"+id;
                    default:
                        return "javascript:void(0);";
                }
            }
        },
        mounted:function(){
        }
    })
})
    
</script>
</body>
</html>
