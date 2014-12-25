<?php
ini_set('error_reporting', E_ALL);
$pathinfo = $_SERVER['PATH_INFO'];
$pathinfo = substr($pathinfo, 1);;

// create table messages(id integer not null primary key, message text not null, lang text);
// insert into messages(message, lang) values ('There is no error, the file uploaded with success', 'php');
// insert into messages(message, lang) values ('The uploaded file exceeds the upload_max_filesize directive in php.ini', 'php');
// insert into messages(message, lang) values ('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'php');
// insert into messages(message, lang) values ('The uploaded file was only partially uploaded', 'php');
// insert into messages(message, lang) values ('No file was uploaded', 'php');
// insert into messages(message, lang) values ('Missing a temporary folder', 'php');
/*


*/
try {
    if (!($db = new PDO("sqlite:db/messages.db"))) {
        die("DB Connection Failed.");
    }
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = intval($pathinfo);
    $sql = 'select * from messages where id = ?;';
    $stmt = $db->prepare($sql);
    
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
 
    $stmt->execute();
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $record = $result[0];


    $sql = 'select * from messages order by random() limit 10;';
    $stmt = $db->prepare($sql);
    
    $stmt->execute();
    
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo $e->getMessage();
    $err = $db->errorInfo();
    die ($err[2]);
}

?>
<?php
require_once(dirname(__FILE__) . '/../../lib/util.php');
use util;
$util = new util\Template();
$util->output_header(array(
  'title' => $record['message'] . ' | エラーメッセージ',
  'description' => $record['message'] . 'エラーメッセージの調査',
  'keywords' => 'error message, エラーメッセージ, ' . $record['lang'],
  'css' => '/errormessages/style.css'
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
      <h1>エラーメッセージ</h1>
      <div class="social-buttons no-iframe">
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-via="ggre_me" data-lang="ja">ツイート</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        <a href="http://b.hatena.ne.jp/entry/http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" class="hatena-bookmark-button" data-hatena-bookmark-title="<?php echo htmlspecialchars("自分のIPアドレス API", ENT_QUOTES); ?>" data-hatena-bookmark-layout="standard-balloon" data-hatena-bookmark-lang="ja" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a><script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
        <div class="fb-like" data-href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
      </div>
      <div class="description">
      </div>
      <div class="result-block">
        <div>
          <span class="result">
            <?php echo htmlspecialchars($record['message'], ENT_QUOTES); ?>
          </span>
        </div>
      </div>

      <h2>他のエラーメッセージ</h2>
      <div class="messages">
        <ul>
<?php foreach ($messages as $m) { ?>
          <li><a href="/errormessages/index.php/<?php echo $m['id'] ?>" title="<?php echo htmlspecialchars($m['message'], ENT_QUOTES) ?>"><?php echo htmlspecialchars(mb_strimwidth($m['message'], 0, 30, '...', 'UTF-8'), ENT_QUOTES) ?></a></li>
<?php } ?>
        </ul>
      </div>

<!--
      <h2>LINKS</h2>
      <div class="links">
        <ul>
          <li><a href="http://ja.wikipedia.org/wiki/IP%E3%82%A2%E3%83%89%E3%83%AC%E3%82%B9" target="_blank">IPアドレス - Wikipedia</a></li>
          <li><a href="http://www.ugtop.com/spill.shtml" target="_blank">確認くん(VIA the UGTOP)</a></li>
          <li><a href="http://www.showtem.com/ip-address" target="_blank">自分のIPアドレスの調べ方</a></li>
          <li><a href="http://qiita.com/sharow/items/66d89136180884a2f7b7" target="_blank">Linux - 自分のIPアドレスを知る方法 - Qiita</a></li>
        </ul>
      </div>
-->
    </div>
<?php $util->output_footer(); ?>
