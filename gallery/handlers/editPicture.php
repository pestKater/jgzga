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
if($request->is_set_post('submit')) {
    // do nothing
} elseif($request->is_set_post('delete')) {
    // do nothing
} else {
    exit;
}

$sql_arr = array(
        'name'      =>  html_entity_decode($db->sql_escape($request->variable('title', ''))),
        'date'      =>  $db->sql_escape($request->variable('date', date("Y-m-d"))),
        'descr'     =>  html_entity_decode($db->sql_escape($request->variable('description', ''))),
        'author'    =>  $db->sql_escape($request->variable('author', '')),
        'in_group'  =>  $request->variable('folder', ''),
    );

if($request->variable('mode', '') == 'new') {
    /*******************************************************************************
     * Formhandling
     ******************************************************************************/

    // Write Data in DB
    $sql = 'INSERT INTO phpbb_gallery ' . $db->sql_build_array('INSERT', $sql_arr);
    $db->sql_query($sql);

    // GET DATA ID
    $sql = 'SELECT id FROM phpbb_gallery WHERE author = ' . $request->variable('author', '') . ' ORDER BY id DESC LIMIT 1';
    $db->sql_query($sql);

    $pictureId = $db->sql_fetchfield('id');

    /*******************************************************************************
     * Imagehandling
     *******************************************************************************/
    $file = $request->file('fileToUpload');        

    // Verschieben der Datei in temorären Ordner
    $tmp = '/var/www/html/forum/tmp/' . $file['name'];
    move_uploaded_file($file['tmp_name'], $tmp);

    // Neuen Dateipfad definieren
    $new = '/var/www/html/forum/images/gallery/' . $pictureId . '.jpg';
    $thumb = '/var/www/html/forum/images/gallery/thumbnails/' . $pictureId . '.jpg';

    // Bearbeiten
    $im = new imageLib($tmp);
    $im->resizeImage(1028, 500, 3);
    $im->setTransparency(false);
    $im->setFillColor('#CCCCCC');
    $im->saveImage($new);
    
    $im = new imageLib($new);
    $im->resizeImage(200, 200, 'crop');
    $im->saveImage($thumb);

    // Temporäre Datei Löschen
    unlink($tmp);
}

if($request->variable('mode', '') == 'edit') {
    $pictureId = $request->variable('picid', '');
    
    if($request->is_set_post('submit')) {
        $sql = 'UPDATE phpbb_gallery  SET ' . $db->sql_build_array('UPDATE', $sql_arr) . ' WHERE id=' . $pictureId;
        $db->sql_query($sql);
    } elseif($request->is_set_post('delete')) {
        $sql = 'DELETE FROM phpbb_gallery WHERE id= ' . $pictureId;
        $db->sql_query($sql);
        
        header('Location: ' . $phpbb_root_path. 'gallery.' . $phpEx . '?list=image');
        exit;
    }
}

header('Location: ' . $phpbb_root_path. 'gallery.' . $phpEx . '?view=image&id=' . $pictureId);
?>