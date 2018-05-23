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
<?php include("template/$OJ_TEMPLATE/nav.php"); ?>
<div class="container">

    <!-- Main component for a primary marketing message or call to action -->
    <div class="ui text container">
        <br>
        <h2>Problem A:</h2>
	输出Hello kitty (没有感叹号)
<div class="ui section divider"></div>
<h2>Problem B:</h2>
输出1 0即可，易知任意整数模1均为0。或者统计模2为1的多还是为0的多，等等其他方法都可以。
<div class="ui section divider"></div>
<h2>Problem C:</h2>
	由于数据范围不大，暴力搜索即可完成。
<div class="ui section divider"></div>
<h2>Problem D:</h2>
	数据范围放大至暴力搜索无法完成的程度，观察到数列中任何一个数的大小始终不超过$100000$，可以开一个大小为$100000$的数组保存每个数的下标，再根据$Query$查询的数直接输出即可。
	时间复杂度:$O(1)$
<div class="ui section divider"></div>
<h2>Problem E:</h2>
	基础逆波兰表达式。<br>使用栈对普通中序表达式进行转化，成为逆波兰表达式（表达式树）后计算结果。注意的是数据范围超出int
	时间复杂度:$O(n)$
	<div class="ui section divider"></div>
<h2>Problem F:</h2>
	本题实际的公式即为解$a^n$。实际上直接计算算法复杂度会超出时限，因此使用快速幂。
	时间复杂度$O(logn)$
	<div class="ui section divider"></div>
<h2>Problem G:</h2>
	单身数本质是素数。按照题意首先需要筛选出$10000000$以内的素数。因此需要高效的素数筛法。本题埃拉托斯特尼筛法和欧拉筛法的复杂度均在题目要求范围内。对于区间位置定位，考虑到左右区间间隔可能出现最坏情况，因此应该使用二分查找。在本题中右区间仅作为数据合法的约束，不参与实际算法设计。
	<br>
时间复杂度$O(n+logn+logn)=O(n)$(欧拉筛法)
<br>
$O(nlogn+logn+logn)=O(nlogn)$(埃拉托斯特尼筛法)
<div class="ui section divider"></div>
<h2>Problem H:</h2>
	2017年ACM/ICPC乌鲁木齐区域赛网络赛题改编。<br>用KMP对初始模式串进行匹配，对于每个$Change$，需要使用树状数组对区间值进行维护，维护过程使用KMP对$Change$所影响的区间位置$n$ $[n-10,n+10]$进行修改
时间复杂度$O(|S|+nlogn)$
<div class="ui section divider"></div>
<h2>Problem I:</h2>
	斐波那契数列的变体。<br>从斐波那契数列推导过程可知，对于二阶斐波那契数列，有$F(n)=F(n-1)+F(n-2),F(0)=1,F(1)=1$,
该公式可推导矩阵
 <img src="/image/mat1.jpg" />
最后可得到
 <img src="/image/mat2.jpg" />
然后使用矩阵快速幂即可。对于N阶斐波那契数列同理。
时间复杂度$O(k^3logn)$
<div class="ui section divider"></div>
<h2>Problem J:</h2>
	搜索模板题。<br>从出发点进行广度优先搜索(BFS)，注意边界的处理(中国象棋棋盘大小)
时间复杂度$O(|V|+|E|)$
<div class="ui section divider"></div>
<h2>Problem K:</h2>
由于保证a数列的和等于b数列的和，所以易知不管怎么排列，最终能取得的最大值一定是所有堆都取。尺取法，将数列$a_{i}$，$b_{i}$复制一遍粘到原序列后面，用一个长度为n的“尺”求最大收入下的最小移动距离。尺取法参见百度。
时间复杂度:$O(n)$
<div class="ui section divider"></div>
<h2>Problem L:</h2>
	前缀和模板题。<br>由于麦田小麦不需要改变，因此考虑使用一个二维数组$SUM$维护区间和，其中$SUM[i][j]$代表从$(1,1)$到$(I,j)$的区间小麦的和。
时间复杂度$O(m*n)$
<div class="ui section divider"></div>
<h2>Problem M:</h2>
按照题目要求即可，十分简单的打印题。
<div class="ui section divider"></div>
<h2>Problem N:</h2>
	本次选拔赛难度最高的题目。<br>题目要求计算$A*B$的结果，但是$A$和$B$的长度最大可达$60600$（$long$ $long$的长度仅在$18$左右），因此不能够用普通方法直接计算。即便是$Java$和$Python$也无法在时限范围内使用自带的高精度类完成计算(普通高精度乘法算法复杂度$O(n^2)$)。
	标准解法使用以下两种方式:
1.	FFT(快速傅里叶变换)
2.	NTT(快速数论变换)
算法复杂度$O(nlogn)$
<h2></h2>
    </div> <!-- /container -->
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<?php include("template/$OJ_TEMPLATE/bottom.php"); ?>
</body>
</html>
