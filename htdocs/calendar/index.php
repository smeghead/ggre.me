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
?>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="description" content="祝日カレンダー">
    <meta name="keywords" content="祝日,カレンダー,<?php echo join(',', $keywords); ?>">
    <link rel="stylesheet" href="/common.css" type="text/css">
    <link rel="stylesheet" href="/calendar/style.css" type="text/css">
    <title><?php echo $year; ?>年 祝日カレンダー</title>
  </head>
  <body>
<?php
require_once(dirname(__FILE__) . '/../../lib/util.php');
use util;
$util = new util\Template();
$util->output_header();
?>
    <div class="content">
      <h1><?php echo $year; ?>年 祝日カレンダー</h1>
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
    </div>
  </body>
<?php
require_once(dirname(__FILE__) . '/../../lib/ga.php');
use ga;
$ga = new ga\GoogleAnalyticsTag();
$ga->output();
?>
</html>
