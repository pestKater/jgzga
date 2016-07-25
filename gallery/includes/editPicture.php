<?php
$site           = 'editImage';
$breadcrumpName = 'Alle Bilder';
$breadcrumpLink = append_sid("{$phpbb_root_path}gallery.$phpEx" . '?list=image&offset=0');
$folders = array(
    0 => array(
        'id'            => 0,
        'name'    => 'Keine Collection',
    ),
);
$allFolders = getAllFolders();

foreach($allFolders as $folder) {
    
    $folders[$folder['id']] = $folder;
}

foreach($folders as $folder) {
    
    $template->assign_block_vars('folders', array(
        'ID'    => $folder['id'],
        'NAME'  => $folder['name'],
    ));
}

if($id == 'new') {
    $pageTitle = 'Neues Bild hochladen';

    $template->assign_vars(array(
        'DATE'          => date("Y-m-d H:i:s"),
        'AUTHOR'        => $user->data['user_id'],
        'MODE'          => 'new',
    ));
    
} else {
    $picture = getPictureData($id);
    $pageTitle = 'Bild bearbeiten: ' . $picture['name'];

    $template->assign_vars(array(
        'PICTURE_ID'    => $picture['id'],
        'NAME'          => $picture['name'],
        'DESCRIPTION'   => nl2br($picture['descr']),
        'DATE'          => $picture['date'],
        'AUTHOR'        => $picture['author'],
        'MODE'          => 'edit',
    ));
}
