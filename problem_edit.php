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
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>
    <?php include("template/$OJ_TEMPLATE/extra_css.php") ?>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <?php include("template/$OJ_TEMPLATE/extra_js.php") ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php");
include("csrf.php");
?>
<div class="container">
    <!-- Main component for a primary marketing message or call to action -->
    <div class="ui container padding" v-cloak>
        <div class="ui grid">
            <div class="row">
                <h2 class="ui header">
                    Title
                </h2>
            </div>
            <div class="row">
                <div class="ui input">
                  <input type="text" v-model="title">
                </div>
            </div>
            <div class="row">
                        <div class="ui labeled input">
                            <div class="ui label">
                            Time
                              </div>
                            <input type="text" v-model="time">
                        </div>
                        <div class="ui labeled input">
                            <div class="ui label">
                            Memory
                              </div>
                            <input type="text" v-model="memory">
                        </div>
                        
                        <select multiple class="ui search dropdown label selection"  @change="label = $('.label.selection.ui.dropdown').dropdown('get value');">
                            <option v-for="lb in all_label" :value="lb" v-text="lb"></option>
                        </select>
            </div>
            <div class="row">
                <h2 class="ui header">
                    Description
                </h2>
                </div>
                <div class="row">
                <mavon-editor v-model="description"></mavon-editor>
                </div>
                <br>
                <div class="row">
                <h2 class="ui header">
                    Input
                </h2>
                </div>
                <div class="row">
                <mavon-editor v-model="input"></mavon-editor>
                </div>
                <br>
                <div class="row">
                <h2 class="ui header">
                    Output
                </h2>
                </div>
                <div class="row">
                <mavon-editor v-model="output"></mavon-editor>
                </div>
                <br>
                <div class="row">
                <h2 class="ui header">
                    Sample Input
                </h2>
                </div>
                <div class="row">
                    <mavon-editor v-model="sampleinput"></mavon-editor>
                </div>
                <br>
                <div class="row">
                <h2 class="ui header">
                    Sample Output
                </h2>
                </div>
                <div class="row">
                <mavon-editor v-model="sampleoutput"></mavon-editor>
                </div>
                <div class="row"  v-if="from === 'local'">
                <h2 class="ui header">
                    Hint
                </h2>
                </div>
                <div class="row" v-if="from === 'local'">
                <mavon-editor v-model="hint"></mavon-editor>
                </div>
                <a class="ui button" @click="submit">提交</a>
            </div>
            
        </div>
    </div>
</div> <!-- /container -->
<script>
    var id = getParameterByName("id");
    var from = getParameterByName("from")||"local";
    if(id){
        $.get("/api/problem/"+from+"?id="+id+"&raw",function(data){
            var d = data.problem;
            window.editor = new Vue({
                el:".ui.container.padding",
                data:function(){
                    return {
                        title:d.title,
                        description:d.description,
                        time:d.time_limit,
                        memory:d.memory_limit,
                        input:d.input,
                        output:d.output,
                        sampleinput:d.sample_input,
                        sampleoutput:d.sample_output,
                        hint:d.hint,
                        source:from,
                        label:d.label?d.label.split(" "):[],
                        all_label:[]
                    }
                },
                methods:{
                    submit:function(){
                        var send_obj={};
                        for(var i of this.$children)
                        {
                            var target = i.$vnode.data.model;
                            send_obj[target.expression] = target.value;
                        }
                        
                        send_obj["time"] = this.time;
                        send_obj["memory"] = this.memory;
                        send_obj["title"] = this.title;
                        var labels = this.label;
                        function unique(array) {
                            var res = array.filter(function(item, index, array){
                                return array.indexOf(item) === index;
                        })
                            return res;
                        }
                        
                        send_obj["label"] = unique(labels).join(" ");
                        $.post("/api/problem/"+this.source+"/"+id,{json:send_obj},function(data){
                            if(data.status == "OK"){
                                $.get("/api/problem/"+from+"?id="+id);
                                alert("提交成功");
                            }
                        });
                    }
                },
                mounted:function(){
                    var that = this;
                    $.get("/api/problem/"+this.source+"/?label=true",function(data){
                        that.all_label = data.data;
                        var has_label = that.label;
                        $('.label.selection.ui.dropdown')
                          .dropdown({
                            allowAdditions: true
                          })
                          .on("click",function(){
                              that.label = $('.label.selection.ui.dropdown').dropdown('get value');
                          });
                        for(var i = 0;i<has_label.length;++i) {
                            $('.label.selection.ui.dropdown').dropdown('set selected',has_label[i]);
                        }
                        
                    })
                }
            });
            console.log(d);
        });
    }
</script>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

    <?php include("template/$OJ_TEMPLATE/bottom.php") ?>
</body>
</html>
