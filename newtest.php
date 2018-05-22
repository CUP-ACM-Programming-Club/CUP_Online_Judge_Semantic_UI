<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <?php $OJ_TEMPLATE = "semantic-ui"; ?>
    <title><?php echo $OJ_NAME ?></title>
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
                    return val.join("");
                },
                set:function(data){
                    this.total = parseInt(data.total);
                    this.table_val = data.discuss;
                }
            }
        }
    })
</script>
<script>
    const HEIGHT = 300;
    const MAX_SIZE = 40, MIN_SIZE = 20;

    function getTextAttrs(cfg) {
      console.log(cfg.color);
      return Object.assign({}, {
        fillOpacity: cfg.opacity,
        fontSize: cfg.origin._origin.size,
        rotate: cfg.origin._origin.rotate,
        text: cfg.origin._origin.text,
        textAlign: 'center',
        fontFamily: cfg.origin._origin.font,
        fill: cfg.color,
        textBaseline: 'Alphabetic'
      }, cfg.style);
    }

    G2.Shape.registerShape('point', 'cloud', {
      drawShape(cfg, container) {
        const attrs = getTextAttrs(cfg);
        return container.addShape('text', {
          attrs: Object.assign(attrs, {
            x: cfg.x,
            y: cfg.y
          })
        });
      }
    });

    const data = [
      
        {
          tag: "2-sat",
          count: 1
        },
      
        {
          tag: "3d",
          count: 1
        },
      
        {
          tag: "bad dataset",
          count: 4
        },
      
        {
          tag: "bad spj",
          count: 1
        },
      
        {
          tag: "binary search",
          count: 15
        },
      
        {
          tag: "bipartite match",
          count: 3
        },
      
        {
          tag: "brute force",
          count: 22
        },
      
        {
          tag: "combinatorics",
          count: 6
        },
      
        {
          tag: "construction",
          count: 16
        },
      
        {
          tag: "data structure",
          count: 30
        },
      
        {
          tag: "date",
          count: 2
        },
      
        {
          tag: "dfs and similar",
          count: 39
        },
      
        {
          tag: "digit dp",
          count: 1
        },
      
        {
          tag: "divide and conquer",
          count: 7
        },
      
        {
          tag: "DP",
          count: 79
        },
      
        {
          tag: "dsu",
          count: 6
        },
      
        {
          tag: "fft",
          count: 4
        },
      
        {
          tag: "flows",
          count: 12
        },
      
        {
          tag: "games",
          count: 9
        },
      
        {
          tag: "geometry",
          count: 41
        },
      
        {
          tag: "graph",
          count: 22
        },
      
        {
          tag: "greedy",
          count: 29
        },
      
        {
          tag: "implementation",
          count: 28
        },
      
        {
          tag: "interactive",
          count: 2
        },
      
        {
          tag: "IO",
          count: 1
        },
      
        {
          tag: "language exercise",
          count: 56
        },
      
        {
          tag: "math",
          count: 59
        },
      
        {
          tag: "matrix",
          count: 13
        },
      
        {
          tag: "meet in the middle",
          count: 2
        },
      
        {
          tag: "mst",
          count: 6
        },
      
        {
          tag: "number theory",
          count: 11
        },
      
        {
          tag: "parser",
          count: 2
        },
      
        {
          tag: "probabilities",
          count: 7
        },
      
        {
          tag: "random",
          count: 1
        },
      
        {
          tag: "scc",
          count: 1
        },
      
        {
          tag: "shortest paths",
          count: 21
        },
      
        {
          tag: "sortings",
          count: 15
        },
      
        {
          tag: "STL",
          count: 36
        },
      
        {
          tag: "string",
          count: 21
        },
      
        {
          tag: "sweep line",
          count: 3
        },
      
        {
          tag: "trees",
          count: 7
        },
      
        {
          tag: "two pointers",
          count: 3
        }
      
    ];

    const dv = new DataSet.View().source(data);
    const range = dv.range('count');
    const min = range[0];
    const max = range[1];
    dv.transform({
      type: 'tag-cloud',
      fields: ['tag', 'count'],
      font: 'Open Sans',
      forceFit: true,
      size: [$("#word-cloud").width(), HEIGHT],
      padding: 0,
      timeInterval: 5000, // max execute time
      rotate() {
        return 0;
      },
      fontSize(d) {
        return ((d.count - min) / (max - min)) * (MAX_SIZE - MIN_SIZE) + MIN_SIZE;
      }
    });
    const chart = new G2.Chart({
      container: 'word-cloud',
      forceFit: true,
      height: HEIGHT,
      padding: 0
    });
    chart.source(dv);
    chart.legend(false);
    chart.axis(false);
    chart.tooltip({
      showTitle: false
    });
    chart.coord().reflect();
    chart.point()
      .position('x*y')
      .color('tag', ['#21BA45', '#009c95', '#2185D0', '#6435C9', '#e61a8d'])
      .shape('cloud')
      .tooltip(false); // 'tag*count'

    chart.render();
    chart.on('point:click', ev => {
      location.href = "?tag=" + encodeURI(ev.data._origin['text']);
    });

  </script>
    <?php include("template/$OJ_TEMPLATE/bottom.php") ?>
</body>
</html>
