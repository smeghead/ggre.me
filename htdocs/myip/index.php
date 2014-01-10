<?php
ini_set('error_reporting', E_ALL);
if ($_SERVER['PATH_INFO'] == '/myip.json') {
  $json = array('ip_address' => $_SERVER['REMOTE_ADDR']);
  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');
  echo json_encode($json);
  exit();
} elseif ($_SERVER['PATH_INFO'] == '/myip.text') {
  header('Content-Type: text/plain');
  header('Access-Control-Allow-Origin: *');
  echo $_SERVER['REMOTE_ADDR'];
  exit();
}
?>
<?php
require_once(dirname(__FILE__) . '/../../lib/util.php');
use util;
$util = new util\Template();
$util->output_header(array(
  'title' => '自分のIPアドレス API',
  'description' => '自分のアクセス元IPアドレスを表示します。自分のIPアドレスを取得できるAPIの使い方も記載してます。',
  'keywords' => '自分のIPアドレス,IPアドレス取得API,自分のIP,ip 調べ方, ipの調べ方,自分のipアドレスの調べ方, 自分のアドレスの調べ方, グローバルアドレス 調べ方, 携帯ipアドレス 調べ方, パソコンのipアドレスの調べ方, パソコン アドレス 調べ方,パソコン ipアドレス 調べ方,remote address',
  'css' => '/myip/style.css'
));
?>
    <div class="content">
      <h1>自分のIPアドレス</h1>
      <div class="description">
自分のアクセス元IPアドレスを表示します。
(環境変数のREMOTE_ADDRの値)
      </div>
      <div class="result-block">
        <div>
          <span class="result">
<?php echo htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES); ?>
          </span>
        </div>
      </div>

      <hr/>
      <h2>自分のIPアドレス取得API</h2>
      <div class="description">
        APIとして利用することができます。
      </div>
      <h3>自分のIPアドレスを取得するAPI</h3>
      <div class="description">
        JavaScriptから利用する場合の例です。
        http://ggre.me/myip/index.php/myip.json にアクセスすると、json形式でIPアドレスを取得できます。
      </div>
      <pre class="prettyprint">
$.getJSON('http://ggre.me/myip/index.php/myip.json', function(data){
  alert(JSON.stringify(data));
});
      </pre>
      <script>
      $(function(){
        $('#btn-json').on('click', function(){
          $.getJSON('http://ggre.me/myip/index.php/myip.json', function(data){
            alert(JSON.stringify(data));
          });
        });
      });
      </script>
      <input type="button" id="btn-json" value="JSON形式で自分のIPアドレスを取得する">

      <div class="description">
        JavaScriptから利用する場合の例です。
        http://ggre.me/myip/index.php/myip.text にアクセスすると、text形式でIPアドレスを取得できます。
      </div>
      <pre class="prettyprint">
$.get('http://ggre.me/myip/index.php/myip.text', null, function(data){
  alert(data);
}, 'text');
      </pre>
      <script>
      $(function(){
        $('#btn-text').on('click', function(){
          $.get('http://ggre.me/myip/index.php/myip.text', null, function(data){
            alert(data);
          }, 'text');
        });
      });
      </script>
      <input type="button" id="btn-text" value="TEXT形式で自分のIPアドレスを取得する">

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
