<?php
//logger, make sure the directory or the file pixel.log is writable

//Settings

$separator = '|';

function printLog($str)
{
  file_put_contents( 'pixel-tracking.csv', $str."\n", FILE_APPEND | LOCK_EX );
}
 
//log some info
if(!file_exists('pixel-tracking.csv')) {
printLog('ID'.$separator.'Title'.$separator.'Date'.$separator.'Site'.$separator.'User IP'.$separator.'Country'.$separator.'State'.$separator.'City'.$separator.'User Agent');
}

if (isset($_GET['id'])) {
    $line .= $_GET['id'] . $separator;
}
if (isset($_GET['title'])) {
  $line .= html_entity_decode(urldecode($_GET['title'])) . $separator;
}

$line .= date('Y-m-d H:i:s') . $separator;
$line .= $_SERVER['HTTP_REFERER'] . $separator;
$line .= $_SERVER['REMOTE_ADDR'] . $separator;

require_once("geoip2.phar");
use GeoIp2\Database\Reader;
// City DB
$reader = new Reader('GeoLite2-City/GeoLite2-City.mmdb');
$record = $reader->city($_SERVER['REMOTE_ADDR']);
$line .= $record->country->name . $separator;
$line .= $record->mostSpecificSubdivision->name . $separator;
$line .= $record->city->name . $separator;

$line .= $_SERVER['HTTP_USER_AGENT'];

printLog($line);
 
//output the image
header('Content-Type: image/gif');
 
// This echo is equivalent to read an image, readfile('pixel.gif')
echo "\x47\x49\x46\x38\x37\x61\x1\x0\x1\x0\x80\x0\x0\xfc\x6a\x6c\x0\x0\x0\x2c\x0\x0\x0\x0\x1\x0\x1\x0\x0\x2\x2\x44\x1\x0\x3b";