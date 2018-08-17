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
    <script src="/template/semantic-ui/js/clipboard.min.js"></script>
    <link href="/css/github-markdown.min.css" rel="stylesheet" type="text/css">
    <link href="/js/styles/github.min.css" rel="stylesheet" type="text/css">
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .subscript{
            font-size: 1rem;
        }
        .none-transform{
            text-transform: none !important;
        }
    </style>
</head>
<body>
<?php include("template/semantic-ui/nav.php");?>
    <div class="ui container padding" v-cloak>
        <h2 class="ui dividing header">
            Solution
            <div class="sub header">
                公测中
            </div>
        </h2>
        <div class="ui grid">
            <div class="row">
                <div class="thirteen wide column">
                </div>
                <div class="three wide right aligned column">
                    <a :href="'newtutorialpost.php?from='+source+'&id='+id"
                    target="_blank" class="ui labeled icon blue mini button"><i class="write icon"></i>
                        Post
                        </a>
                </div>
            </div>
        </div>
        <div class="ui grid" v-for="thread_head in content">
                <div class="four wide column">
                <div class="ui link card">
    <div class="image">
      <img v-if="thread_head.avatar === 1" :src="'../avatar/'+thread_head.user_id+'.jpg'" @click="location.href='userinfo.php?user='+thread_head.user_id">
      <img v-else src="../assets/images/wireframe/white-image.png">
    </div>
    <div class="content">
      <div class="header"><a class="black" :href="'userinfo.php?user='+thread_head.user_id" target="_blank">{{thread_head.nick}}</a></div>
      <div class="meta">
        <a :href="'userinfo.php?user='+thread_head.user_id" target="_blank">{{thread_head.user_id}}</a>
      </div>
      <div class="description" v-html="markdownIt.renderRaw(thread_head.biography||'')">
      </div>
    </div>
    <div class="extra content">
      <span class="right floated">
        
      </span>
      <span>
        <i class="user icon"></i>
        Solved {{thread_head.solved}}
      </span>
    </div>
  </div>
              </div>
              <div class="twelve wide column">
                 <div class="ui existing full segment">
                     <a v-if="thread_head.user_id + '' ===  owner" class="ui blue right ribbon label" :href="'tutorialedit.php?tutorial_id='+thread_head.tutorial_id+'&from='+thread_head.source.toLowerCase()+'&id='+thread_head.problem_id">Edit</a>
                     <div class="ui vertical segment" v-html="markdownIt.render(thread_head.content||'')"></div>
                     
                    <div class="ui raised segment" >
    <div class="ui tiny statistics">
        <div class="statistic">
            <div class="value none-transform">
                {{thread_head.time}}
                <span class="subscript">ms</span>
            </div>
            <div class="label none-transform">
                Running Time
            </div>
        </div>
        <div class="statistic">
            <div class="value none-transform">
                {{thread_head.memory}}
                <span class="subscript">KB</span>
            </div>
            <div class="label none-transform">
                Used Memory
            </div>
        </div>
        <div class="statistic">
            <div class="value none-transform">
                {{language_name[thread_head.language]}}
                <span class="subscript">&nbsp;</span>
            </div>
            <div class="label none-transform">
                Language
            </div>
        </div>
        <div class="statistic">
            <div :class="'value none-transform '+judge_color[thread_head.result]">
                <i :class="icon_list[thread_head.result]+' icon'"></i>
                {{result_name[thread_head.result]}}
             <span class="subscript">&nbsp;</span>
            </div>
            <div class="label none-transform">
                Result
            </div>
        </div>
    </div>
    </div>
                        <div class="ui styled fluid accordion">
        <div class="title">AC代码<i class="dropdown icon"></i></div>
        <div class="content" v-html="markdownIt.render('```' + language_markdown[thread_head.language] + '\n' +thread_head.code + '\n```')"></div>
    </div>
                 </div>
              </div>
      </div>
    </div>
    <script>
    var source = getParameterByName("from")||"local";
    var id = getParameterByName("id");
    $.get("/api/tutorial/"+source + "/" + id,function(d){
        window.tutorial = new Vue({
            el:".ui.container.padding",
            data:function(){
                return {
                    content:d.data,
                    id:id,
                    source:source,
                    judge_color:d.const_variable.judge_color,
                    language_name:d.const_variable.language_name,
                    icon_list:d.const_variable.icon_list,
                    result_name:d.const_variable.result,
                    owner:d.self,
                    language_markdown:d.const_variable.language_common_name
                }
            },
            mounted:function(){
                var that = this;
                $.get("/api/tutorial/"+source + "/" + id,function(d){
                    that.content = d.data;
                    that.owner = d.self;
                });
                $title = $("title").html();
                $("title").html("Tutorial " + id + " -- " + $title);
                this.$nextTick(function(){
                    $('.ui.accordion')
                    .accordion({
                        'exclusive': false
                    });
                    var copy_content = new Clipboard(".copy.context",{
                        text: function (trigger) {
                            return $(trigger).parent().next().text();
                        }
                        });
                    copy_content.on("success",function(e){
                    $(e.trigger)
                    .popup({
                        title   : 'Finished',
                    content : 'Context is in your clipboard',
                        on      : 'click'
                     })
                     .popup("show");
                    })
                })
            }
        })
    })
        
    </script>
<?php include("template/semantic-ui/bottom.php") ?>
</body>
</html>
