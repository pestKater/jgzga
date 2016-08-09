<?php
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'custom/functions.' . $phpEx);

// 1. Daten entgegen nehmen
if(!$request->is_set_post('submit')) {
    exit;
}

$title 		= html_entity_decode($db->sql_escape($request->variable('title', '')));
$content 	= html_entity_decode($db->sql_escape($request->variable('content', '')));
$pageid 	= $request->variable('pageid', '');
$newsite        = true;

$content = makeLinks($content);

// 2. Checken ob Datensatz vorhanden
$sql = "SELECT count(*) AS count FROM phpbb_customsite WHERE id=" . $pageid;
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

if($row['count'] == '1') {
	$newsite = false;
}

// 3. Schreiben
$sql_arr = array(
	'title' => $title,
	'content' => $content,
);

if($newsite) {
	$sql = 'INSERT INTO phpbb_customsite ' . $db->sql_build_array('INSERT', $sql_arr); 
} else {
	$sql = 'UPDATE phpbb_customsite SET ' . $db->sql_build_array('UPDATE', $sql_arr);
}

$db->sql_query($sql);

// 4. Redirect
if($newsite) {
	$sql = "SELECT id FROM phpbb_customsite ORDER BY id DESC LIMIT 1";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$pageid = $row['id'];
}

header('Location: ' . $phpbb_root_path . 'custom.' . $phpEx . '?page='.$pageid);

?>