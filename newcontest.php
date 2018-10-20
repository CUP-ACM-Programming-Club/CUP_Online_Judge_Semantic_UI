<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title><?php echo $OJ_NAME ?></title>
    <?php include("template/semantic-ui/css.php"); ?>
    <?php include("template/semantic-ui/js.php"); ?>
</head>

<body>
<?php include("template/semantic-ui/nav.php");?>
<script type="text/x-template" id="contest_detail">
    <div class="ui raised segment">
                    <h1></h1>
                    <h2 class="ui header" style="text-align:center"> <i class="star outline icon"></i>Contest {{cid}}</h2>
  <h2 class="ui header" style="text-align:center">{{title}}</h2>
 <p>{{description}}</p>
 <!--2018年 中国石油大学（北京）团委 不发ICPC/CCPC奖学金-->
 <center>Start Time {{start_time.format("YYYY-MM-DD HH:mm:ss")}}
<br> &nbsp;Now&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id=nowdate >{{now.format("YYYY-MM-DD HH:mm:ss")}}</span>
<br>End Time&nbsp;&nbsp;{{end_time.format("YYYY-MM-DD HH:mm:ss")}}
</center>
<div :class="'ui top right attached label ' + (private ?'red':'green')">{{private?'Private':'Public'}}</div>
<div :class="'ui top left attached ' + (now.isAfter(end_time)?'red':now.isBefore(start_time)?'grey':'green') + ' label'">
    <span class="red" v-if="now.isAfter(end_time)">Ended</span>
    <span class="red" v-else-if="now.isBefore(start_time)"></span>
    <span class="green" v-else>Running</span></div>
                <center>
                <div class="row padding">
                <div class="ui buttons mini">
                    <a class="ui button orange" :href="'copystatus.php?cid='+cid" v-if="admin">判重</a>
                </div>
                </div>
                </center>
</div>
</script>
<div class="ui container">
     <div class="padding ui container">
        <h2 class="ui dividing header">
            Contest Problem Set
        </h2>
        <div class="ui grid" id="contest_table">
            <div class="row">
                <div class="eleven wide column">
                    <table id='problemset' class='ui padded celled selectable table'  width='95%'>
                <thead>
                <tr align=center class='toprow'>
                    <th style="cursor:hand" onclick="sortTable('problemset', 1, 'int');" width="15%" ><?php echo $MSG_PROBLEM_ID?>
                    <th width='60%'><?php echo $MSG_TITLE?></th>
                    <th style="cursor:hand" onclick="sortTable('problemset', 4, 'int');" width='8%'>
                        正确</th>
                    <th style="cursor:hand" onclick="sortTable('problemset', 5, 'int');" width='8%'><?php echo $MSG_SUBMIT?></th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="row in problem_table">
                        <td>{{row.oj_name?row.oj_name:row.pid?"LOCAL ":""}}{{row.pid}}<br v-if="row.pid">Problem {{row.num + 1001}}</td>
                        <td><a :href="'newsubmitpage.php?cid='+cid+'&pid='+row.num">{{row.title}}</a></td>
                        <td>{{row.accepted}}</td>
                        <td>{{row.submit}}</td>
                    </tr>
                </tbody>
            </table>
                </div>
                <div class="five wide column">
            <div>
                <contest-detail :start_time="start_time"
                :end_time="end_time"
                :description="description"
                :admin="admin"
                :private="private"
                :title="title"
                :cid="cid"></contest-detail>
                <br>
            </div>
                </div>
            </div>
        </div>
            
    </div>
</div>
<script>
Vue.component("contest-detail",{
    template:"#contest_detail",
    props:{
        start_time:Object,
        end_time:Object,
        description:String,
        title:String,
        admin:Boolean,
        private:Boolean,
        cid:Number
    },
    data:function(){
        return {
            now:dayjs()
        };
    },
    mounted:function(){
        var that = this;
        setInterval(function(){
            that.now = dayjs();
        },1000);
    },
    methods:{
        
    }
})
    window.contestList = new Vue({
        el:"#contest_table",
        data: function(){
            
            return {
                cid:0,
                problem_table:[],
                start_time:dayjs(),
                end_time:dayjs(),
                description:"",
                title:"",
                contest_mode:0,
                admin:false,
                private:0
            }
        },
        computed:{
            
        },
        mounted:function(){
            var contest_id = getParameterByName("cid");
            var that = this;
            this.cid = parseInt(contest_id);
            $.get("/api/contest/problem/" + contest_id,function(_d){
                that.problem_table = _d.data;
                var info = _d.info;
                that.start_time = dayjs(info.start_time);
                that.end_time = dayjs(info.end_time);
                that.title = info.title;
                that.description = info.description;
                that.admin = _d.admin;
                that.contest_mode = info.contest_mode;
                that.private = Boolean(info.private);
            });
        },
        updated:function(){
            
        }
    })
</script>

<?php include("template/semantic-ui/bottom.php") ?>
</body>
</html>
