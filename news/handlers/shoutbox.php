<?php
/*******************************************************************************
 * Includes
 ******************************************************************************/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path. 'common.' . $phpEx);
require_once('../functions.php');

/*******************************************************************************
 * Errorhandling
 ******************************************************************************/
if($request->is_set_post('submit')) {
    // do nothing
} else {
    exit;
}

$comment = html_entity_decode($db->sql_escape($request->variable('text', '')));
$comment = makeLinks($comment);

$sql_arr = array(
        'comment'       =>  $comment,
        'author'        =>  $db->sql_escape($request->variable('author', '')),
        'date'          =>  date("Y-m-d H:i:s"),
    );

    // Write Data in DB
    $sql = 'INSERT INTO phpbb_shoutbox ' . $db->sql_build_array('INSERT', $sql_arr);
    $db->sql_query($sql);

header('Location: ' . $phpbb_root_path. 'news.' . $phpEx);
?>