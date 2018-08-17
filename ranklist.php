<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Ranklist -- <?php echo $OJ_NAME ?></title>
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <style>
        .center.head{
            text-align:center!important;
        }
    </style>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="include/sortTable.js"></script>
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<script type="text/x-template" id="ranklist_template">
<div>
    <div class="ui stack segment">

    <div class="ui grid">
    
    <div class="row">
    <div class="four wide column">
    <div class="ui mini statistic">
                    <div class="value">
                        <i class="user icon"></i>{{registed_user}}
                    </div>
                    <div class="label">
                        Registered
                    </div>
                </div>
                <div class="ui mini statistic">
                    <div class="value">
                        <i class="user circle outline icon"></i>{{acm_user}}
                    </div>
                    <div class="label">
                        ACMER
                    </div>
                </div>
                </div>
                <div class="five wide column">
                <div class="ui search">
                    <label>{{_name.user}}</label>
                    <div class="ui input">
                        <input name="user" @keyup="search_user($event)">
                    </div>
                </div>
                </div>
                <div class="seven wide column  center aligned">
                <a :class="'ui blue mini button '+(time_stamp === 'D'?'disabled':'')" @click="timestamp('D',$event)">Day</a>
                <a :class="'ui blue mini button '+(time_stamp === 'W'?'disabled':'')" @click="timestamp('W',$event)">Week</a>
                <a :class="'ui blue mini button '+(time_stamp === 'M'?'disabled':'')" @click="timestamp('M',$event)">Month</a>
                <a :class="'ui blue mini button '+(time_stamp === 'Y'?'disabled':'')" @click="timestamp('Y',$event)">Year</a>
                <a :class="'ui blue mini button '+(time_stamp === ''?'disabled':'')" @click="timestamp('',$event)">Total</a>
                </div>
    </div>
    </div>
    </div>
    <div class="ui stack segment">
    <table style="width:100%" class="ui padded borderless selectable table">
    <thead>
        <tr>
            <th width="7%" class="center head">{{_name.rank}}</th>
            <th width="10%" class="center head"><b>{{_name.user}}</b></th>
            <th width="3%"></th>
            <th width="15%"><b>{{_name.nick}}</b></th>
            <th width="55%" class="center head">个人介绍</th>
            <th width="10%"  class="center head"><b>{{_name.accept}}</b></th>
            <!--<th width="7%"><b>{{_name.vjudge_accept}}</b></th>
            <th width="7%"><b>{{_name.submit}}</b></th>
            <th width="7%"><b>{{_name.ratio}}</b></th>-->
        </tr>
    </thead>
    <tbody>
        <tr v-for="(row,key,index) in ranklist">
            <td  class="center head">{{page*50+key+1}}</td>
            <td class="center head"><a :href="'/userinfo.php?user='+row.user_id" target="_blank">{{row.user_id}}</a></td>
            <td>
                <img class="ui avatar image" :src="'../avatar/'+row.user_id+'.jpg'" v-if="row.avatar" style="object-fit: cover;">
            </td>
            <td>
                    {{row.nick}}
            </td>
            <td class="center head" v-html="markdownIt.renderRaw(row.biography||'')"></td>
            <td class="center head"><a :href="'status.php?user_id='+row.user_id+'&jresult=4'">{{row.solved||0}}</a></td>
            <!--<td><a :href="'hdu_status.php?user_id='+row.user_id+'&jresult=4'">{{row.vjudge_solved||0}}</a></td>
            <td><a :href="'status.php?user_id='+row.user_id">{{row.submit||0}}</a></td>
            <td>{{(((row.solved*100/(row.submit||0)||0)).toString().substring(0,5)+"%")}}</td>-->
        </tr>
    </tbody>
</table>
</div>
<a v-cloak :class="'ui button '+(page == 0?'disabled':'')" @click="page != 0 && _page(-page,$event)" class="ui button">
    Top
</a>

<a @click="page&&_page(-1,$event)" class="ui left labeled icon button" :class="'ui left labeled icon button '+(page > 0?'':'disabled')" >
    <i class="left arrow icon"></i>
    Prev
</a>
<a @click="page*50<registed_user&&_page(1,$event)" class="ui right labeled icon button" :class="'ui right labeled icon button '+((page+1)*50>=registed_user?'disabled':'')">
     <i class="right arrow icon"></i>
    Next
</a>
</div>
</script>
<div class="ui container padding">
    <!-- Main component for a primary marketing message or call to action -->
    <div>
        <h2 class="ui dividing header">
            Rank List
        </h2>
        <ranklist :data="ranklist"></ranklist>

    </div>

</div> <!-- /container -->

<?php include("template/$OJ_TEMPLATE/bottom.php"); ?>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script>
Vue.component("ranklist",{
    template:"#ranklist_template",
    props:{
       data:Object 
    },
    data:function(){
        return {
            registed_user:0,
            acm_user:0,
            page:parseInt(getParameterByName("page"))||0,
            search:getParameterByName("search")||"",
            time_stamp:""
        };
    },
    computed:{
       _name:function(){
           return this.data._name;
       },
       ranklist:function(){
           return this.data.ranklist;
       }
    },
    methods:{
        search_user:function($event){
            var that = this;
            this.search = $event.target.value;
            $.get("/api/ranklist?page="+this.page+"&search="+this.search+"&time_stamp="+this.time_stamp,function(data){
                that.data = data;
            })
        },
        timestamp:function(time,$event){
            var that = this;
            this.time_stamp = time;
            $.get("/api/ranklist?page="+this.page+"&search="+this.search+"&time_stamp="+this.time_stamp,function(data){
                that.data = data;
            })
        },
        _page:function(diff,$event){
            this.page += diff;
            var that = this;
            $.get("/api/ranklist?page="+this.page+"&search="+this.search+"&time_stamp="+this.time_stamp,function(data){
                that.data = data;
            })
        }
    },
    mounted:function(){
        var that = this;
        $.get("/api/ranklist/user",function(data){
            that.registed_user = data[0].tot_user;
            that.acm_user = data[0].acm_user;
        })
    }
});
$.get("/api/ranklist",function(data){
    window.ranklist = new Vue({
    el:".ui.container.padding",
    data:{
        ranklist:data
    },
    
})
})

</script>
</body>
</html>
