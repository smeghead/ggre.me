<?php
ini_set('error_reporting', E_ALL);

$api_url = 'https://www.googleapis.com/urlshortener/v1/url';
$api_key = 'AIzaSyDgZ2Ynx9zb1JlaUybehiBYcmMOZ1dW7hE';

$fonts = array(
  'deco-maru-font-s' => 'まる字',
  'deco-r10-win' => 'JTCウインR10', 
  'deco-edo-win' => 'JTC江戸文字「風雲」', 
  'deco-z10-win' => 'JTCウインZ10', 
  'deco-uta-win' => 'JTC淡斎古印体「歌」',
);

function compress_url($url) {
  global $api_url, $api_key;
  $curl = curl_init("$api_url?key=$api_key");
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, '{"longUrl":"' . $url . '"}');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $res = curl_exec($curl);
  curl_close($curl);

  $json = json_decode($res);
  return $json->id;
}

$memo = array();
function decompress_url($url) {
  global $api_url, $api_key, $memo;
  if (!array_key_exists($url, $memo)) {
    $curl = curl_init("$api_url?key=$api_key&shortUrl=$url");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($curl);
    curl_close($curl);

    $json = json_decode($res);
    $memo[$url] = $json->longUrl;
  }
  return $memo[$url];
}

$pathinfo = $_SERVER['PATH_INFO'];
if ($pathinfo == '/matrix/') {
  $pathinfo = '';
}
if ($pathinfo) {
  //pathinfoがある場合は、短縮URLとして扱う
  $originalUrl = decompress_url('http://goo.gl' . $pathinfo);
  $url = parse_url($originalUrl);
  parse_str($url['query'], $params);
  foreach ($params as $k => $v) {
    //強制的にパラメータを上書きする。
    $_REQUEST[$k] = $v;
  }
  $shortUrl = 'http://ggre.me/matrix' . $pathinfo;
} else {
  if (!isEdit()) {
    $myUrl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];;
    $sUrl = compress_url($myUrl);
    preg_match('/\/([0-9a-zA-Z]+)$/', $sUrl, $matches);
    $redirectUrl = 'http://ggre.me/matrix/' . $matches[1];
    header('Location: ' . $redirectUrl);
    exit();
  }
}



function isEdit() {
  return !isset($_REQUEST['title']);
}

function v($name, $default) {
  $val = $default;
  $ref = $_SERVER["HTTP_REFERER"];
  if (strstr($ref, $_SERVER['HTTP_HOST']) == -1) {
    //他のサイトから来た場合はデフォルト文字列を返却する。
    return $default;
  }

  if (strstr($ref, '?title=') === false) {
    $pathinfo = strrchr($ref, '/');
    $ref = decompress_url('http://goo.gl' . $pathinfo);
  }
  if (strstr($ref, '?title=') !== false) {
    $url = parse_url($ref);
    parse_str($url['query'], $params);
    if (isset($params[$name])) {
      $val = $params[$name];
    }
  }

  if (isset($_REQUEST[$name])) {
     $val = $_REQUEST[$name];
  }
  return $val;
}

$title = v('title', '');
$font = v('font', 'deco-maru-font-s');
$theme = v('theme', '#69c');
$label_x = v('label-x', '裕福');
$label__x = v('label--x', '貧乏');
$label_y = v('label-y', '忙しい');
$label__y = v('label--y', 'ひま');
$culster1 = v('culster1', '和民社員');
$culster2 = v('culster2', '外資系社員');
$culster3 = v('culster3', 'ニート');
$culster4 = v('culster4', '資産家');
?>
<?php
require_once(dirname(__FILE__) . '/../../lib/util.php');
use util;
$util = new util\Template();
$util->output_header(array(
  'title' => (isEdit() ? '' : $title . ' | ') . '四象限マトリクスジェネレーター',
  'description' => '四象限マトリクスを生成します。',
  'keywords' => '四象限,マトリクス',
  'css' => '/matrix/style.css'
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
    <!-- webfont -->
    <script type="text/javascript" src="http://site.decomoji.jp/js/aKv4T.js" charset="utf-8"></script>

    <div class="content">
      <img id="logo" src="matrix.png" alt="logo"/>
<?php
if (isEdit()) {
  $page_title = '四象限マトリクスジェネレーター';
} else {
  $page_title = htmlspecialchars($title . ' | 四象限マトリクスジェネレーター', ENT_QUOTES);
} ?>
      <h1><?php echo htmlspecialchars($page_title, ENT_QUOTES); ?></h1>
<div class="social-buttons no-iframe">
  <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://g<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-via="ggre_me" data-lang="ja">ツイート</a>
  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
  <a href="http://b.hatena.ne.jp/entry/http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" class="hatena-bookmark-button" data-hatena-bookmark-title="<?php echo htmlspecialchars($title, ENT_QUOTES); ?>" data-hatena-bookmark-layout="standard-balloon" data-hatena-bookmark-lang="ja" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a><script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
  <div class="fb-like" data-href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
</div>
<?php if (isEdit()) { ?>
      <div class="description">
        四象限マトリクスを生成します。
      </div>
      <div class="description">
        図の中の変更したい文字をクリックして下さい。
      </div>
<?php } ?>
      <div class="result-block">
        <div class="matrix-block">
          <span class="label label-x" title="<?php echo htmlspecialchars($label_x, ENT_QUOTES); ?>">
            <?php echo htmlspecialchars($label_x, ENT_QUOTES); ?>
          </span>
          <span class="label label--x" title="<?php echo htmlspecialchars($label__x, ENT_QUOTES); ?>">
            <?php echo htmlspecialchars($label__x, ENT_QUOTES); ?>
          </span>
          <span class="label label-y" title="<?php echo htmlspecialchars($label_y, ENT_QUOTES); ?>">
            <?php echo htmlspecialchars($label_y, ENT_QUOTES); ?>
          </span>
          <span class="label label--y" title="<?php echo htmlspecialchars($label__y, ENT_QUOTES); ?>">
            <?php echo htmlspecialchars($label__y, ENT_QUOTES); ?>
          </span>
          <span class="url"><a href="<?php echo $shortUrl; ?>" target="_blank"><?php echo str_replace('http://', '', $shortUrl); ?></a></span>
          <table class="matrix">
            <tr>
              <td>
                <span class="cluster culster1" title="<?php echo htmlspecialchars($culster1, ENT_QUOTES); ?>">
                  <?php echo htmlspecialchars($culster1, ENT_QUOTES); ?>
                </span>
              </td>
              <td>
                <span class="cluster culster2" title="<?php echo htmlspecialchars($culster2, ENT_QUOTES); ?>">
                  <?php echo htmlspecialchars($culster2, ENT_QUOTES); ?>
                </span>
              </td>
            </tr>
            <tr>
              <td>
                <span class="cluster culster3" title="<?php echo htmlspecialchars($culster3, ENT_QUOTES); ?>">
                  <?php echo htmlspecialchars($culster3, ENT_QUOTES); ?>
                </span>
              </td>
              <td>
                <span class="cluster culster4" title="<?php echo htmlspecialchars($culster4, ENT_QUOTES); ?>">
                  <?php echo htmlspecialchars($culster4, ENT_QUOTES); ?>
                </span>
              </td>
            </tr>
          </table>
        </div>
        <div class="nav-actions">
<?php if (isEdit()) { ?>
<form id="matrix-form">
  <div>
    <input id="title" type="text" value="<?php echo htmlspecialchars($title, ENT_QUOTES); ?>" placeholder="グラフのタイトルを入力してください。" />
  </div>
  <div class="font-block">
    <div>
      <input id="font-normal" type="radio" name="font" value="" <?php if ($font == '') { echo 'checked="checked"'; } ?> /><label for="font-normal" class="">フォントを選択する</label>
    </div>
<?php foreach ($fonts as $key => $name) { ?>
    <div>
      <input id="font-<?php echo $key; ?>" type="radio" name="font" value="<?php echo $key; ?>" <?php if ($font == $key) { echo 'checked="checked"'; } ?> /><label for="font-<?php echo $key; ?>" class="<?php echo $key; ?>" title="<?php echo $name; ?>">フォントを選択する</label>
    </div>
<?php } ?>
  </div>
  <div class="color-block">
    <input id="theme-blue" type="radio" name="theme" value="#69c" <?php if ($theme == '#69c') { echo 'checked="checked"'; } ?> /><label for="theme-blue" class=""><span class="color-area" style="background-color: #69c;">&nbsp;</a></label>
    <input id="theme-red" type="radio" name="theme" value="#ff7f7f" <?php if ($theme == '#ff7f7f') { echo 'checked="checked"'; } ?> /><label for="theme-red" class=""><span class="color-area" style="background-color: #ff7f7f;">&nbsp;</a></label>
    <input id="theme-black" type="radio" name="theme" value="#999" <?php if ($theme == '#999') { echo 'checked="checked"'; } ?> /><label for="theme-black" class=""><span class="color-area" style="background-color: #999;">&nbsp;</a></label>
    <input id="theme-green" type="radio" name="theme" value="#CCFF99" <?php if ($theme == '#CCFF99') { echo 'checked="checked"'; } ?> /><label for="theme-green" class=""><span class="color-area" style="background-color: #CCFF99;">&nbsp;</a></label>
    <input id="theme-pink" type="radio" name="theme" value="#FF99CC" <?php if ($theme == '#FF99CC') { echo 'checked="checked"'; } ?> /><label for="theme-pink" class=""><span class="color-area" style="background-color: #FF99CC;">&nbsp;</a></label>
    <input id="theme-yellow" type="radio" name="theme" value="#ffff93" <?php if ($theme == '#ffff93') { echo 'checked="checked"'; } ?> /><label for="theme-yellow" class=""><span class="color-area" style="background-color: #ffff93;">&nbsp;</a></label>
  </div>
  <div>
    <input type="submit" value="四象限マトリクスを生成する" />
  </div>
</form>
<?php } else { ?>
          <a class="nav-back" href="/matrix">四象限マトリクを編集する</a>
<?php } ?>
        </div>
      </div>

<?php if (isEdit()) { ?>
  <script type="text/javascript">
  $(function(){
    $('div.matrix-block .label,table.matrix .cluster').on('click', function(){
      var label = $(this);
      var text = prompt('入力してください', $.trim(label.text()));
      if (text !== null) {
        label.text(text);
        ajustmentLabelWidth();
      }
    }).css('cursor', 'pointer');
    $('#title').keypress(function(){
      var title = $('#title');
      title.css('background-color', 'white');
    });

    $('#matrix-form').on('submit', function(ev){
      ev.preventDefault();
      var title = $('#title');
      if (title.val().length == 0) {
        title.css('background-color', '#FFCCCC');
        alert('グラフのタイトルを入力してください。');
        return;
      }

      location.href = '/matrix?' +
        'title=' + encodeURIComponent(title.val()) +
        '&label-x=' + encodeURIComponent($.trim($('.label-x').text())) +
        '&label--x=' + encodeURIComponent($.trim($('.label--x').text())) +
        '&label-y=' + encodeURIComponent($.trim($('.label-y').text())) +
        '&label--y=' + encodeURIComponent($.trim($('.label--y').text())) +
        '&culster1=' + encodeURIComponent($.trim($('.culster1').text())) +
        '&culster2=' + encodeURIComponent($.trim($('.culster2').text())) +
        '&culster3=' + encodeURIComponent($.trim($('.culster3').text())) +
        '&culster4=' + encodeURIComponent($.trim($('.culster4').text())) +
        '&font=' + encodeURIComponent($('input[name=font]:checked').val()) +
        '&theme=' + encodeURIComponent($('input[name=theme]:checked').val());
    });
  });
  </script>
  <h2 class="">サンプル</h2>
  <div class="samples">
    <iframe src="http://t.co/ZxtpMy6wm8" style="width: 360px; height: 280px; border:none;"></iframe>
    <iframe src="http://t.co/tLefvRiemR" style="width: 360px; height: 280px; border:none;"></iframe>
    <iframe src="http://t.co/w0iAyeor44" style="width: 360px; height: 280px; border:none;"></iframe>
  </div>
<?php } else { ?>
  <div class="no-iframe">
    <h3 id="sample">ブログパーツ</h3>
    作成した四象限マトリクスのグラフを、ブログなどに貼り付けることができます。
    以下のタグをブログに貼り付けて下さい。
    <pre class="prettyprint">
      &lt;iframe src="<?php echo $shortUrl; ?>" style="width: 360px; height: 280px; border:none;"&gt;&lt;/iframe&gt;
    </pre>

    <hr/>
  </div>
  <script type="text/javascript">
  $(function(){
    //iframe中に表示されている場合は、nav-actionを表示しない。
    if (window != parent) {
      $('.nav-actions').hide();
      $('header, footer').hide();
      $('.no-iframe').hide();
      $('h1').css('font-size', '15pt');
    }
  });
  </script>
<?php } ?>
  <h2 class="no-iframe">LINKS</h2>
  <div class="links no-iframe">
    <ul>
      <li><a href="http://www.higuchi.com/item/672" target="_blank">コンサル風４象限マトリクスチャートにはだまされないぞ [困ったプレゼン] - higuchi.com blog</a></li>
      <li><a href="https://www.google.co.jp/search?num=100&espv=210&es_sm=93&q=%E5%9B%9B%E8%B1%A1%E9%99%90%E3%83%9E%E3%83%88%E3%83%AA%E3%82%AF%E3%82%B9&oq=%E5%9B%9B%E8%B1%A1%E9%99%90%E3%83%9E%E3%83%88%E3%83%AA%E3%82%AF%E3%82%B9&gs_l=serp.3..0.1909.1909.0.2027.1.1.0.0.0.0.96.96.1.1.0....0...1c.1.37.serp..0.1.96.3rKts0rqnoo" target="_blank">四象限マトリクス - Google 検索</a></li>
      <li><a href="http://sustoco.concentinc.jp/shikumi/matrix/" target="_blank">「普通」「曖昧」を表すマトリクス - 勝手に伝わるしくみ | サストコ</a></li>
      <li><a href="http://allabout.co.jp/gm/gc/376046/2/" target="_blank">2/3 代表的な情報整理「マトリクス」 [ロジカルシンキング] All About</a></li>
      <li><a href="http://kotobank.jp/word/%E8%B1%A1%E9%99%90" target="_blank">象限 とは - コトバンク</a></li>
    </ul>
  </div>
  <script type="text/javascript">
  $(function(){
    setupLabel();
    setupFont('<?php echo $font; ?>');
    $('input[name=font]').change(function(){
      console.log('font changed.');
      setupFont();
    });
    setupTheme('<?php echo $theme; ?>');
    $('input[name=theme]').change(function(){
      console.log('theme changed.');
      setupTheme();
    });
  });
  function setupLabel() {
    var parentWidth = $('.matrix-block').get(0).offsetWidth;
    $('.matrix-block .label-y, .matrix-block .label--y').each(function(){
      var w = this.offsetWidth;
      var newWidth = ((parentWidth - w) / 2);
      $(this).css('left', newWidth + 'px');
    });
    var parentHeight = $('.matrix-block').get(0).offsetHeight;
    $('.matrix-block .label-x, .matrix-block .label--x').each(function(){
      var h = this.offsetHeight;
      var newHeight = ((parentHeight - h) / 2);
      $(this).css('top', newHeight + 'px');
    });
  }
  function setupFont(f) {
    var font = f;
    if (!font) {
      font = $('input[name=font]:checked').val();
    }
//console.log($('input[name=font]:checked'));
//    $('input[name=font]').next().css('background-color', '#fff');
//    $('input[name=font]:checked').next().css('background-color', '#ddd');
    $('.matrix-block')
<?php foreach ($fonts as $key => $name) { ?>
      .removeClass('<?php echo $key; ?>')
<?php } ?>
      .css('font-family', "'Lucida Grande', Meiryo, sans-serif")
      .addClass(font);
    DecoMoji.load()
  }
  function setupTheme(t) {
    var theme = t;
    if (!theme) {
      theme = $('input[name=theme]:checked').val();
    }
    var background = 'linear-gradient(top center, #fff 0%, ' + theme + ' 100%)';
    if(jQuery.browser.mozilla){
      background = '-moz-linear-gradient(top center, #fff 0%, ' + theme + ' 100%)';
    } else if(jQuery.browser.webkit){
      background = '-webkit-gradient(linear, center top, center bottom, from(#fff), to(' + theme + '))';
    }
    $('table.matrix').css({'background': background, 'background-color': theme});
  }
  </script>
<?php $util->output_footer(); ?>
