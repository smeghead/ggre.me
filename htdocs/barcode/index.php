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
  //nocache_headers();
  $code = new Image_Barcode2();
  $code->draw(get_jancode_from_id(), 'code128', 'png');
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
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="description" content="バーコード生成">
    <meta name="keywords" content="バーコード生成,自動生成">
    <link rel="stylesheet" href="/password/style.css" type="text/css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <title>バーコード生成</title>
  </head>
  <body>
    <h1>バーコード生成</h1>
    <div class="condition-form">
      <form method="get">
      自社内商品番号<input type="text" name="product_id" value="<?php echo get_product_id(); ?>">
      会社コード<input type="text" name="company_id" value="<?php echo get_company_id(); ?>">
      <input type="submit" value="生成">
      </form>
    </div>
    <div class="barcode-block">
      <img src="/barcode/index.php?product_id=<?php echo get_product_id(); ?>&company_id=<?php echo get_company_id(); ?>&image=1">
    </div>
  </body>
<?php
require_once(dirname(__FILE__) . '/../../lib/ga.php');
use ga;
$ga = new ga\GoogleAnalyticsTag();
$ga->output();
?>
</html>


