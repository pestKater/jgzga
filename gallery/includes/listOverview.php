<?php
// Statische Infos an Template übergeben
$pageTitle  = 'Usergalerie';
$site       = 'overview';
$breadcrumpName = 'Usergalerie';
$breadcrumpLink = append_sid("{$phpbb_root_path}gallery.$phpEx" . '?list=overview');
$maxFolders = countFolders() - 8;
$maxImages  = countPictures() - 8;

// Folder holen
$folders = getAllFolders(8);

foreach($folders as $folder) {
    // Daten an Template übergeben
    $template->assign_block_vars('folder', array(
        'ID'    => $folder['id'],
        'NAME'  => $folder['name'],
    ));
    
    // Thumbnails holen
    $thumbnails = getAllImages($folder['id'], 9);
    
    // Daten ans Template übergeben
    foreach($thumbnails as $thumb) {
        $template->assign_block_vars('folder.thumb', array(
            'ID'    => $thumb['id'],
        ));
    }
}

// Bilder auslesen
$images = getAllImages(NULL, 8);

// Daten ans Template übergeben
foreach($images as $image) {
    $template->assign_block_vars('pic', array(
        'ID'    => $image['id'],
        'NAME'  => $image['name'],
    ));
}