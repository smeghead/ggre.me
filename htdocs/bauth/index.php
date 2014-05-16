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
      <h1>BASIC認証のパスワード生成</h1>
      <div class="social-buttons no-iframe">
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-via="ggre_me" data-lang="ja">ツイート</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        <a href="http://b.hatena.ne.jp/entry/http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" class="hatena-bookmark-button" data-hatena-bookmark-title="<?php echo htmlspecialchars("BASIC認証のパスワード生成", ENT_QUOTES); ?>" data-hatena-bookmark-layout="standard-balloon" data-hatena-bookmark-lang="ja" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a><script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
        <div class="fb-like" data-href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
      </div>
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

      <hr/>
      <h2>Basic認証のパスワード生成API</h2>
      <div class="description">
        APIとして利用することができます。
      </div>
      <h3>APIによるBasic認証パスワード取得方法</h3>
      <div class="description">
        JavaScript(jQuery)から利用する場合の例です。
        http://ggre.me/bauth/index.php/generate.json?user_id=john&amp;passwd=xxxxx にアクセスすると、生成したパスワードをjson形式で取得できます。
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
        JavaScript(jQuery)から利用する場合の例です。
        http://ggre.me/bauth/index.php/generate.text?user_id=john&amp;passwd=xxxxx にアクセスすると、生成したパスワードをtext形式で取得できます。
      </div>
      <pre class="prettyprint">
$.get('http://ggre.me/bauth/index.php/generate.text', {user_id: 'john', passwd: 'xxxxx'}, function(data){
  alert(data);
}, 'text');
      </pre>
      <script>
      $(function(){
        $('#btn-text').on('click', function(){
          $.get('http://ggre.me/bauth/index.php/generate.text', {user_id: 'john', passwd: 'xxxxx'}, function(data){
            alert(data);
          }, 'text');
        });
      });
      </script>
      <input type="button" id="btn-text" value="TEXT形式で生成したBasic認証のパスワードを取得する">

      <hr/>
      <h2>LINKS</h2>
      <div class="links">
        <ul>
          <li><a href="http://ja.wikipedia.org/wiki/Basic%E8%AA%8D%E8%A8%BC" target="_blank">Basic認証 - Wikipedia</a></li>
          <li><a href="http://allabout.co.jp/gm/gc/23780/" target="_blank">基本認証でアクセス制限をかける方法 [ホームページ作成] All About</a></li>
          <li><a href="http://orange-factory.com/tool/crypt.cgi" target="_blank">BASIC認証用 パスワード暗号化ツール</a></li>
        </ul>
      </div>
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
