<script src="/js/browser-detect.umd.js"></script>
<script>
var result = browserDetect();
if(result.name === "ie" && result.versionNumber < 10) {
        location.href = "notie.php"
}
//console.log(result);
</script>
<script src="/template/semantic-ui/js/jquery-3.3.1.min.js"></script>
<script src="/semantic-ui/semantic.min.js?ver=1.0.4"></script>
<script src="/js/markdown-it.js?ver=1.0.5"></script>
<!--
<script src="/template/semantic-ui/js/webpack/index_bundle.js"></script>
-->
<script src="/js/vue.min.js?ver=2.5.17"></script>
<script src="/template/semantic-ui/js/base64.js?type=min&ver=1.0"></script>
<script src="/template/semantic-ui/js/utils.js?ver=1.0.4"></script>
<script src="/template/semantic-ui/js/anime.min.js"></script>
<script src="/js/lodash.min.js"></script>
<script>
    $(document).ready(function(){
        $(function() {
                setTimeout(function() {
                $("body").prepend('<a href="#top" id="toTop" style="z-index:999"><i class="arrow alternate huge circle up icon"></i></a>');
            	$("#toTop").scrollToTop();
                }, 50);
            });
    })
</script>