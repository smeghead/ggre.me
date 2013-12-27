<?php
ini_set('error_reporting', E_ALL);
require_once(dirname(__FILE__) . '/../lib/util.php');
use util;
$util = new util\Template();
$util->output_header(array(
  'title' => 'ggre.me',
  'description' => 'このサイトは、小さいツールを集めたサイトです。',
  'keywords' => 'カレンダー,パスワード,バーコード,BASIC認証'
));
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

      <div class="description">
        このサイトで公開されているツールの利用は、無保証です。
      </div>
    </div>


<?php
$util->output_footer();
?>
