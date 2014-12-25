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
  'title' => '自分のIPアドレス取得 API',
  'description' => '自分のアクセス元IPアドレスを表示します。自分のIPアドレスを取得できるAPIの使い方も記載してます。',
  'keywords' => '自分のIPアドレス,IPアドレス取得API,自分のIP,ip 調べ方, ipの調べ方,自分のipアドレスの調べ方, 自分のアドレスの調べ方, グローバルアドレス 調べ方, 携帯ipアドレス 調べ方, パソコンのipアドレスの調べ方, パソコン アドレス 調べ方,パソコン ipアドレス 調べ方,remote address',
  'css' => '/myip/style.css'
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
      <h1>自分のIPアドレス取得</h1>
      <div class="social-buttons no-iframe">
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-via="ggre_me" data-lang="ja">ツイート</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        <a href="http://b.hatena.ne.jp/entry/http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" class="hatena-bookmark-button" data-hatena-bookmark-title="<?php echo htmlspecialchars("自分のIPアドレス API", ENT_QUOTES); ?>" data-hatena-bookmark-layout="standard-balloon" data-hatena-bookmark-lang="ja" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a><script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
        <div class="fb-like" data-href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
      </div>
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
        JavaScript(jQuery)から利用する場合の例です。
        http://peakytools.info/myip/index.php/myip.json にアクセスすると、json形式でIPアドレスを取得できます。
      </div>
      <pre class="prettyprint">
$.getJSON('http://peakytools.info/myip/index.php/myip.json', function(data){
  alert(JSON.stringify(data));
});
      </pre>
      <script>
      $(function(){
        $('#btn-json').on('click', function(){
          $.getJSON('http://peakytools.info/myip/index.php/myip.json', function(data){
            alert(JSON.stringify(data));
          });
        });
      });
      </script>
      <input type="button" id="btn-json" value="JSON形式で自分のIPアドレスを取得する">

      <div class="description">
        JavaScript(jQuery)から利用する場合の例です。
        http://peakytools.info/myip/index.php/myip.text にアクセスすると、text形式でIPアドレスを取得できます。
      </div>
      <pre class="prettyprint">
$.get('http://peakytools.info/myip/index.php/myip.text', null, function(data){
  alert(data);
}, 'text');
      </pre>
      <script>
      $(function(){
        $('#btn-text').on('click', function(){
          $.get('http://peakytools.info/myip/index.php/myip.text', null, function(data){
            alert(data);
          }, 'text');
        });
      });
      </script>
      <input type="button" id="btn-text" value="TEXT形式で自分のIPアドレスを取得する">

      <h2>LINKS</h2>
      <div class="links">
        <ul>
          <li><a href="http://ja.wikipedia.org/wiki/IP%E3%82%A2%E3%83%89%E3%83%AC%E3%82%B9" target="_blank">IPアドレス - Wikipedia</a></li>
          <li><a href="http://www.ugtop.com/spill.shtml" target="_blank">確認くん(VIA the UGTOP)</a></li>
          <li><a href="http://www.showtem.com/ip-address" target="_blank">自分のIPアドレスの調べ方</a></li>
          <li><a href="http://qiita.com/sharow/items/66d89136180884a2f7b7" target="_blank">Linux - 自分のIPアドレスを知る方法 - Qiita</a></li>
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
