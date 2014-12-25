<?php
ini_set('error_reporting', E_ALL);
?>
<?php
require_once(dirname(__FILE__) . '/../../lib/util.php');
use util;
$util = new util\Template();
$util->output_header(array(
  'title' => 'キーコード確認サービス',
  'description' => 'ブラウザでキーコードを確認できます。',
  'keywords' => 'キーコード 調査,ブラウザ',
  'css' => '/keycode/style.css'
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
      <h1>キーコード確認</h1>
      <div class="social-buttons no-iframe">
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-via="ggre_me" data-lang="ja">ツイート</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        <a href="http://b.hatena.ne.jp/entry/http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" class="hatena-bookmark-button" data-hatena-bookmark-title="<?php echo htmlspecialchars("自分のIPアドレス API", ENT_QUOTES); ?>" data-hatena-bookmark-layout="standard-balloon" data-hatena-bookmark-lang="ja" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a><script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
        <div class="fb-like" data-href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
      </div>
      <div class="description">
        キーコードを調べたいキーを押してください。
      </div>
      <div class="result-block">
        <div>
          <span class="result">

          </span>
        </div>
      </div>

      <hr/>

      <h2>LINKS</h2>
      <div class="links">
        <ul>
<!--
          <li><a href="http://ja.wikipedia.org/wiki/IP%E3%82%A2%E3%83%89%E3%83%AC%E3%82%B9" target="_blank">IPアドレス - Wikipedia</a></li>
-->
        </ul>
      </div>
    </div>
<script type="text/javascript">
$(function(){
  var key_code = function(e){
    if(document.all)
      return  e.keyCode;
    else if(document.getElementById)
      return (e.keyCode)? e.keyCode: e.charCode;
    else if(document.layers)
      return  e.which;
  }
  $(window).on('keydown', function(ev){
    $('<div>key down char: ' + ev.ckeyCode: ' + key_code(ev) + '</div>')
      .appendTo('.result-block .result');
  });
  $(window).on('keypress', function(ev){
    $('<div>key press keyCode: ' + key_code(ev) + '</div>')
      .appendTo('.result-block .result');
  });
  $(window).on('keyup', function(ev){
    $('<div>key up keyCode: ' + key_code(ev) + '</div>')
      .appendTo('.result-block .result');
  });
});
</script>
<?php $util->output_footer(); ?>
