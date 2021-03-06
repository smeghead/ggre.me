<?php
namespace util;

require_once(dirname(__FILE__) . '/ga.php');
use ga;

class Template {
  public function output_header($params) {
    ?>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
<?php if ($params['description']): ?>
    <meta name="description" content="<? echo htmlspecialchars($params['description'], ENT_QUOTES) ?>">
<?php endif ?>
<?php if ($params['keywords']): ?>
    <meta name="keywords" content="<? echo htmlspecialchars($params['keywords'], ENT_QUOTES) ?>">
<?php endif ?>
    <link rel="stylesheet" href="/common.css" type="text/css">
<?php if ($params['css']): ?>
    <link rel="stylesheet" href="<? echo htmlspecialchars($params['css'], ENT_QUOTES) ?>" type="text/css">
<?php endif ?>
    <title><?php echo $params['title'] ?></title>
    <link href="/js/google-code-prettify/prettify.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="/js/google-code-prettify/prettify.js"></script>
  </head>
  <body>
    <header>
      <div class="main-menu">
        <ul>
          <li>
            <a href="/">peakytools.info</a>
          </li>
        </ul>
      </div>
      <p class="clearfix"></p>
    </header>
    <?php
  }

  public function output_footer($params) {
?>

<script>
    $(function(){
      prettyPrint();
    });
</script>

<footer>
  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <!-- peakytools.info -->
  <ins class="adsbygoogle"
   style="display:block"
   data-ad-client="ca-pub-6994803411870749"
   data-ad-slot="4317119598"
   data-ad-format="auto"></ins>
  <script>
  (adsbygoogle = window.adsbygoogle || []).push({});
  </script>
  <hr>
  <div class="copyright">
    &copy; <a href="http://twitter.com/ggre_me" target="_blank">@ggre_me</a>
  </div>
</footer>

<?php
    $ga = new ga\GoogleAnalyticsTag();
    $ga->output();
?>
  </body>
</html>
<?php
  }
}

?>

