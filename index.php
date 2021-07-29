<?php

include 'Tracker/Settings.php';
include 'Tracker/DbConnector.php';
include 'Tracker/VisitHelper.php';
include 'geoip2.phar';

use Tracker\VisitHelper;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\GeoIp2Exception;

$user_id = $_GET['id'];
$title = html_entity_decode(urldecode($_GET['title']));
$visit_date = date('Y-m-d H:i:s'); //DateTime::createFromFormat('U', time())->format('Y-m-d H:i:s');
$site = $_SERVER['HTTP_HOST'];
$user_ip = '142.250.71.78'; //TODO Test Only $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

try {
    $geoIPReader = new Reader('GeoLite2-City/GeoLite2-City.mmdb');
    $record = $geoIPReader->city($user_ip);
    $country = $record->country->name;
    $state = $record->mostSpecificSubdivision->name;
    $city = $record->city->name;
} catch (GeoIp2Exception $exception) {
    //GeoIP couldn't find a match, Consider logging the exception message
    die;
}

VisitHelper::addVisit(
    $user_id,
    $title,
    $visit_date,
    $site,
    $user_ip,
    $user_agent,
    $country,
    $state,
    $city
);

$randomHash = md5(rand());
header('Content-Type: image/gif');
header('Content-Disposition: inline; filename="pixel-'. $randomHash .'.gif"');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

echo "\x47\x49\x46\x38\x37\x61\x1\x0\x1\x0\x80\x0\x0\xfc\x6a\x6c\x0\x0\x0\x2c\x0\x0\x0\x0\x1\x0\x1\x0\x0\x2\x2\x44\x1\x0\x3b";
