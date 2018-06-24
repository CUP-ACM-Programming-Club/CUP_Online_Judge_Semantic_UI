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
    <script src="/js/dist/g2.min.js"></script>
    <script src="/js/dist/data-set.min.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .full.segment{
            height:100%;
        }
        .black{
            color: black;
        }
    </style>
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php") ?>
    <!-- Main component for a primary marketing message or call to action -->
<div class="ui container padding" v-cloak>
        <h2 class="ui dividing header">Discuss</h2>
        <div class="ui breadcrumb">
        <a class="section" href="/discuss.php">讨论主页</a>
        <i class="right angle icon divider"></i>
        <div class="active section">Discuss ID:{{id}}</div>
        </div>
        <h1>{{thread_head.title}}</h1>
        <div class="ui grid">
                <div class="four wide column">
                <div class="ui link card">
    <div class="image">
      <img v-if="thread_head.avatar === 1" :src="'../avatar/'+thread_head.user_id+'.jpg'">
      <img v-else src="../assets/images/wireframe/white-image.png">
    </div>
    <div class="content">
      <div class="header"><a class="black" :href="'userinfo.php?user='+thread_head.user_id" target="_blank">{{thread_head.nick}}</a></div>
      <div class="meta">
        <a :href="'userinfo.php?user='+thread_head.user_id" target="_blank">{{thread_head.user_id}}</a>
      </div>
      <div class="description">
        {{thread_head.biography}}
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
                 <div class="ui existing full segment" v-html="thread_head.content">
                 </div>
              </div>
      </div>
      <h3 class="ui dividing header">Comments</h3>
      <div class="ui comments">
      <div class="comment" v-for="row in reply">
    <a class="avatar" :href="'userinfo.php?user='+row.user_id">
      <img v-if="row.avatar === 1" :src="'../avatar/'+row.user_id+'.jpg'">
      <img v-else src="../assets/images/wireframe/white-image.png">
    </a>
    <div class="content">
      <a class="author" :href="'userinfo.php?user='+row.user_id">{{row.nick}}</a>
      <div class="metadata">
        <span class="date">5 days ago</span>
      </div>
      <div class="text">
        {{row.content}}
      </div>
      <div class="actions">
        <a class="reply">Reply</a>
      </div>
    </div>
  </div>
  </div>
</div> <!-- /container -->
<script>
    window.discussTable = new Vue({
        el:".ui.container.padding",
        data:function(){
            var query_string = parseQueryString(window.location.hash.substring(1));
            return {
                page:parseInt(query_string.page)||0,
                table_val:{},
                total:0,
                id:getParameterByName("id")||0,
            }
        },
        computed:{
            table:{
                get:function(){
                    return this.table_val;
                },
                set:function(val){
                    this.table_val = val;
                }
            },
            thread_head:function(){
                var context = {
                    title:""
                };
                _.assign(context,this.table_val.discuss_header_content);
                return context;
            },
            reply:function(){
                return this.table_val.discuss;
            }
        },
        created:function(){
            
        },
        mounted:function(){
            var page = this.page * 20;
            var that = this;
            $.get("/api/discuss/"+this.id+"?page="+page,function(data){
                that.table = data;
            });
        },
        methods:{
            
        }
    })
</script>

    <?php include("template/$OJ_TEMPLATE/bottom.php") ?>
</body>
</html>
