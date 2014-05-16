<?php
ini_set('error_reporting', E_ALL);

require_once(dirname(__FILE__).'/Image/Barcode2.php');

function get_product_id() {
  $product_id = '';
  if (is_numeric($_REQUEST['product_id'])) {
    $product_id = $_REQUEST['product_id'];
  }
  return $product_id;
}
function get_company_id() {
  $company_id = '';
  if (is_numeric($_REQUEST['company_id'])) {
    $company_id = $_REQUEST['company_id'];
  }
  return $company_id;
}

function generate_barcode() {
  $png = array(
    0x89, 0x50, 0x4e, 0x47, 0x0d, 0x0a, 0x1a, 0x0a, 0x00, 0x00, 0x00, 0x0d, 0x49, 0x48, 0x44, 0x52,
    0x00, 0x00, 0x00, 0x01, 0x00, 0x00, 0x00, 0x01, 0x01, 0x03, 0x00, 0x00, 0x00, 0x25, 0xdb, 0x56,
    0xca, 0x00, 0x00, 0x00, 0x03, 0x50, 0x4c, 0x54, 0x45, 0x00, 0x00, 0x00, 0xa7, 0x7a, 0x3d, 0xda,
    0x00, 0x00, 0x00, 0x01, 0x74, 0x52, 0x4e, 0x53, 0x00, 0x40, 0xe6, 0xd8, 0x66, 0x00, 0x00, 0x00,
    0x0a, 0x49, 0x44, 0x41, 0x54, 0x08, 0xd7, 0x63, 0x60, 0x00, 0x00, 0x00, 0x02, 0x00, 0x01, 0xe2,
    0x21, 0xbc, 0x33, 0x00, 0x00, 0x00, 0x00, 0x49, 0x45, 0x4e, 0x44, 0xae, 0x42, 0x60, 0x82
  );

  if (!get_product_id() || !get_company_id()) {
    header('Content-Type: image/png');
    foreach($png as $b) {
      print(pack('C', $b));
    }
    exit();
  }
  //nocache_headers();
  $code = new Image_Barcode2();
  $code->draw(get_jancode_from_id(), 'code128', 'png');
  exit();
}

function get_jancode_from_id() {
  $sold_product_id = get_product_id();
  $company_jan_code = get_company_id();
  $num = $company_jan_code * 1000 + $sold_product_id;
  $arr = array_reverse(str_split($num));
  $t = 0;
  for($i=0;$i<count($arr);$i++){
    $t += ( ($i+1) % 2) == 0 ? intval($arr[$i]) : intval($arr[$i])*3;
  }
  $cd = 10 - intval( substr($t,strlen($t)-1,1) );
  if($cd == 10) {
    $cd = 0;
  }
  $result = $num * 10 + $cd;
  return $result;
}

//画像リクエストなら画像を返却する。
if ($_REQUEST['image']) {
  generate_barcode();
}
?>
<?php
require_once(dirname(__FILE__) . '/../../lib/util.php');
use util;
$util = new util\Template();
$util->output_header(array(
  'title' => 'JAN バーコード生成',
  'description' => 'JAN バーコード生成',
  'keywords' => 'JAN バーコード生成,自動生成',
));
?>
    <!-- facebook -->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1&appId=807993512562818";
          fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <div class="content">
      <h1>JAN バーコード生成</h1>
      <div class="social-buttons no-iframe">
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-via="ggre_me" data-lang="ja">ツイート</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        <a href="http://b.hatena.ne.jp/entry/http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" class="hatena-bookmark-button" data-hatena-bookmark-title="<?php echo htmlspecialchars("JAN バーコード生成", ENT_QUOTES); ?>" data-hatena-bookmark-layout="standard-balloon" data-hatena-bookmark-lang="ja" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a><script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
        <div class="fb-like" data-href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
      </div>
      <div class="condition-form">
        <form method="get">
        自社内商品番号<input type="text" name="product_id" value="<?php echo htmlspecialchars(get_product_id(), ENT_QUOTES); ?>">
        会社コード<input type="text" name="company_id" value="<?php echo htmlspecialchars(get_company_id(), ENT_QUOTES); ?>">
        <input type="submit" value="生成">
        </form>
      </div>
      <div class="barcode-block">
        <img src="/barcode/index.php?product_id=<?php echo htmlspecialchars(get_product_id(), ENT_QUOTES); ?>&company_id=<?php echo htmlspecialchars(get_company_id(), ENT_QUOTES); ?>&image=1">
      </div>
    </div>

    <hr/>
    <h2>LINKS</h2>
    <div class="links">
      <ul>
        <li><a href="http://www.dsri.jp/jan/" target="_blank">JANコード登録│ 一般財団法人流通システム開発センター</a></li>
        <li><a href="http://www5d.biglobe.ne.jp/~bar/index.html" target="_blank">バーコードの作成</a></li>
        <li><a href="http://rs-lab.net/jancode/" target="_blank">無料バーコード作成ツール</a></li>
      </ul>
    </div>
<?php $util->output_footer(); ?>
