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
if(!$request->is_set_post('submit')) {
    exit();
} elseif(strlen($request->variable('comment', '')) < 3 ) {
    exit();
}

$pictureId = $request->variable('picture', '');
$comment =  html_entity_decode($request->variable('comment', ''));

$sql_arr = array(
    'user_id'       => $request->variable('author', ''),
    'picture_id'    => $pictureId,
    'post_date'     => date("Y-m-d H:i:s"),
    'content'       => $comment,
);

// Write Data in DB
$sql = 'INSERT INTO phpbb_gallery_comments ' . $db->sql_build_array('INSERT', $sql_arr);
$db->sql_query($sql);

header('Location: ' . $phpbb_root_path. 'gallery.' . $phpEx . '?view=image&id=' . $pictureId);

?>