<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/include/AiroDump.class.php';

// Connect to CouchDB database

$client = \Doctrine\CouchDB\CouchDBClient::create(array('dbname' => 'test'));
// $client->createDatabase($client->getDatabase());

// Initiate AiroDump log parser

$airodump = new AiroDump();

// Read AiroDump log

$log = file_get_contents($argv[count($argv)-1]);

$log = $airodump->ParseLog($log);

// Populate into database

foreach($log[1] as $device){
	$query = $client->createViewQuery('client', 'by_mac');
	$query->setKey($device[0]);
	//$query->setIncludeDocs(true);
	$result = $query->execute();

	if(count($result) == 0){
		$client->postDocument(array('mac' => $device[0]));	
	}
}

