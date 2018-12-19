<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>Discuss -- <?php echo $OJ_NAME ?></title>
    <?php include("template/semantic-ui/css.php"); ?>
    <?php include("template/semantic-ui/extra_css.php") ?>
    <?php include("template/semantic-ui/js.php"); ?>
    <?php include("template/semantic-ui/extra_js.php") ?>
    <script src="/js/dist/g2.min.js"></script>
    <script src="/js/dist/data-set.min.js"></script>
    <script src="/js/dayjs.min.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php include("template/$OJ_TEMPLATE/nav.php") ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="ui container padding" style="min-height:400px">
        <h2 class="ui dividing header">Discuss</h2>
        <div class="ui grid">
            <div class="row">
                <div class="thirteen wide column">
                    <div class="ui search">
  <div class="ui icon input">
    <input class="prompt" type="text" placeholder="搜索标题" v-model="search">
    <i class="search icon"></i>
  </div>
  <div class="results"></div>
</div>
                </div>
                <div class="three wide right aligned column">
                    <a href="/newdiscusspost.php" target="_blank" class="ui labeled icon blue mini button">
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
    <table class="ui very basic center aligned table" v-cloak>
        <thead>
            <th width="5%">ID</th>
            <th width="40%">Title</th>
            <th>Author</th>
            <th>Create Time</th>
            <th>Modify Time</th>
            <th>Latest Post</th>
        </thead>
        <tbody>
            <tr v-for="row in table">
                <td>{{row.article_id}}</td>
                <td><a :href="'discusscontext.php?id='+row.article_id" target='_blank'>{{row.title}}</a></td>
                <td><a :href="'userinfo.php?user='+row.user_id" target='_blank'>{{row.user_id}}</a></td>
                <td>{{dayjs(row.create_time).format("YYYY-MM-DD HH:mm:ss")}}</td>
                <td>{{dayjs(row.edit_time).format("YYYY-MM-DD HH:mm:ss")}}</td>
                <td>{{dayjs(row.last_post).format("YYYY-MM-DD HH:mm:ss")}}</td>
            </tr>
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
                total:0,
                search:""
            }
        },
        watch:{
            search:function(newVal,oldVal) {
                var that = this;
                var page = this.page * 20;
                var url = "/api/discuss/";
                if(newVal && newVal.length > 0) {
                    url += "search/" + newVal;
                }
                url += "?page=" + page;
                $.get(url,function(data){
                    if(data.discuss) {
                        that.table = data;
                    }
                    else {
                    that.table = {
                        discuss:data.data
                    }
                    }
                })
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
            $.get("/api/discuss?page="+page,function(data){
                that.table = data;
            });
        },
        methods:{
            
        },
        computed:{
            table:{
                get:function(){
                    return this.table_val;
                },
                set:function(data){
                    this.total = parseInt(data.total);
                    this.table_val = data.discuss;
                }
            }
        }
    })
</script>

    <?php include("template/semantic-ui/bottom.php") ?>
</body>
</html>
