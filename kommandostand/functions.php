<?php
    /*define('IN_PHPBB', true);
    $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);
	*/
/**
* Holt die Modstrings aus der Datenbank, hängt vor jeden Mod das Verzeichnis und gibt einen String zurück
*/
function generateModstring($modstringId) {
	$modstring_client = '';
	$modstring_serv = '';
	
	$pdo = new PDO('mysql:host=localhost;dbname=website', 'root', '');

	$sql = 'SELECT moddir, moddir_serv,  moddir_cust,  modstring, modstring_serv, modstring_cust FROM tbl_modstrings WHERE id = ' . $modstringId;

	foreach ($pdo->query($sql) as $row) {
		if(isset($row['modstring'])) {
			$dir = $row['moddir'];
			$modList_client = explode(";", $row['modstring']);
			$modstring_client = '"-mod=';
			
			foreach ($modList_client as $value) {
				$value = $dir . $value . ';';
			
				$modstring_client .= $value;
			}
		}
		
		if(isset($row['modstring_serv'])) {
			$dir = $row['moddir_serv'];
			$modList_serv = explode(";", $row['modstring_serv']);
			$modstring_serv = '"-serverMod=';
			
			foreach ($modList_serv as $value) {
				$value = $dir . $value . ';';
			
				$modstring_serv .= $value;
			}
			$modstring_serv .= '"';
		}
		
		if(isset($row['modstring_cust'])) {
			$dir = $row['moddir_cust'];
			$modList_cust = explode(";", $row['modstring_cust']);
			
			foreach ($modList_cust as $value) {
				$value = $dir . $value . ';';
			
				$modstring_client .= $value;
			}
		}
		$modstring_client .= '"';
	}
	
	return $modstring_serv . ' ' . $modstring_client;
}

/**
* Holt die benötigten Server-Informationen aus der Datenbank
*/
function generateParams($serverId) {
	$params = '';
	
	$pdo = new PDO('mysql:host=localhost;dbname=website', 'root', '');

	$sql = 'SELECT port, server_dir, exe, basic_cfg, server_cfg, params FROM tbl_server WHERE id = ' . $serverId;

	foreach ($pdo->query($sql) as $row) {
		$server_dir = '"' . $row['server_dir'] . '"';
		$server_exe = '"' . $row['server_dir'] . '\\' . $row['exe'] . '"';
		$server_cfg = '-config=' . $row['server_cfg'];
		$basic_cfg = '-cfg=' . $row['basic_cfg'];
		$server_port = '-port=' . $row['port'];
		
		$startparameter = 'start "ArmA" /D ' . $server_dir . ' ' . $server_exe . ' ' . $server_port . ' ';
		
		if(isset($row['params'])) {
			$startparameter .= $row['params'] . ' ';
		}
		
		$startparameter .= $basic_cfg . ' ' . $server_cfg . ' ';
	}
	return $startparameter;
}

/**
* Sendet den Server-Command an die Query Tabelle
*/
function sentServerQuery($serverParams, $modstrings) {
	$pdo = new PDO('mysql:host=localhost;dbname=website', 'root', '');
	
	date_default_timezone_set('UTC');
	$date = date("Y-m-d H:i:s");
	$command = $serverParams . $modstrings;
	
	$statement = $pdo->prepare("INSERT INTO tbl_queue (action_date, server_command) VALUES (?, ?)");
	//$statement->execute(array($date, $command));
}

/**
* Sendet den aktuellen Admin-Wert des jeweiligen Servers an die Datenbank
*/
function changeServerlock($serverId, $admin_wert) {
	$pdo = new PDO('mysql:host=localhost;dbname=website', 'root', '');
	$sql = 'UPDATE tbl_server SET admin = ' . $admin_wert . ' WHERE id = ' . $serverId;
	
	$pdo->query($sql);
}
?>