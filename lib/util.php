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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  </head>
  <body>
    <header>
      <div class="main-menu">
        <ul>
          <li>
            <a href="/">ggre.me</a>
          </li>
        </ul>
      </div>
      <p class="clearfix"></p>
    </header>
    <?php
  }

  public function output_footer($params) {
?>
<hr>

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

