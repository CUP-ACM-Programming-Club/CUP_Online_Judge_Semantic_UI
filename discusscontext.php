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
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php") ?>
<div class="container">
    <!-- Main component for a primary marketing message or call to action -->
    <div class="ui container padding">
        <h2 class="ui dividing header">Discuss</h2>
        <div class="ui grid">
            <div class="row">
                <div class="thirteen wide column">
                    
                </div>
                <div class="three wide right aligned column">
                    <a href="/discuss_add.php" target="_blank" class="ui labeled icon blue mini button">
                        <i class="write icon"></i>
                        Post
                        </a>
                </div>
            </div>
            <div class="row">
                <div id="word-cloud">
                    
                </div>
            </div>
    </div>
    <table class="ui very basic center aligned table">
        <thead>
            <th width="5%">ID</th>
            <th width="60%">Title</th>
            <th>Author</th>
            <th>Create Time</th>
            <th>Modify Time</th>
        </thead>
        <tbody v-html="table">
            
        </tbody>
    </table>
</div> <!-- /container -->
<script>
    window.discussTable = new Vue({
        el:".ui.container.padding",
        data:function(){
            var query_string = parseQueryString(window.location.hash.substring(1));
            return {
                page:parseInt(query_string.page)||0,
                table_val:[],
                total:0
            }
        },
        created:function(){
            
        },
        mounted:function(){
            var page = this.page * 20;
            var that = this;
            $.get("/api/discuss?page="+page,function(data){
                that.table = data;
            });
        },
        methods:{
            
        },
        computed:{
            table:{
                get:function(){
                    var val = [];
                    var make_tr = function(val){
                        return ["<td>",val,"</td>"].join("");
                    }
                    var make_user = function(user) {
                        return make_tr(["<a href='userinfo.php?user=",user,"' target='_blank'>",user,"</a>"].join(""));
                    }
                    this.table_val.forEach(function(element){
                        var content = [];
                        content.push("<tr>");
                        content.push(make_tr(element.article_id));
                        content.push(make_tr(element.context));
                        content.push(make_user(element.user_id));
                        content.push(make_tr((new Date(element.create_time)).toLocaleString()));
                        content.push(make_tr((new Date(element.edit_time)).toLocaleString()));
                        content.push("</tr>");
                        val.push(content.join(""));
                    })
                    //return val.join("");
                    return "";
                },
                set:function(data){
                    this.total = parseInt(data.total);
                    this.table_val = data.discuss;
                }
            }
        }
    })
</script>

    <?php include("template/$OJ_TEMPLATE/bottom.php") ?>
</body>
</html>
