<?php
ini_set('error_reporting', E_ALL);

function generate_password($user, $password) {
  ob_start();
  $user = str_replace('"', '\\"', $user);
  $passwd = str_replace('"', '\\"', $password);
  system("htpasswd -nb \"$user\" \"$passwd\"", $ret);
  $output = ob_get_contents();
  ob_end_clean();
  return trim($output);
}

if ($_SERVER['PATH_INFO'] == '/generate.json') {
  $json = array('password' => generate_password($_REQUEST['user_id'], $_REQUEST['passwd']));
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');
  echo json_encode($json);
  exit();
} elseif ($_SERVER['PATH_INFO'] == '/generate.text') {
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');
  echo generate_password($_REQUEST['user_id'], $_REQUEST['passwd']);
  exit();
}
?>
<?php
require_once(dirname(__FILE__) . '/../../lib/util.php');
use util;
$util = new util\Template();
$util->output_header(array(
  'title' => 'BASIC認証のパスワード生成',
  'description' => 'BASIC認証のパスワードを生成します。APIによるパスワード生成も可能です。',
  'keywords' => 'BASIC認証のパスワード生成,API,htpasswd,apache,nginx',
  'css' => '/bauth/style.css'
));
?>
    <div class="content">
      <h1>BASIC認証のパスワード生成</h1>
      <div class="description">
        BASIC認証のパスワードファイルに記載する情報をブラウザから作成します。
      </div>
      <div class="condition-form">
        <form method="post">
        アカウント名<input type="text" name="user_id" value="<?php echo htmlspecialchars($_REQUEST['user_id'], ENT_QUOTES); ?>" maxlength="20">
        パスワード<input type="text" name="passwd" value="<?php echo htmlspecialchars($_REQUEST['passwd'], ENT_QUOTES); ?>" maxlength="20">
        <input type="submit" value="生成">
        </form>
      </div>
<?php if ($_REQUEST['user_id'] && $_REQUEST['passwd']) { ?>
      <div class="result-block">
        <div class="description">
          Basic認証のパスワードファイルに以下の行を追加してください。
        </div>
        <div>
          <span class="result">
<?php
  echo generate_password($_REQUEST['user_id'], $_REQUEST['passwd']);
?>
          </span>
        </div>
      </div>
<?php } ?>

      <h2>Basic認証のパスワード生成API</h2>
      <div class="description">
        APIとして利用することができます。
      </div>
      <h3>APIによるBasic認証パスワード取得方法</h3>
      <div class="description">
        JavaScriptから利用する場合の例です。
        http://ggre.me/bauth/index.php/generate.json にアクセスすると、生成したパスワードをjson形式でを取得できます。
      </div>
      <pre class="prettyprint">
$.getJSON('http://ggre.me/bauth/index.php/generate.json', {user_id: 'john', passwd: 'xxxxx'}, function(data){
  alert(JSON.stringify(data));
});
      </pre>
      <script>
      $(function(){
        $('#btn-json').on('click', function(){
          $.getJSON('http://ggre.me/bauth/index.php/generate.json', {user_id: 'john', passwd: 'xxxxx'}, function(data){
            alert(JSON.stringify(data));
          });
        });
      });
      </script>
      <input type="button" id="btn-json" value="JSON形式で生成したBasic認証のパスワードを取得する">

      <div class="description">
        JavaScriptから利用する場合の例です。
        http://ggre.me/bauth/index.php/generate.text にアクセスすると、生成したパスワードをtext形式でを取得できます。
      </div>
      <pre class="prettyprint">
$.get('http://ggre.me/bauth/index.php/generate.text', {user_id: 'john', passwd: 'xxxxx'}, function(data){
  alert(JSON.stringify(data));
}, 'text');
      </pre>
      <script>
      $(function(){
        $('#btn-text').on('click', function(){
          $.get('http://ggre.me/bauth/index.php/generate.text', {user_id: 'john', passwd: 'xxxxx'}, function(data){
            alert(JSON.stringify(data));
          }, 'text');
        });
      });
      </script>
      <input type="button" id="btn-text" value="TEXT形式で生成したBasic認証のパスワードを取得する">
    </div>
<script type="text/javascript">
$(function(){
  $('span.result').click(function(){
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
