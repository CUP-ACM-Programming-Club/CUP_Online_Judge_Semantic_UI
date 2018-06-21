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
    $color=["black","black","black","green","red","yellow","yellow","yellow","yellow","yellow","yellow","yellow","","",""];
    ?>
      <!-- Main component for a primary marketing message or call to action -->
      
      <div class="ui container padding" v-cloak>
          <h1 class="ui dividing header">
              Problem {{pid}} Status
              </h1>
              <div class="ui stacked segment">
                      <div class="ui statistics">
                          <div class="black statistic">
                             <div class="value">
                                 {{submitStatus.total_status.total_submit}}
                             </div> 
                             <div class="label">
                                 总提交
                             </div>
                          </div>
                            <div class="black statistic">
                             <div class="value">
                                 {{submitStatus.total_status.total_solved_submit}}
                             </div> 
                             <div class="label">
                                 已提交用户
                             </div>
                          </div>
                          <div class="black statistic">
                             <div class="value">
                                 {{submitStatus.total_status.total_solved_user}}
                             </div> 
                             <div class="label">
                                 已通过用户
                             </div>
                          </div>
                          <div :class="submitStatus.color[index-1]+' statistic'" v-for="(row,index) in submitStatus.problem_status">
                              <div class="value">
                                  {{row}}
                              </div>
                              <div class="label">
                                  <a :href="'status.php?problem_id='+pid+'&jresult='+index" target="_blank">
                                  {{submitStatus.statistic_name[index]}}
                                  </a>
                              </div>
                          </div>
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
<table id=problemstatus class="ui table"><thead>
<tr class=toprow><th style="cursor:hand"><?php echo $MSG_Number?>
<th>RunID
<th><?php echo $MSG_USER?>
<th ><?php echo $MSG_MEMORY?>
<th ><?php echo $MSG_TIME?>
<th><?php echo $MSG_LANG?>
<th ><?php echo $MSG_CODE_LENGTH?>
<th><?php echo $MSG_SUBMIT_TIME?></tr></thead><tbody>
<tr v-for="(row,index) in submitStatus.solution_status">
    <td>{{current_page * 20 + index + 1}}</td>
    <td>{{row.solution_id}}</td>
    <td><a :href="'userinfo.php?user='+row.user_id" target="_blank">{{row.user_id}}</a></td>
    <td>{{row.memory}}KB</td>
    <td>{{row.time}}ms</td>
    <td>
        <a :href="'showsource.php?id='+row.solution_id" target="_blank" v-if="isadmin||row.user_id == owner">{{submitStatus.language_name[row.language]}}</a>
        <span v-else>{{submitStatus.language_name[row.language]}}</span>
        </td>
    <td>{{row.code_length}}B</td>
    <td>{{new Date(row.in_date).toLocaleString()}}</td>
</tr>
</table>
<br>
<a v-cloak class="ui button" :href="'status.php?problem_id='+this.pid" target="_blank">Status</a>
<a v-cloak :class="'ui button '+(current_page == 0?'disabled':'')" @click="current_page != 0 && page(-current_page,$event)">Top</a>
                <div class="ui buttons">
                    <button v-cloak :class="'ui left labeled icon button '+(current_page == 0?'disabled':'')"
                            @click="current_page!=0&&page(-1,$event)">
                        <i class="left arrow icon"></i>
                        Prev
                    </button>
                    <div class="or" v-cloak></div>
                    <button v-cloak :class="'ui right labeled icon button '+(submit_stat.length < 20?'disabled':'')"
                            @click="submit_stat.length == 20 && page(1,$event)">
                        <i class="right arrow icon"></i>
                        Next
                    </button>
                </div>
 </div>

    </div> <!-- /container -->
    <?php include("template/$OJ_TEMPLATE/bottom.php") ?>
<script>

    (function(env){
        var problemStatus = new Vue({
        el:".ui.container.padding",
        data:function(){
            return {
                pid:getParameterByName("id"),
                problem_stat:[],
                submit_stat:[],
                problem_submit_stat:{},
                stat_name:[],
                current_page:parseInt(getParameterByName("page")||0),
                language_name:[],
                isadmin:false,
                self:""
            }
        },
        computed:{
            submitStatus:{
                get:function(){
                    var prob_stat = {};
                    _.forEach(this.problem_stat,function(val,index){
                        prob_stat[val.result] = val.total;
                    })
                    return {
                        problem_status:prob_stat,
                        solution_status:this.submit_stat,
                        total_status:this.problem_submit_stat,
                        color:["black","black","black","green","red","yellow","yellow","yellow","yellow","yellow","yellow","yellow","","",""],
                        statistic_name:this.stat_name,
                        language_name:this.language_name
                    }
                },
                set: function(val){
                    var stat = val.data.problem_status
                    this.problem_stat = stat;
                    this.submit_stat = val.data.solution_status;
                    this.problem_submit_stat = val.data.submit_status;
                    this.stat_name = val.data.statistic_name;
                    this.language_name = val.data.language_name;
                    this.isadmin = val.data.isadmin;
                    this.owner = val.data.self;
                }
            }
        },
        methods:{
            page: function (num, $event) {
                this.current_page += num;
                var that = this;
                $.get("../api/problemstatus/"+this.pid+"?page="+this.current_page,function(data){
                    that.submitStatus = data;
                    that.setQuery();
                })
            },
            setQuery: function () {
                    var queryObject = {};
                    queryObject["id"] = this.pid;
                    if (this.current_page !== 0)
                        queryObject["page"] = this.current_page + 1;
                    else {
                        delete queryObject["page"];
                    }
                    var url = location.origin + location.pathname + "?" + $.param(queryObject);
                    if (url !== location.origin + location.pathname + "?")
                        history.pushState({}, 0, url);
                }
        },
        mounted:function(){
            var that = this;
            this.current_page = Math.max(0,this.current_page-1);
            $.get("../api/problemstatus/"+this.pid+"?page="+this.current_page,function(data){
                if(data.status == "OK") {
                that.submitStatus = data;
                if(data.data.isadmin) {
                    env.submitStatus = problemStatus;
                }

                }
                else {
                    return ;
                }
                var colors = _.values(window.chartColors);
                colors.push("#af63f4");
                colors.push("#00b5ad");
                colors.push("#350ae8");
                colors.push("#E2EAE9");
                    var config = {
        type: 'pie',
        data: {
            datasets: [{
                data: _.map(that.submitStatus.problem_status,(val,index)=>{return val}),
                backgroundColor: colors,
                label: 'Status'
            }],
            labels: _.map(that.submitStatus.problem_status,(val,index)=>{return that.submitStatus.statistic_name[index]})
        },
        options: {
            responsive: true
        }
    };
                var ctx = document.getElementById("chart-area").getContext("2d");
                window.myPie = new Chart(ctx, config);
            })
        }
    })
    })(window);
    
    
    </script>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    	    
  </body>
</html>
