<?php
/*******************************************************************************
 * Includes
 ******************************************************************************/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path. 'common.' . $phpEx);
require_once('../php_image_magician.php');
require_once('../functions.php');

/*******************************************************************************
 * Errorhandling
 ******************************************************************************/
if(!$request->is_set_post('submit')) {
    exit;
}

$sql_arr = array(
        'foldername'      =>  html_entity_decode($db->sql_escape($request->variable('title', ''))),
        'date'      =>  $db->sql_escape($request->variable('date', date("Y-m-d"))),
        'author'    =>  $db->sql_escape($request->variable('author', '')),
    );

if($request->variable('mode', '') == 'new') {
    /*******************************************************************************
     * Formhandling
     ******************************************************************************/

    // Write Data in DB
    $sql = 'INSERT INTO phpbb_gallery_folders ' . $db->sql_build_array('INSERT', $sql_arr);
    $db->sql_query($sql);

    // GET DATA ID
    $sql = 'SELECT id FROM phpbb_gallery_folders WHERE author = ' . $request->variable('author', '') . ' ORDER BY id DESC LIMIT 1';
    $db->sql_query($sql);

    $folderId = $db->sql_fetchfield('id');
}

if($request->variable('mode', '') == 'edit') {
    $folderId = $request->variable('folderid', '');
    
    $sql = 'UPDATE phpbb_gallery_folders  SET ' . $db->sql_build_array('UPDATE', $sql_arr) . ' WHERE id=' . $folderId;
    $db->sql_query($sql);
}

header('Location: ' . $phpbb_root_path. 'gallery.' . $phpEx . '?view=folder&id=' . $folderId);

?>