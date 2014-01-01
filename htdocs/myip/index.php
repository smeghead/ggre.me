<?php
ini_set('error_reporting', E_ALL);

?>
<?php
require_once(dirname(__FILE__) . '/../../lib/util.php');
use util;
$util = new util\Template();
$util->output_header(array(
  'title' => '自分のIPアドレス',
  'description' => '自分のアクセス元IPアドレスを表示します。',
  'keywords' => '自分のIPアドレス,自分のIP,ip 調べ方, ipの調べ方,自分のipアドレスの調べ方, 自分のアドレスの調べ方, グローバルアドレス 調べ方, 携帯ipアドレス 調べ方, パソコンのipアドレスの調べ方, パソコン アドレス 調べ方,パソコン ipアドレス 調べ方,remote address',
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
    </div>
<script type="text/javascript">
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
</script>
<?php $util->output_footer(); ?>
