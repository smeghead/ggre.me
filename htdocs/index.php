<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/common.css" type="text/css">
    <title>ggre.me</title>
  </head>
  <body>
<?php
require_once(dirname(__FILE__) . '/../lib/util.php');
use util;
$util = new util\Template();
$util->output_header();
?>
    <div class="content">
      <div class="description">
      このサイトは、小さいツールを集めたサイトです。
      </div>
      <ul>
        <li><a href="/calendar/">祝日カレンダー</a></li>
        <li><a href="/password/">パスワード生成</a></li>
        <li><a href="/barcode/">バーコード生成</a></li>
        <li><a href="/bauth/">BASIC認証のパスワード生成</a></li>
      </ul>
    </div>

<?php
require_once(dirname(__FILE__) . '/../lib/ga.php');
use ga;
$ga = new ga\GoogleAnalyticsTag();
$ga->output();
?>
  </body>
</html>
