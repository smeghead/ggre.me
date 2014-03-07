<?php
$api_url = 'https://www.googleapis.com/urlshortener/v1/url';
$api_key = 'AIzaSyDgZ2Ynx9zb1JlaUybehiBYcmMOZ1dW7hE';

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
function decompress_url($url) {
  global $api_url, $api_key;
  $curl = curl_init("$api_url?key=$api_key&shortUrl=$url");
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $res = curl_exec($curl);
  curl_close($curl);

  $json = json_decode($res);
  return $json->longUrl;
}
