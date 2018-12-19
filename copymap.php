<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $OJ_NAME?></title>
    <?php include("template/semantic-ui/css.php");?>
    <?php include("template/semantic-ui/js.php");?>
<script src="/js/d3.v5.min.js" charset="utf-8"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css" media="screen">
    .editor { 
        height:300px;
        width:inherit;
    }
</style>
</head>

<body>
<?php include("template/semantic-ui/nav.php");?>
<div>
    <svg width="1400" height="600" style="overflow:inherit"></svg>
</div>

<script>
    var maps = window.maps = {};
    var hashtable = window.hashtable = {};
    var div = document.createElement("div");
    function parseName(name) {
        div.innerHTML = name;
        return div.innerText;
    }
    function addEdge(from,to) {
        if(!maps[from]) {
            maps[from] = [to];
        }
        else {
            maps[from].push(to);
        }
    }
    let hashFlag = 0;
    function getHash(name) {
        if(hashtable[name] === undefined) {
            return hashtable[name] = hashFlag++;
        }
        else {
            return hashtable[name];
        }
    }
    let nodes = [];
    let edges = [];
    let a = new Set();
    $.get("/api/status/sim/?cid=" + getParameterByName("cid"),function(data){
        window.dataset = data.data; 
        for(let i of dataset) {
            i.snick = parseName(i.snick);
            i.nick = parseName(i.nick);
            var ta = getHash(i.snick),tb = getHash(i.nick);
            var tmp;
            tmp = {
                source:ta,
                target:tb,
                relation:"",
                value:1
            };
            if(!maps[ta]) {
                maps[ta] = {};
                maps[ta][tb] = tmp;
                edges.push(tmp);
            }
            else if(!maps[ta][tb]) {
                maps[ta][tb] = tmp;
                edges.push(tmp);
            }
            else {
                maps[ta][tb].value += tmp.value;
            }

            if(!a.has(ta)) {
                console.log(ta);
                a.add(ta);
                nodes.push({name:i.snick});
            }
            if(!a.has(tb)) {
                console.log(tb);
                a.add(tb);
                nodes.push({name:i.nick});
            }
            let mx = Math.max(ta,tb);
            //addEdge(i.s_id,i.sim_s_id);
            //addEdge(i.sim_s_id,i.s_id);
        }
        for(let i of edges) {
            i.value = 2.5 / i.value;
        }
        nodes.sort(function(a,b){
            return getHash(a.name) - getHash(b.name);
        });
        var marge = {top:60,bottom:60,left:60,right:60};
        try {
    	var svg = d3.select("svg")
    	var width = svg.attr("width")
    	var height = 400
    	var defs = svg.append("defs");
 
  var zoom = d3.zoom()
    .scaleExtent([1, 40])
    .translateExtent([
      [-0, -0],
      [width + 0, height + 0]
    ])
    .on("zoom", zoomed);
 
  svg.call(zoom);
 
  function zoomed() {
      if(d3.event.sourceEvent.type === "wheel") {
        d3.event.transform.x = 0;
        d3.event.transform.y = 0;
      }
    svg.attr("transform", d3.event.transform);
  }
 
  function resetted() {
    svg.transition()
      .duration(750)
      .call(zoom.transform, d3.zoomIdentity);
  }
var arrowMarker = defs.append("marker")
						.attr("id","arrow")
						.attr("markerUnits","strokeWidth")
					    .attr("markerWidth","12")
                        .attr("markerHeight","12")
                        .attr("viewBox","0 0 12 12") 
                        .attr("refX","13")
                        .attr("refY","6")
                        .attr("orient","auto");
                        var arrow_path = "M2,2 L10,6 L2,10 L6,6 L2,2";

                        arrowMarker.append("path")
			.attr("d",arrow_path)
			.attr("fill","#000");

    	var g = svg.append("g")
    		.attr("transform","translate("+marge.top+","+marge.left+")");
    		var colorScale = d3.scaleOrdinal()
    		.domain(d3.range(nodes.length))
    		.range(d3.schemeCategory10);
        }
        catch(e) {
            console.log("error");
        }
    	//新建一个力导向图
    	var forceX = d3.forceX(width / 2).strength(0.015)
        var forceY = d3.forceY(height / 2).strength(0.015)
    	var forceSimulation = d3.forceSimulation()
    	    .force('x', forceX)
            .force('y',  forceY)
    		.force("link",d3.forceLink())
    		.force("charge",d3.forceManyBody())
    		.force("center",d3.forceCenter());
    	//初始化力导向图，也就是传入数据
    	//生成节点数据
    	forceSimulation.nodes(nodes)
    		.on("tick",ticked);//这个函数很重要，后面给出具体实现和说明
    	//生成边数据
    	forceSimulation.force("link")
    		.links(edges)
    		.distance(function(d){//每一边的长度
    			return d.value*100;
    		})    	
    	//设置图形的中心位置	
    	forceSimulation.force("center")
    		.x(width/2)
    		.y(height/2);
    	//在浏览器的控制台输出
    	
    	//有了节点和边的数据后，我们开始绘制
    	//绘制边
    	var links = g.append("g")
    		.selectAll("line")
    		.data(edges)
    		.enter()
    		.append("line")
    		.attr("stroke",function(d,i){
    			return colorScale(i);
    		})
    		.attr("stroke-width",1).attr("marker-end","url(#arrow)");
    	var linksText = g.append("g")
    		.selectAll("text")
    		.data(edges)
    		.enter()
    		.append("text")
    		.text(function(d){
    			return d.relation;
    		})
    	
    	//绘制节点
    	//老规矩，先为节点和节点上的文字分组
    	var gs = g.selectAll(".circleText")
    		.data(nodes)
    		.enter()
    		.append("g")
    		.attr("transform",function(d,i){
    			var cirX = d.x;
    			var cirY = d.y;
    			return "translate("+cirX+","+cirY+")";
    		})
    		.call(d3.drag()
    			.on("start",started)
    			.on("drag",dragged)
    			.on("end",ended)
    		);
    		
    	//绘制节点
    	gs.append("circle")
    		.attr("r",3)
    		.attr("fill",function(d,i){
    			return colorScale(i);
    		})
    	//文字
    	gs.append("text")
    		.attr("x",-5)
    		.attr("y",-10)
    		.attr("dy",5)
    		.text(function(d){
    			return d.name;
    		})
    	
    	function ticked(){
    		links
    			.attr("x1",function(d){return d.source.x;})
    			.attr("y1",function(d){return d.source.y;})
    			.attr("x2",function(d){return d.target.x;})
    			.attr("y2",function(d){return d.target.y;});
    			
    		linksText
    			.attr("x",function(d){
    			return (d.source.x+d.target.x)/2;
    		})
    		.attr("y",function(d){
    			return (d.source.y+d.target.y)/2;
    		});
    			
    		gs
    			.attr("transform",function(d) { return "translate(" + d.x + "," + d.y + ")"; });
    	}
    	function started(d){
    		if(!d3.event.active){
    			forceSimulation.alphaTarget(0.8).restart();
    		}
    		d.fx = d.x;
    		d.fy = d.y;
    	}
    	function dragged(d){
    		d.fx = d3.event.x;
    		d.fy = d3.event.y;
    	}
    	function ended(d){
    		if(!d3.event.active){
    			forceSimulation.alphaTarget(0);
    		}
    		d.fx = null;
    		d.fy = null;
    	}
    });
    
</script>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
</body>
</html>
