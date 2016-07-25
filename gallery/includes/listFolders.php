<?php
$pageTitle      = 'Alle Collections';
$site           = 'listImages';
$breadcrumpName = 'Usergalerie';
$breadcrumpLink = append_sid("{$phpbb_root_path}gallery.$phpEx" . '?list=overview');
$folders        = getAllFolders(40, $offset);
$maxPages       = ceil(countFolders() / 40);
$currentPage    = floor((($offset + 1) / 40) + 1);
$maxImages      = $maxFolders;

foreach($folders as $folder) {

    $template->assign_block_vars('list', array(
        'ID'        => $folder['id'], 
        'NAME'      => $folder['name'],
    )); 
   
    // Thumbnails holen
    $thumbnails = getAllImages($folder['id'], 9);
    
    // Daten ans Template übergeben
    foreach($thumbnails as $thumb) {
        $template->assign_block_vars('list.thumb', array(
            'ID'    => $thumb['id'],
        ));
    }
}

$template->assign_vars(array(
    'CONTEXT'       => 'folder',
    'PAGINATION'    => 'list=folder'
));