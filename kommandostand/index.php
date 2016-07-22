<?php
require_once 'functions.php';

$aktueller_server = 1;
$aktueller_modstring = 1;

$serverParams = generateParams($aktueller_server);
$modstrings = generateModstring($aktueller_modstring);
//$success = sentServerQuery($serverParams, $modstrings);

$admin_wert = 0;
//changeServerlock($aktueller_server,$admin_wert);

//echo $serverParams . $modstrings;
?>
