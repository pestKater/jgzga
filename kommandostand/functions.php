<?php
/**
* Holt die Modstrings aus der Datenbank, hängt vor jeden Mod das Verzeichnis und gibt einen String zurück
*/
function generateModstring($modstringId) {
	global $db;
	
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

/**
* Empfängt alle Werte der Tabelle 'tbl_server' und gibt diese zurück
*/
function getServerInfos($serverId) {
	$pdo = new PDO('mysql:host=localhost;dbname=website', 'root', '');
	$sql = 'SELECT name, port, server_dir, exe, gametype, basic_cfg, server_cfg, admin, params FROM tbl_server WHERE id = ' . $serverId;
	
	$pdo->query($sql);
	
	foreach ($pdo->query($sql) as $row) {
		
	}
	return $row;
}

/**
* Empfängt alle Werte der Tabelle 'tbl_modstrings' und gibt diese zurück
*/
function getModstringInfos($modstringId) {
	$pdo = new PDO('mysql:host=localhost;dbname=website', 'root', '');
	$sql = 'SELECT gametype, name, moddir, moddir_serv, moddir_cust, modstring, modstring_serv, modstring_cust FROM tbl_modstrings WHERE id = ' . $modstringId;
	
	$pdo->query($sql);
	
	foreach ($pdo->query($sql) as $row) {
		
	}
	return $row;
}

/**
* Sendet die geänderten Werte der Tabelle 'tbl_server' zurück
*/
function sentServerInfos($serverId, $name, $port, $server_dir, $exe, $gametype, $basic_cfg, $server_cfg, $admin, $params) {
	$pdo = new PDO('mysql:host=localhost;dbname=website', 'root', '');
	$sql = 'UPDATE tbl_server SET name = \'' . $name . '\' , port = \'' . $port . '\' , server_dir = \'' . $server_dir . '\' , exe = \'' . $exe . '\' , gametype = \'' . $gametype . '\' , basic_cfg = \'' . $basic_cfg . '\' , server_cfg = \'' . $server_cfg . '\' , admin = \'' . $admin . '\' , params = \'' . $params . '\' WHERE id = ' . $serverId;

	$pdo->query($sql);
}

/**
* Sendet die geänderten Werte der Tabelle 'tbl_modstrings' zurück
*/
function sentModstringInfos($modstringId, $gametype, $name, $moddir, $moddir_serv, $moddir_cust, $modstring, $modstring_serv, $modstring_cust) {
	$pdo = new PDO('mysql:host=localhost;dbname=website', 'root', '');
	$sql = 'UPDATE tbl_modstrings SET gametype = \'' . $gametype . '\' , name = \'' . $name . '\' , moddir = \'' . $moddir . '\' , moddir_serv = \'' . $moddir_serv . '\' , moddir_cust = \'' . $moddir_cust . '\' , modstring = \'' . $modstring . '\' , modstring_serv = \'' . $modstring_serv . '\' , modstring_cust = \'' . $modstring_cust . '\' WHERE id = ' . $modstringId;
	
	$pdo->query($sql);
}

function isUserMember($userId) {
	global $db;
	
	$sql = 'SELECT count(*) AS count FROM ' . USER_GROUP_TABLE . ' WHERE group_id = 8 AND user_id = ' . $userId;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	if($row['count'] == 1) {
		return true;
	}
	
	return false;
}

function getAllServer ($isAdmin = false) {
	global $db;
	$data = array ();
	$sql = 'SELECT id, name, port FROM tbl_server';
	
	if (!$isAdmin) {
		$sql .= ' WHERE admin = 0';
	}
	$result = $db->sql_query($sql);
	while($row = $db->sql_fetchrow($result)) {
		$data[$row['id']]['ServerId'] = $row['id'];
		$data[$row['id']]['ServerName'] = $row['name'];
		$data[$row['id']]['ServerPort'] = $row['port'];
	}
	$db->sql_freeresult($result);
	
	return $data;
}

function getAllModsets() {
	global $db;
	$data = array ();
	$sql = 'SELECT id, name FROM tbl_modstrings';
	
	$result = $db->sql_query($sql);
	while($row = $db->sql_fetchrow($result)) {
		$data[$row['id']]['ModsetId'] = $row['id'];
		$data[$row['id']]['ModsetName'] = $row['name'];
	}
	$db->sql_freeresult($result);
	
	return $data;
}

?>