<?php
/*******************************************************************************
 * Includes
 ******************************************************************************/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include('../common.php');
require_once('php_image_magician.php');

/*******************************************************************************
 * Errorhandling
 ******************************************************************************/
if(!$request->is_set_post('submit')) {
    exit;
}

$sql_arr = array(
        'name'      =>  $db->sql_escape($request->variable('title', '')),
        'date'      =>  $db->sql_escape($request->variable('date', date("Y-m-d"))),
        'descr'     =>  $db->sql_escape($request->variable('description', '')),
        'author'    =>  $db->sql_escape($request->variable('author', ''))
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
    $request->enable_super_globals();
    $tmp = $_SERVER['DOCUMENT_ROOT'] . "/jgzga/tmp/" . $file['name']; // AUF LINUX ANPASSEN
    // $tmp = '/var/www/html/forum/tmp' . $filename;
    move_uploaded_file($file['tmp_name'], $tmp);

    // Neuen Dateipfad definieren
    $new = $_SERVER['DOCUMENT_ROOT'] . "/jgzga/images/gallery/" . $pictureId . '.jpg'; // AUF LINUX UMSTELLEN
    // $new = '/var/www/html/forum/images/gallery' . $filename;

    // Bearbeiten
    $im = new imageLib($tmp);
    $im->resizeImage(1028, 500, 3);
    $im->setTransparency(false);
    $im->setFillColor('#CCCCCC');

    $im->saveImage($new);

    // Temporäre Datei Löschen
    unlink($tmp);
}

if($request->variable('mode', '') == 'edit') {
    $pictureId = $request->variable('picid', '');
    
    $sql = 'UPDATE phpbb_gallery  SET ' . $db->sql_build_array('UPDATE', $sql_arr) . ' WHERE id=' . $pictureId;
    $db->sql_query($sql);
}

header('Location: ../gallery.php?file=' . $pictureId);
?>