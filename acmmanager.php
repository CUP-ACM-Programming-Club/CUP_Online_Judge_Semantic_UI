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
                <h4 class="ui header center aligned">注:华东OJ(HUSTOJ_UPC)无法爬取普通提交，故HUSTOJ_UPC所有提交均为正确</h4>
        <div class="ui two column grid center aligned">
            <div class="six wide column center aligned source_table">
                
                <?php echo $view_table; ?>
            </div>
            <div class="ten wide column center aligned">
                <table id="acmsubmit" class="ui fixed single line celled table center aligned" v-cloak>
                    <thead>
                        <th width="18%" v-if="!current_person">用户名</th>
                        <th v-if="!current_person" width="13%">昵称</th>
                        <th width="10%">题目</th>
                        <th width="15%">提交时间</th>
                        <th width="6%" v-if="current_person">提交语言</th>
                        <th width="11%" v-if="current_person">结果</th>
                        <th width="6%" v-if="current_person">运行时间</th>
                        <th width="6%" v-if="current_person">运行内存</th>
                        <th width="6%" v-if="current_person">代码长度</th>
                    </thead>
                    <tbody>
                        <tr v-for="row in table" v-if="!current_person || (current_person && current_person === row.user_id)">
                            <td v-if="!current_person">{{row.user_id}}</td>
                            <td v-if="!current_person"><a :href="'userinfo.php?user='+row.user_id" target="_blank">{{row.nick}}</a></td>
                            <td><a target="_blank" :href="getProblemSource(row.oj_name,row.problem_id)">{{row.oj_name+" "+(isNaN(row.problem_id) ? row.problem_id :Math.abs(row.problem_id))}}</a></td>
                            <td>{{new Date(row.time).toLocaleString()}}</td>
                            <td v-if="current_person">{{isNaN(row.language)?row.language:parseLang(row.oj_name,row.language)}}</td>
                            <td v-if="current_person" :class="judge_color[row.result]"><i :class="icon_list[row.result]+' icon'"></i>{{result_parse[row.result]}}</td>
                            <td v-if="current_person">{{row.time_running}}ms</td>
                            <td v-if="current_person">{{row.memory}}KB</td>
                            <td v-if="current_person">{{row.code_length}}B</td>
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
             table:d.data,
             current_person:false,
             result_parse:d.const_data,
             language_name:d.language_name,
             judge_color:d.judge_color,
             icon_list:d.icon_list
        },
        watch: {
            current_person:function(val) {
                var that = this;
                $.get("../api/acmsubmit?name="+this.current_person,function(data){
                    that.table = data.data;
                })
                $.get("../api/acmsubmit?name="+this.current_person,function(data){
                    that.table = data.data;
                })
            }
        },
        computed:{
            tables:{
                get:function(){
                    var person = this.current_person;
                    if(!person) {
                        return {
                        list:this.table
                        }
                    }
                    var ntable = [];
                    _.forEach(this.table,function(val,index){
                        if(val.nick == person) {
                            ntable.push(val);
                        }
                    })
                    return {
                        list:ntable
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
            },
            parseLang: function(oj_name,val) {
                if(oj_name.toLowerCase() === "cup") oj_name = "local";
                oj_name = oj_name.toLowerCase();
                return this.language_name[oj_name][val];
            }
        },
        mounted:function(){
            var that = this;
            $.get("../api/acmsubmit",function(d){
                that.table = d.data;
            })
        }
    })
})
    $(".source_table").on("click",function(e){
        $("tr").removeClass("active");
        var parent = $(e.target).parent();
        parent.addClass("active");
        window.acm.current_person = parent.find("td").eq(1).text();
    })
</script>
</body>
</html>
