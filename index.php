<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/include/AiroDump.class.php';

// Connect to CouchDB database

$clients = \Doctrine\CouchDB\CouchDBClient::create(array('dbname' => 'clients'));
//$clients->createDatabase($clients->getDatabase());
$probes = \Doctrine\CouchDB\CouchDBClient::create(array('dbname' => 'probes'));
//$probes->createDatabase($probes->getDatabase());
$aps = \Doctrine\CouchDB\CouchDBClient::create(array('dbname' => 'aps'));
#$aps->createDatabase($aps->getDatabase());

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
	if($clients->findDocument($device[0])->status == "404"){
		@$clients->postDocument(array('_id' => $device[0]));	
	}

	if(count($device[6]) > 0){
		for($i = 0; $i < count($device[6]); $i++){
			if($probes->findDocument(md5($device[6][$i].$device[0]))->status == "404"){
				$probes->postDocument(array('_id' => md5($device[6][$i].$device[0]), "name" => $device[6][$i], "mac" => $device[0]));
			}		
		}
	}
}

/*    
    [0] => BSSID
    [1] => First time seen
    [2] => Last time seen
    [3] => channel
    [4] => Speed
    [5] => Privacy
    [6] => Cipher
    [7] => Authentication
    [8] => Power
    [9] => # beacons
    [10] => # IV
    [11] => LAN IP
    [12] => ID-length
    [13] => ESSID
    [14] => Key
 */

foreach($log[0] as $ap){
	print_r($ap);
}
