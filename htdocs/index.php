<?php
ini_set('error_reporting', E_ALL);
require_once(dirname(__FILE__) . '/../lib/util.php');
use util;
$util = new util\Template();
$util->output_header(array(
  'title' => 'ggre.me',
  'description' => 'このサイトは、小さいツールやAPIを集めたサイトです。',
  'keywords' => 'カレンダー,パスワード,バーコード,BASIC認証'
));
?>
    <div class="content">
      <div class="description">
        このサイトは、小さいツールやAPIを集めたサイトです。
      </div>
      <ul class="pages">
        <li><a href="/matrix/">四象限マトリクスジェネレーター</a></li>
        <li><a href="/calendar/">祝日カレンダー</a></li>
        <li><a href="/password/">パスワード生成(APIあり)</a></li>
        <li><a href="/barcode/">バーコード生成</a></li>
        <li><a href="/bauth/">BASIC認証のパスワード生成(APIあり)</a></li>
        <li><a href="/myip/">自分のIPアドレス(APIあり)</a></li>
      </ul>

      <div class="description">

      </div>
    </div>


<?php $util->output_footer(); ?>
