<?php
require_once '../vendor/autoload.php';
require 'csv.php';
use GeoIp2\Database\Reader;

// Initiating function
$IP_Geo_Array = createIPGeoArray();

//print_r($IP_Geo_Array);

$Geo_IP_Headers = array(
			'IP' => ' ',
			'Country_Code' => ' ',
			'Country_Name' => ' ',
			'State_Code' => ' ',
			'State_Name' => ' ',
			'City_Name' => ' ',
			'Postal_Code' => ' ',
			'Lat' => ' ',
			'Lon' => ' '
		);

writeIP_Geo_Csv($Geo_IP_Headers, $IP_Geo_Array, 'testCSV1.csv');

function createIPGeoArray(){

	// This creates the Reader object, which should be reused across
	// lookups.
	$reader = new Reader("/Users/brucegoldfeder/Sites/GeoIP/GeoLite2-City.mmdb");

	// File to read in, this can be refactored to input arg for generalization
	$IPfile = "/Users/brucegoldfeder/Sites/GeoIP/hits_http.txt";

	// loops through the ip to geo function to create an ID defined list of IP Geo Data
	// Creates an array of arrays with the ID of the counter and an IP-Geo data set

	$cnt = 1;

	$InnerArray = array();

	$IP_Geo_Assoc = array();

	$IParray = getIPfromCSV($IPfile);

	foreach ( $IParray as $ip_inner ) {
		
		$IP_Geo_Assoc[$cnt] = getIPGeo($ip_inner, $reader);

		$cnt++;
	}

	return $IP_Geo_Assoc;

}

function writeIP_Geo_Csv($Geo_IP_Headers, $IP_Geo_Assoc, $outFilename) {

	//CSV::export($IP_Geo_Assoc, $IP_Geo_Assoc, $outFilename);
	//$root = $_SERVER["DOCUMENT_ROOT"] ;
    $fileName = "/Users/brucegoldfeder/Sites/maxmind/file.csv";

    print('file name is: ' . $fileName);


	$fh = fopen($fileName, 'w');

	// write out the headers
	$all_keys = array_keys($Geo_IP_Headers);
	//fputcsv($fh, $all_keys);

	print('<BR> number of lines output: ' . count($IP_Geo_Assoc));

	$test = array_keys($IP_Geo_Assoc[1]);

	//print($test[0]);

	fputcsv($fh, $test);

	foreach ($IP_Geo_Assoc as $inner) {

		//foreach ($inner as $data)
		
		fputcsv($fh, $inner);

	}

	fclose($fh);

}

function getIPfromCSV($IPfile) {

	//read in the IP listings
	$file_handle = fopen($IPfile, "rw");

	$result = array();

	while (!feof($file_handle) ) {

		$line_of_text = fgetcsv($file_handle, 1024);

		$result[] = $line_of_text[1];

	}

	fclose($file_handle);

	return $result;
}

function getIPGeo($IPtoGeo, $reader) {

	$record = $reader->city($IPtoGeo);

	$Geo_IPlist = array(
			'IP' => $IPtoGeo,
			'Country_Code' => $record->country->isoCode,
			'Country_Name' => $record->country->name,
			'State_Code' => $record->mostSpecificSubdivision->isoCode,
			'State_Name' => $record->mostSpecificSubdivision->name,
			'City_Name' => $record->city->name,
			'Postal_Code' => $record->postal->code,
			'Lat' => $record->location->latitude,
			'Lon' => $record->location->longitude
		);

	return $Geo_IPlist;	

}

// Replace "city" with the appropriate method for your database, e.g.,
// "country".
function printIPGeo($IPtoGeo, $reader) {
	$record = $reader->city($IPtoGeo);

	

	print('IP to Geo is: ' . $IPtoGeo . ", ");

	print($record->country->isoCode . ", "); // 'US'
	print($record->country->name . ", "); // 'United States'

	print($record->mostSpecificSubdivision->isoCode . ", "); // 'MN'
	print($record->mostSpecificSubdivision->name . ", "); // 'Minnesota'

	print($record->city->name . ", "); // 'Minneapolis'

	print($record->postal->code . ", "); // '55455'

	print($record->location->latitude . ", "); // 44.9733
	print($record->location->longitude . ", "); // -93.2323
	print ("<BR>");
}

?>
