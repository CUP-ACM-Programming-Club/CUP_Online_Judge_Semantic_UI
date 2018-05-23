<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script>
    if (window.navigator.appName.indexOf("Internet") != -1) {
        location.href = "notie.php";
    }

    function checkModule(obj) {
        return typeof module !== "undefined";
    }
</script>

<script src="/template/semantic-ui/js/jquery-3.2.1.min.js"
        onload="if(checkModule())window.$ = window.jQuery = module.exports;"></script>
<script src="/template/semantic-ui/js/semantic.js"></script>
<!--
<script src="/template/semantic-ui/js/webpack/index_bundle.js"></script>
-->
<script src="/template/semantic-ui/js/vue.js"></script>
<script src="/template/semantic-ui/js/base64.js"></script>
<script src="/template/semantic-ui/js/utils.js"></script>
<script src="/js/lodash.min.js"></script>

<script>
var _parameterCache = {};
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    if(_parameterCache[name+url]){
        return _parameterCache[name+url];
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return (_parameterCache[name+url]=decodeURIComponent(results[2].replace(/\+/g, " ")));
}
function parseQueryString(query) {
    var parsed = {};

    query.replace(
        new RegExp('([^?=&]+)(=([^&]*))?', 'g'),
        function ($0, $1, $2, $3) {
            parsed[decodeURIComponent($1)] = decodeURIComponent($3);
        }
    );

    return parsed;
}
   
    if (navigator.userAgent.indexOf("MSIE")!==-1) {
        location.href = "notie.php";
    }
    function binding_method(){
        
            if ($('.ui.accordion').html())
                $('.ui.accordion')
                    .accordion({
                        'exclusive': false
                    })
                ;
            if ($('.ui.menu .ui.dropdown').html())
                $('.ui.menu .ui.dropdown').dropdown({
                    on: 'hover'
                });
            if ($('.ui.search.dropdown').html()) {
                $('.ui.search.dropdown').dropdown({
                    on: 'hover'
                });
            }
            if ($('.ui.dropdown.selection'))
                $('.ui.dropdown.selection').dropdown({
                    on: 'hover'
                });
            // fix menu when passed
            if ($('.fixed.menu').html())
                $('.masthead')
                    .visibility({
                        once: false,
                        onBottomPassed: function () {
                           // $('.following.bar').addClass('light fixed');
                            $('.fixed.menu').transition('fade in');
                            //('.network.menu').addClass('secondary')
                            //$('.network.menu').removeClass('inverted');
                        },
                        onBottomPassedReverse: function () {
                           // $('.following.bar').removeClass('light fixed')
                            $('.fixed.menu').transition('fade out');
                           // $('.network.menu').removeClass('secondary');
                           // $('.network.menu').addClass('inverted');
                        }
                    })
                ;

            // create sidebar and attach to menu open
            if ($('.ui.sidebar.mobile').html())
                $('.ui.sidebar.mobile')
                    .sidebar('attach events', '.toc.item')
                ;
                function lightin(){
                    $('.following.bar').addClass('light fixed');
                            $('.fixed.menu').transition('fade in');
                            //$('.network.menu').addClass('secondary')
                            if(typeof homepage === "boolean" && homepage)
                            $('.network.menu').removeClass('inverted');
                }
                function lightout(){
                    
                            $('.following.bar').removeClass('light fixed')
                            $('.fixed.menu').transition('fade out');
                            //('.network.menu').removeClass('secondary');
                            if(typeof homepage === "boolean" && homepage)
                            $('.network.menu').addClass('inverted');
                }
                $('body')
                    .visibility({
                        once: false,
                        offset : -10,
                        observeChanges : false,
                        continuous : false,
                        refreshOnLoad:true,
                        refreshOnResize:true,
                        onTopPassedReverse: lightout,
                        onTopPassed: lightin
                    })
                ;
                $('.message .close')
        .on('click', function() {
          $(this)
            .closest('.message')
            .transition('fade')
          ;
        })
      ;
        $(".detail").popup({
           exclusive:  true
          , hoverable:  true,
          popup:".flowing.popup.dropdown_menu_group"
                    })
    }
    $(document)
        .ready(function () {binding_method();})
    ;
</script>
