<?php

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

require_once(dirname(__FILE__) . '/../../lib/util.php');
use util;
$util = new util\Template();
$util->output_header(array(
  'title' => 'パスワード生成',
  'description' => 'パスワード生成',
  'keywords' => 'パスワード生成,自動生成',
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
    </div>
<script type="text/javascript">
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
</script>
<?php $util->output_footer(); ?>
