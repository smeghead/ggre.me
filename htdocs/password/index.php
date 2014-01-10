<?php
ini_set('error_reporting', E_ALL);

$letters = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890');
function get_length() {
  $length = 8;
  if (is_numeric($_REQUEST['length'])) {
    $length = min(24, max(1, $_REQUEST['length']));
  }
  return $length;
}

function generate_password() {
  global $letters;

  $length = get_length();


  $password = '';
  $last_index = count($letters) - 1;
  for ($i = 0; $i < $length; $i++) {
    $password .= $letters[mt_rand(0, $last_index)];
  }
  return $password;
}

if ($_SERVER['PATH_INFO'] == '/generate.json') {
  $json = array('password' => generate_password());
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');
  echo json_encode($json);
  exit();
} elseif ($_SERVER['PATH_INFO'] == '/generate.text') {
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');
  echo generate_password();
  exit();
}

require_once(dirname(__FILE__) . '/../../lib/util.php');
use util;
$util = new util\Template();
$util->output_header(array(
  'title' => 'パスワード生成 API',
  'description' => 'ランダムな文字列のパスワードを生成します。APIとして利用することもできます。',
  'keywords' => '自動生成,API,パスワード自動生成API, パスワード自動生成, パスワード生成ツール, ランダムパスワード生成, パスワード生成機, パス生成, パスワード作成ツール, ランダムパスワード作成',
  'css' => '/password/style.css'
));
?>
    <div class="content">
      <h1>パスワード生成</h1>
      <div class="condition-form">
        <form method="get">
        パスワード長さ<input type="number" name="length" value="<?php echo get_length(); ?>">
          <input type="submit" value="再生成">
        </form>
      </div>
      <div class="passwords">
  <?php for ($i = 0; $i < 30; $i++) { ?>
    <span class="password"><?php echo generate_password(); ?></span>
  <?php } ?>
      </div>

      <h2>パスワード生成API</h2>
      <div class="description">
        APIとして利用することができます。
      </div>
      <h3>APIによるパスワード取得方法</h3>
      <div class="description">
        JavaScriptから利用する場合の例です。
        http://ggre.me/password/index.php/generate.json にアクセスすると、生成したパスワードをjson形式でを取得できます。
      </div>
      <pre class="prettyprint">
$.getJSON('http://ggre.me/password/index.php/generate.json', function(data){
  alert(JSON.stringify(data));
});
      </pre>
      <script>
      $(function(){
        $('#btn-json').on('click', function(){
          $.getJSON('http://ggre.me/password/index.php/generate.json', function(data){
            alert(JSON.stringify(data));
          });
        });
      });
      </script>
      <input type="button" id="btn-json" value="JSON形式で生成したパスワードを取得する">

      <div class="description">
        JavaScriptから利用する場合の例です。
        http://ggre.me/password/index.php/generate.text にアクセスすると、生成したパスワードをtext形式でを取得できます。
      </div>
      <pre class="prettyprint">
$.get('http://ggre.me/password/index.php/generate.text', function(data){
  alert(JSON.stringify(data));
}, 'text');
      </pre>
      <script>
      $(function(){
        $('#btn-text').on('click', function(){
          $.get('http://ggre.me/password/index.php/generate.text', function(data){
            alert(JSON.stringify(data));
          }, 'text');
        });
      });
      </script>
      <input type="button" id="btn-text" value="TEXT形式で生成したパスワードを取得する">
    </div>
<script type="text/javascript">
$(function(){
  $('span.password').click(function(){
    var span = this;
    if (window.getSelection) {
      var range = document.createRange();
      range.setStart(span.firstChild,0);
      range.setEnd(span.firstChild, span.innerHTML.length);
      var sel = getSelection();
      sel.removeAllRanges();
      sel.addRange(range);
    } else {
      var range = document.selection.createRange();
      range.moveToElementText(span);
      range.collapse();
      range.moveStart("character", 0);
      range.moveEnd("character", span.innerHTML.length);
      range.select();
    }
  });
});
</script>
<?php $util->output_footer(); ?>
