<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script>
    if (window.navigator.appName.indexOf("Internet") != -1) {
        location.href = "notie.php";
    }
</script>

<script src="/template/semantic-ui/js/jquery-3.2.1.min.js"></script>
<script src="/template/semantic-ui/js/semantic.js"></script>
<!--
<script src="/template/semantic-ui/js/webpack/index_bundle.js"></script>
-->
<script src="/template/semantic-ui/js/vue.js"></script>
<script src="/template/semantic-ui/js/base64.js"></script>
<script src="/template/semantic-ui/js/utils.js"></script>
<script src="/template/semantic-ui/js/anime.min.js"></script>
<script src="/js/lodash.min.js"></script>

<script>
    var _parameterCache = {};

    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        if (_parameterCache[name + url]) {
            return _parameterCache[name + url];
        }
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return (_parameterCache[name + url] = decodeURIComponent(results[2].replace(/\+/g, " ")));
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

    if (navigator.userAgent.indexOf("MSIE") !== -1) {
        location.href = "notie.php";
    }

    function binding_method() {

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

        function lightin() {
            $('.following.bar').addClass('light fixed');
            $('.fixed.menu').transition('fade in');
            //$('.network.menu').addClass('secondary')
            if (typeof homepage === "boolean" && homepage)
                $('.network.menu').removeClass('inverted');
        }

        function lightout() {

            $('.following.bar').removeClass('light fixed')
            $('.fixed.menu').transition('fade out');
            //('.network.menu').removeClass('secondary');
            if (typeof homepage === "boolean" && homepage)
                $('.network.menu').addClass('inverted');
        }

        $('body')
            .visibility({
                once: false,
                offset: -10,
                observeChanges: false,
                continuous: false,
                refreshOnLoad: true,
                refreshOnResize: true,
                onTopPassedReverse: lightout,
                onTopPassed: lightin
            })
        ;
        $('.message .close')
            .off("click")
            .on('click', function () {
                $(this)
                    .closest('.message')
                    .transition('fade')
                ;
            })
        ;
        $(".detail").popup({
            exclusive: true
            , hoverable: true,
            popup: ".flowing.popup.dropdown_menu_group"
        })
    }

    $(document)
        .ready(function () {
            binding_method();
            /*(function addFirework() {
                var canvasEl = document.querySelector('.fireworks');
                var ctx = canvasEl.getContext('2d');
                var numberOfParticules = 30;
                var pointerX = 0;
                var pointerY = 0;
                var tap = ('ontouchstart' in window || navigator.msMaxTouchPoints) ? 'touchstart' : 'mousedown';
                var colors = ['#FF1461', '#18FF92', '#5A87FF', '#FBF38C'];

                function setCanvasSize() {
                    canvasEl.width = window.innerWidth * 2;
                    canvasEl.height = window.innerHeight * 2;
                    canvasEl.style.width = window.innerWidth + 'px';
                    canvasEl.style.height = window.innerHeight + 'px';
                    canvasEl.style.marginTop = window.scrollY + 'px';
                    canvasEl.getContext('2d').scale(2, 2);
                }

                function updateCoords(e) {
                    pointerX = e.clientX || e.touches[0].clientX;
                    pointerY = e.clientY || e.touches[0].clientY;
                }

                function setParticuleDirection(p) {
                    var angle = anime.random(0, 360) * Math.PI / 180;
                    var value = anime.random(50, 180);
                    var radius = [-1, 1][anime.random(0, 1)] * value;
                    return {
                        x: p.x + radius * Math.cos(angle),
                        y: p.y + radius * Math.sin(angle)
                    }
                }

                function createParticule(x, y) {
                    var p = {};
                    p.x = x;
                    p.y = y;
                    p.color = colors[anime.random(0, colors.length - 1)];
                    p.radius = anime.random(16, 32);
                    p.endPos = setParticuleDirection(p);
                    p.draw = function () {
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.radius, 0, 2 * Math.PI, true);
                        ctx.fillStyle = p.color;
                        ctx.fill();
                    }
                    return p;
                }

                function createCircle(x, y) {
                    var p = {};
                    p.x = x;
                    p.y = y;
                    p.color = '#FFF';
                    p.radius = 0.1;
                    p.alpha = .5;
                    p.lineWidth = 6;
                    p.draw = function () {
                        ctx.globalAlpha = p.alpha;
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.radius, 0, 2 * Math.PI, true);
                        ctx.lineWidth = p.lineWidth;
                        ctx.strokeStyle = p.color;
                        ctx.stroke();
                        ctx.globalAlpha = 1;
                    }
                    return p;
                }

                function renderParticule(anim) {
                    for (var i = 0; i < anim.animatables.length; i++) {
                        anim.animatables[i].target.draw();
                    }
                }

                function animateParticules(x, y) {
                    var circle = createCircle(x, y);
                    var particules = [];
                    for (var i = 0; i < numberOfParticules; i++) {
                        particules.push(createParticule(x, y));
                    }
                    anime.timeline().add({
                        targets: particules,
                        x: function (p) {
                            return p.endPos.x;
                        },
                        y: function (p) {
                            return p.endPos.y;
                        },
                        radius: 0.1,
                        duration: anime.random(1200, 1800),
                        easing: 'easeOutExpo',
                        update: renderParticule
                    })
                        .add({
                            targets: circle,
                            radius: anime.random(80, 160),
                            lineWidth: 0,
                            alpha: {
                                value: 0,
                                easing: 'linear',
                                duration: anime.random(600, 800),
                            },
                            duration: anime.random(1200, 1800),
                            easing: 'easeOutExpo',
                            update: renderParticule,
                            offset: 0
                        });
                }

                var render = anime({
                    duration: Infinity,
                    update: function () {
                        ctx.clearRect(0, 0, canvasEl.width, canvasEl.height);
                    }
                });

                document.addEventListener(tap, function (e) {
                    render.play();
                    updateCoords(e);
                    animateParticules(pointerX, pointerY);
                }, false);

                var centerX = window.innerWidth / 2;
                var centerY = window.innerHeight / 2;
                setCanvasSize();
                $(document).on("resize", setCanvasSize).on("scroll", setCanvasSize);
            });*/
        });

</script>
