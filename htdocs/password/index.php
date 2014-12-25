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
      <h1>パスワード生成</h1>
      <div class="social-buttons no-iframe">
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-via="ggre_me" data-lang="ja">ツイート</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        <a href="http://b.hatena.ne.jp/entry/http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" class="hatena-bookmark-button" data-hatena-bookmark-title="<?php echo htmlspecialchars("パスワード生成 API", ENT_QUOTES); ?>" data-hatena-bookmark-layout="standard-balloon" data-hatena-bookmark-lang="ja" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a><script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
        <div class="fb-like" data-href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
      </div>
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

      <hr/>
      <h2>パスワード生成API</h2>
      <div class="description">
        APIとして利用することができます。
      </div>
      <h3>APIによるパスワード取得方法</h3>
      <div class="description">
        JavaScript(jQuery)から利用する場合の例です。
        http://peakytools.info/password/index.php/generate.json にアクセスすると、生成したパスワードをjson形式で取得できます。
      </div>
      <pre class="prettyprint">
$.getJSON('http://peakytools.info/password/index.php/generate.json', function(data){
  alert(JSON.stringify(data));
});
      </pre>
      <script>
      $(function(){
        $('#btn-json').on('click', function(){
          $.getJSON('http://peakytools.info/password/index.php/generate.json', function(data){
            alert(JSON.stringify(data));
          });
        });
      });
      </script>
      <input type="button" id="btn-json" value="JSON形式で生成したパスワードを取得する">

      <div class="description">
        JavaScript(jQuery)から利用する場合の例です。
        http://peakytools.info/password/index.php/generate.text にアクセスすると、生成したパスワードをtext形式で取得できます。
      </div>
      <pre class="prettyprint">
$.get('http://peakytools.info/password/index.php/generate.text', function(data){
  alert(data);
}, 'text');
      </pre>
      <script>
      $(function(){
        $('#btn-text').on('click', function(){
          $.get('http://peakytools.info/password/index.php/generate.text', function(data){
            alert(data);
          }, 'text');
        });
      });
      </script>
      <input type="button" id="btn-text" value="TEXT形式で生成したパスワードを取得する">

      <h2>LINKS</h2>
      <div class="links">
        <ul>
          <li><a href="http://ja.wikipedia.org/wiki/%E3%83%91%E3%82%B9%E3%83%AF%E3%83%BC%E3%83%89" target="_blank">パスワード - Wikipedia</a></li>
          <li><a href="http://www.graviness.com/temp/pw_creator/" target="_blank">パスワード自動生成 (Automated Password Generator)</a></li>
          <li><a href="http://www.atmarkit.co.jp/flinux/rensai/linuxtips/889mkpasswd2.html" target="_blank">任意の文字数でパスワードをランダム生成するには － ＠IT</a></li>
          <li><a href="http://www.microsoft.com/ja-jp/security/online-privacy/passwords-create.aspx" target="_blank">パスワードの変更 | 安全性の高いパスワードの作成 | Microsoft セキュリティ</a></li>
        </ul>
      </div>
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
