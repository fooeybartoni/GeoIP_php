<?php
require_once '../vendor/autoload.php';
use GeoIp2\Database\Reader;

// This creates the Reader object, which should be reused across
// lookups.
$reader = new Reader("/Users/brucegoldfeder/Sites/GeoIP/GeoLite2-City.mmdb");

// File to read in, this can be refactored to input arg for generalization
$IPfile = "/Users/brucegoldfeder/Sites/GeoIP/hits_http.txt";

getIPfromCSV($reader, $IPfile);

function getIPfromCSV($reader, $IPfile) {
	
	//read in the IP listings
	$file_handle = fopen($IPfile, "rw");

	while (!feof($file_handle) ) {

		$line_of_text = fgetcsv($file_handle, 1024);

		$IPtoGeo = $line_of_text[1];

		$calcIP = printIPGeo($IPtoGeo, $reader);

	}

	fclose($file_handle);
}

// Replace "city" with the appropriate method for your database, e.g.,
// "country".
function printIPGeo($IPtoGeo, $reader) {
	$record = $reader->city($IPtoGeo);

	print('IP to Geo is: ' . $IPtoGeo . "\n");

	print($record->country->isoCode . "\n"); // 'US'
	print($record->country->name . "\n"); // 'United States'


	print($record->mostSpecificSubdivision->name . "\n"); // 'Minnesota'
	print($record->mostSpecificSubdivision->isoCode . "\n"); // 'MN'

	print($record->city->name . "\n"); // 'Minneapolis'

	print($record->postal->code . "\n"); // '55455'

	print($record->location->latitude . "\n"); // 44.9733
	print($record->location->longitude . "\n"); // -93.2323
	print ("<BR>");
}
?>
