<?php
require_once '../vendor/autoload.php';
use GeoIp2\WebService\Client;

// This creates a Client object that can be reused across requests.
// Replace "42" with your user ID and "license_key" with your license
// key.
$client = new Client();

// Replace "city" with the method corresponding to the web service that
// you are using, e.g., "country", "cityIspOrg", "omni".
$record = $client->city('128.101.101.101');

print($record->country->isoCode . "\n"); // 'US'
print($record->country->name . "\n"); // 'United States'
//print($record->country->names['zh-CN'] . "\n"); // '美国'

print($record->mostSpecificSubdivision->name . "\n"); // 'Minnesota'
print($record->mostSpecificSubdivision->isoCode . "\n"); // 'MN'

print($record->city->name . "\n"); // 'Minneapolis'

print($record->postal->code . "\n"); // '55455'

print($record->location->latitude . "\n"); // 44.9733
print($record->location->longitude . "\n"); // -93.2323