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
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
    <div class="ui container padding" v-cloak>
        <h2 class="ui dividing header">
            Solution
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
                 <div class="ui existing full segment">
                     <a v-if="thread_head.user_id + '' ===  owner" class="ui blue right ribbon label" :href="'tutorialedit.php?tutorial_id='+thread_head.tutorial_id+'&from='+thread_head.source.toLowerCase()+'&id='+thread_head.problem_id">Edit</a>
                     <div v-html="thread_head.content"></div>
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
                    owner:d.self
                }
            },
            mounted:function(){
                
            }
        })
    })
        
    </script>
<?php include("template/$OJ_TEMPLATE/bottom.php") ?>
</body>
</html>
