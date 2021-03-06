<?php

function get_holidays($year) {
  if (!is_numeric($year)) {
    $year = date('Y');
  }
  $cache = dirname(__FILE__) . "/cache/$year";
  if (file_exists($cache)) {
    return (array)json_decode(file_get_contents($cache));
  }

  $holidays_url = sprintf(
    'http://www.google.com/calendar/feeds/%s/public/full-noattendees?start-min=%s&start-max=%s&max-results=%d&alt=json' ,
    'outid3el0qkcrsuf89fltf7a4qbacgt9@import.calendar.google.com' , // 'japanese@holiday.calendar.google.com' ,
    "$year-01-01" ,  // 取得開始日
    "$year-12-31" ,  // 取得終了日
    50              // 最大取得数
  );
  if ( $results = file_get_contents($holidays_url) ) {
    $results = json_decode($results, true);
    $holidays = array();
    foreach ($results['feed']['entry'] as $val ) {
      $date  = $val['gd$when'][0]['startTime'];
      $title = $val['title']['$t'];
      $holidays[$date] = $title;
    }
    ksort($holidays);
    if (file_put_contents($cache, json_encode($holidays)) === false) {
      die('failed to create cache.');
    }
  }
  return $holidays;
}

$year = date('Y');
if ($_SERVER['SCRIPT_NAME'] != $_SERVER['PATH_INFO']) {
  $args = split('/', $_SERVER['PATH_INFO']);
  if (count($args) > 1) {
    if (is_numeric($args[1])) {
      $year = $args[1];
    }
  }
}

function output_month($year, $month) {
  $holidays = get_holidays($year);

  $current = new DateTime(sprintf('%04d-%02d-01', $year, $month));
  $last_day = new DateTime(sprintf('%04d-%02d-01', $current->format('Y'), $current->format('m')));
  $last_day->modify('+1 month -1 day');
?>
  <div class="month-block">
    <h2><?php echo $month . '月'; ?></h2>

    <table class="calendar">
      <tr>
        <th>日</th>
        <th>月</th>
        <th>火</th>
        <th>水</th>
        <th>木</th>
        <th>金</th>
        <th>土</th>
      </tr>
<?php
  for ($i = 0; $i < $current->format('w'); $i++) {
    printf('<td></td>');
  }
  while ($current <= $last_day) {
    $weekday = $current->format('w');
    if ($weekday == 0) {
      print('<tr>');
    }
    $class = '';
    if (in_array($weekday, array('0', '6'))) {
      $class = 'holiday';
      if ($weekday == '6') {
        $class .= ' saturday';
      }
    }
    $additional = '';
    $description = '';
    if (in_array($current->format('Y-m-d'), array_keys($holidays))) {
      $class .= ' holiday';
      $descriptions = split(' / ', $holidays[$current->format('Y-m-d')]);
      $description = $descriptions[0];
      $description = str_replace('"', '', $description);
      $additional = sprintf('title="%s"', $description);
    }
    if ($description) {
      $day = sprintf(
        '<a href="http://ja.wikipedia.org/wiki/%s" target="_blank">%d</a>',
        $description,
        $current->format('d'));
    } else {
      $day = sprintf('%d', $current->format('d'));
    }
    printf('<td class="%s" %s>%s</td>' . "\n", $class, $additional, $day);
    if ($current->format('w') == 6) {
      print('</tr>' . "\n");
    }

    $current = $current->add(new DateInterval('P1D'));
  }
  if ($current->format('w') != 0) {
    for ($i = $current->format('w'); $i <= 6; $i++) {
      printf('<td></td>');
    }
  }
?>
    </table>
  </div>
<?php
}

function output_months($year) {
  for ($i = 1; $i <= 12; $i++) {
    output_month($year, $i);
  }
}

$keywords = array();
for ($y = date('Y') - 2; $y < date('Y') + 3; $y++) {
  $keywords[] = $y;
}

require_once(dirname(__FILE__) . '/../../lib/util.php');
use util;
$util = new util\Template();
$util->output_header(array(
  'title' => $year . '年 祝日カレンダー',
  'description' => '祝日カレンダー',
  'keywords' => '祝日,カレンダー,' . join(',', $keywords),
  'css' => '/calendar/style.css'
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
      <h1><?php echo $year; ?>年 祝日カレンダー</h1>
      <div class="social-buttons no-iframe">
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-via="ggre_me" data-lang="ja">ツイート</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        <a href="http://b.hatena.ne.jp/entry/http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" class="hatena-bookmark-button" data-hatena-bookmark-title="<?php echo htmlspecialchars("$year年 祝日カレンダー", ENT_QUOTES); ?>" data-hatena-bookmark-layout="standard-balloon" data-hatena-bookmark-lang="ja" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a><script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
        <div class="fb-like" data-href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
      </div>
      <div class="menu">
        <ul>
  <?php for ($y = date('Y') - 10; $y < date('Y') + 6; $y++) { ?>
  <?php 
  $attr = '';
  if ($y == date('Y')) {
    $attr = 'class="current"';
  }
  if ($y == $year) {
    $attr = 'class="target"';
  }
  ?>
    <li <?php echo $attr; ?>>
      <a href="/calendar/index.php/<?php echo $y; ?>"><?php echo $y; ?>年</a>
    </li>
  <?php } ?>
        </ul>
      </div>
      <div class="year-block">
        <?php output_months($year); ?>
      </div>

      <hr/>
      <h2>LINKS</h2>
      <div class="links">
        <ul>
          <li><a href="http://ja.wikipedia.org/wiki/%E3%82%AB%E3%83%AC%E3%83%B3%E3%83%80%E3%83%BC" target="_blank">カレンダー - Wikipedia</a></li>
          <li><a href="http://www.benri.com/calendar/" target="_blank">「便利コム」カレンダー(祝日・六曜付き)</a></li>
          <li><a href="http://s-proj.com/utils/holiday.html" target="_blank">国民の祝日チェック 祝日判定web API</a></li>
        </ul>
      </div>
    </div>
    <p class="clearfix"></p>
<?php $util->output_footer(); ?>
