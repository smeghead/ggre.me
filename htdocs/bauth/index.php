<?php
ini_set('error_reporting', E_ALL);

?>
<?php
require_once(dirname(__FILE__) . '/../../lib/util.php');
use util;
$util = new util\Template();
$util->output_header(array(
  'title' => 'BASIC認証のパスワード生成',
  'description' => 'BASIC認証のパスワード生成',
  'keywords' => 'BASIC認証のパスワード生成,htpasswd,apache,nginx',
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
        アカウント名<input type="text" name="user_id" value="<?php echo htmlspecialchars($_POST['user_id'], ENT_QUOTES); ?>" maxlength="20">
        パスワード<input type="text" name="passwd" value="<?php echo htmlspecialchars($_POST['passwd'], ENT_QUOTES); ?>" maxlength="20">
        <input type="submit" value="生成">
        </form>
      </div>
<?php if ($_POST['user_id'] && $_POST['passwd']) { ?>
      <div class="result-block">
        <div class="description">
          Basic認証のパスワードファイルに以下の行を追加してください。
        </div>
        <div>
          <span class="result">
<?php
  $user = str_replace('"', '\\"', $_POST['user_id']);
  $passwd = str_replace('"', '\\"', $_POST['passwd']);
  system("htpasswd -nb \"$user\" \"$passwd\"", $ret);
?>
          </span>
        </div>
      </div>
<?php } ?>
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
