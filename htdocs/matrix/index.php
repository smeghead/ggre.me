<?php
ini_set('error_reporting', E_ALL);

function v($name, $default) {
  if (isset($_REQUEST[$name])) {
    return $_REQUEST[$name];
  }
  return $default;
}
$title = v('title', '');
$label_x = v('label_x', '儲かる');
$label__x = v('label__x', '儲からない');
$label_y = v('label_y', '忙しい');
$label__y = v('label__y', 'ひま');
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
  'title' => '4象限マトリクス API',
  'description' => '4象限マトリクスのグラフを生成します。',
  'keywords' => '4象限,マトリクス,グラフ',
  'css' => '/matrix/style.css'
));
?>
    <div class="content">
<?php if (empty($title)) { ?>
      <h1>4象限マトリクスジェネレーター</h1>
      <div class="description">
        4象限マトリクスのグラフを生成します。
      </div>
      <div class="description">
        グラフの中の変更したい文字をクリックして下さい。
      </div>
<?php } else { ?>
      <h1><?php echo htmlspecialchars($title, ENT_QUOTES); ?></h1>
<?php } ?>
      <div class="result-block">
        <div class="matrix-block">
          <div class="label label-x">
            <?php echo htmlspecialchars($label_x, ENT_QUOTES); ?>
          </div>
          <div class="label label--x">
            <?php echo htmlspecialchars($label__x, ENT_QUOTES); ?>
          </div>
          <div class="label label-y">
            <?php echo htmlspecialchars($label_y, ENT_QUOTES); ?>
          </div>
          <div class="label label--y">
            <?php echo htmlspecialchars($label__y, ENT_QUOTES); ?>
          </div>
          <div class="url"><a href=""></a></div>
          <table class="matrix">
            <tr>
              <td>
                <span class="cluster culster1">
                  <?php echo htmlspecialchars($culster1, ENT_QUOTES); ?>
                </span>
              </td>
              <td>
                <span class="cluster culster2">
                  <?php echo htmlspecialchars($culster2, ENT_QUOTES); ?>
                </span>
              </td>
            </tr>
            <tr>
              <td>
                <span class="cluster culster3">
                  <?php echo htmlspecialchars($culster3, ENT_QUOTES); ?>
                </span>
              </td>
              <td>
                <span class="cluster culster4">
                  <?php echo htmlspecialchars($culster4, ENT_QUOTES); ?>
                </span>
              </td>
            </tr>
          </table>
        </div>
        <div class="nav-actions">
<?php if (empty($title)) { ?>
          <input id="title" type="text" value="" placeholder="グラフのタイトルを入力してください。" />
          <input id="create-url" type="button" value="URLを生成する" />
<?php } else { ?>
          <a class="nav-back" href="/matrix">4象限マトリクスジェネレーターに戻る</a>
<?php } ?>
        </div>
      </div>

<?php if (empty($title)) { ?>
  <script type="text/javascript">
  $(function(){
    $('div.matrix-block .label,table.matrix .cluster').on('click', function(){
      var label = $(this);
      var text = prompt('入力してください', $.trim(label.text()));
      if (text !== null) {
        label.text(text);
      }
    }).css('cursor', 'pointer');
    $('#title').keypress(function(){
      console.log('press');
      var title = $('#title');
      title.css('background-color', 'white');
    });

    $('#create-url').on('click', function(){
      var title = $('#title');
      if (title.val().length == 0) {
        title.css('background-color', '#FFCCCC');
        alert('グラフのタイトルを入力してください。');
        return;
      }

      location.href = '/matrix?' +
        'title=' + title.val() +
        '&label-x=' + $.trim($('.label-x').text()) +
        '&label--x=' + $.trim($('.label--x').text()) +
        '&label-y=' + $.trim($('.label-y').text()) +
        '&label--y=' + $.trim($('.label--y').text()) +
        '&culster1=' + $.trim($('.culster1').text()) +
        '&culster2=' + $.trim($('.culster2').text()) +
        '&culster3=' + $.trim($('.culster3').text()) +
        '&culster4=' + $.trim($('.culster4').text());
    });
  });
  </script>
<?php } else { ?>
  <script type="text/javascript">
  $(function(){
  });

  function load(){
    gapi.client.setApiKey('AIzaSyBy2BDR9xFMg98Eb1vAaWQpUcEswkOlNSQ');
    gapi.client.load('urlshortener', 'v1',　function(){
      gapi.client.urlshortener.url.insert({"resource": {"longUrl": location.href}}).execute(function(resp){
        $('.matrix-block .url a').attr('href', resp.id).text(resp.id);
      });
    });
  }

  </script>
  <script src="https://apis.google.com/js/client.js?onload=load"></script>
<?php } ?>
<?php $util->output_footer(); ?>
