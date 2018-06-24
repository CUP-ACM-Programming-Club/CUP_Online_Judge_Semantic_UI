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
              <div class="ui piled segment">
                      <div id="bar_chart_language_legend" align="center">
                   </div>
                  <div id="bar-holder" style="width:100%" align="center">
                    <canvas id="bar-area" />
                    </div>
              </div>
              <div class="ui piled segment">
                  <div id="memory_bar_chart_language_legend" align="center">
                      
                  </div>
                  <div id="memory_bar_holder" style="width:100%" align="center">
                      <canvas id="memory_bar_area" />
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
                self:"",
                time_range:{},
                memory_range:{}
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
                        language_name:this.language_name,
                        time_range:this.time_range,
                        memory_range:this.memory_range
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
                    this.time_range = val.data.time_range;
                    this.memory_range = val.data.memory_range;
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
            var current_title = $("title").text();
            $("title").text("Status:Problem "+this.pid +" - "+current_title);
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
                var ncolor = ['#3366CC','#DC3912','#FF9900','#109618','#990099','#3B3EAC','#0099C6','#DD4477','#66AA00','#B82E2E','#316395','#994499','#22AA99','#AAAA11','#6633CC','#E67300','#8B0707','#329262','#5574A6','#3B3EAC'];
                _.forEach(ncolor,function(val){
                    colors.push(val);
                })
                    var config = {
        type: 'pie',
        data: {
            datasets: [{
                data: _.map(that.submitStatus.problem_status,function(val,index){return val}),
                backgroundColor: colors,
                label: 'Status'
            }],
            labels: _.map(that.submitStatus.problem_status,function(val,index){return that.submitStatus.statistic_name[index]})
        },
        options: {
            responsive: true
        }
    };
                var lang = {};
                var labels = {};
                _.forEach(that.submitStatus.time_range,function(val,index){
                    labels[val.diff] = true;
                    lang[val.language] = {};
                })
                labels = _.map(labels,function(val,index){
                    var arr = index.split("-");
                    return arr[0] + "ms - " + arr[1] + "ms";
                });
                labels.sort(function(a,b){
                    var s = parseFloat(a.split("-")[0]);
                    var t = parseFloat(b.split("-")[0]);
                    return s-t;
                })
                _.forEach(lang,function(val,index){
                    _.forEach(labels,function(v,idx){
                        lang[index][v] = 0;
                    })
                })
                
                _.forEach(that.submitStatus.time_range,function(val,index){
                    var arr = val.diff.split("-");
                    var diffstr =  arr[0] + "ms - " + arr[1] + "ms";
                    lang[val.language][diffstr] = val.total;
                })
                var _colors = _.map(colors,function(val){return val;});
                var config2 = {
                    type: 'bar',
                    labels:labels,
                    datasets:_.map(lang,function(val,index){
                            return {
                                label: that.submitStatus.language_name[index],
                                backgroundColor: _colors.shift(),
                                data:_.values(val)
                        }
                    })
                }
                var mlabels = {};
                var mlang = {};
                _colors = _.map(colors,function(val){return val;});
                _.forEach(that.submitStatus.memory_range,function(val,index){
                    mlabels[val.diff] = true;
                    mlang[val.language] = {};
                })
                mlabels = _.map(mlabels,function(val,index){
                    var arr = index.split("-");
                    arr[0] = (parseFloat(arr[0]) / 1024).toFixed(2);
                    arr[1] = (parseFloat(arr[1]) / 1024).toFixed(2);
                    return arr[0] + "MB - " + arr[1] + "MB";
                })
                mlabels.sort(function(a,b){
                    var s = parseFloat(a.split("-")[0]);
                    var t = parseFloat(b.split("-")[0]);
                    return s - t;
                })
                _.forEach(mlang,function(val,index){
                    _.forEach(mlabels,function(v,idx){
                        mlang[index][v] = 0;
                    })
                })
                
                _.forEach(that.submitStatus.memory_range,function(val,index){
                    var arr = val.diff.split("-");
                    arr[0] = (parseFloat(arr[0]) / 1024).toFixed(2);
                    arr[1] = (parseFloat(arr[1]) / 1024).toFixed(2);
                    var diffstr =  arr[0] + "MB - " + arr[1] + "MB";
                    mlang[val.language][diffstr] = val.total;
                })
                var config3 = {
                    type: 'bar',
                    labels:mlabels,
                    datasets:_.map(mlang,function(val,index){
                            return {
                                label: that.submitStatus.language_name[index],
                                backgroundColor: _colors.shift(),
                                data:_.values(val)
                        }
                    })
                }
                var ctx = document.getElementById("chart-area").getContext("2d");
                window.myPie = new Chart(ctx, config);
                var btx = document.getElementById("bar-area").getContext("2d");
                var mtx = document.getElementById("memory_bar_area").getContext("2d");
                window.myBar = new Chart(btx, {
				type: 'bar',
				data: config2,
				options: {
					title: {
						display: true,
						text: 'AC代码运行用时'
					},
					tooltips: {
						mode: 'index',
						intersect: true
					},
					responsive: true,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					}
				}
			});
			window.myMemory = new Chart(mtx, {
				type: 'bar',
				data: config3,
				options: {
					title: {
						display: true,
						text: 'AC代码内存使用'
					},
					tooltips: {
						mode: 'index',
						intersect: false
					},
					responsive: true,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					}
				}
			});
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
