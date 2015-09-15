<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/include/AiroDump.class.php';

// Connect to CouchDB database

$clients = \Doctrine\CouchDB\CouchDBClient::create(array('dbname' => 'clients'));
//$clients->createDatabase($client->getDatabase());
$probes = \Doctrine\CouchDB\CouchDBClient::create(array('dbname' => 'probes'));
//$probes->createDatabase($client->getDatabase());

// Initiate AiroDump log parser

$airodump = new AiroDump();

// Read AiroDump log

$log = file_get_contents($argv[count($argv)-1]);

$log = $airodump->ParseLog($log);

// Populate into database

/*
    [0] => Station MAC
    [1] => First time seen
    [2] => Last time seen
    [3] => Power
    [4] => # packets
    [5] => BSSID
    [6] => Array
	        (
	            [0] => Probed ESSIDs
	        )
 */

foreach($log[1] as $device){
	if(!$clients->findDocument($device[0])){
		@$clients->postDocument(array('_id' => $device[0]));	
	}

	if(count($client[6])){
		
	}
}
