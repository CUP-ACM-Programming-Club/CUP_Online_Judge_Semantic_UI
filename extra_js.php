<script src="/template/semantic-ui/js/clipboard.min.js"></script>
<script src="/js/mavon-editor.js"></script>
<script src='/MathJax/MathJax.js?config=TeX-AMS-MML_HTMLorMML' ></script>
<script>
     Vue.use(window["mavon-editor"]);
</script>
<script type="text/x-mathjax-config">
  MathJax.Hub.Config({
      showProcessingMessages:false,
      messageStyle:"none",
    tex2jax: {
      inlineMath: [
        ['$', '$'],
        ['\\(', '\\)']
      ],
      displayMath: [ ["$$","$$"] ],
      skipTags: ['script', 'noscript', 'style', 'textarea', 'pre','code','a'],
    },
    TeX: {
      equationNumbers: {
        autoNumber: ["AMS"],
        useLabelIds: true
      }
    },
    "HTML-CSS": {
      linebreaks: {
        automatic: true
      }
    },
    SVG: {
      linebreaks: {
        automatic: true
      }
    }
  });

</script>