<html>
  <head>
    <meta charset="UTF-8">
    <title>ググレカス</title>
  </head>
  <body>
    <h1>ググレカス</h1>
    <ul>
      <li><a href="/calendar/">祝日カレンダー</a></li>
      <li><a href="/password/">パスワード生成</a></li>
    </ul>

<?php
require_once(dirname(__FILE__) . '/../lib/ga.php');
use ga;
$ga = new ga\GoogleAnalyticsTag();
$ga->output();
?>
  </body>
</html>
