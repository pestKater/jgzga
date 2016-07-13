<?php

//change this to reflect the servers that you want to query
$servers = array(
	array(
		'id' => 'JgZg-A Public',
		'type' => 'armedassault3',
		'host' => '138.201.82.235:2312'
	),
	array(
		'id' => 'JgZg-A Intern',
		'type' => 'armedassault3',
		'host' => '138.201.82.235:2322'
	),
	array(
		'id' => 'JgZg-A Test',
		'type' => 'armedassault3',
		'host' => '138.201.82.235:2332'
	)
	
);


//change this to toggle querying geographic information based on the IP address
define("GEOIP", "false");


/* phparma2serverstatus version (you don't need to change this) */
define("VERSION", "0.2");

?>
