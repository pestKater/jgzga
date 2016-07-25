<?php
$site           = 'editFolder';
$breadcrumpName = 'Alle Collectionen';
$breadcrumpLink = append_sid("{$phpbb_root_path}gallery.$phpEx" . '?list=folder&offset=0');

if($id == 'new') {
    $pageTitle = 'Neues Collection erstellen';

    $template->assign_vars(array(
        'DATE'          => date("Y-m-d H:i:s"),
        'AUTHOR'        => $user->data['user_id'],
        'MODE'          => 'new',
    ));
    
} else {
    $folder = getFolderData($id);
    $pageTitle = 'Collection bearbeiten: ' . $folder['foldername'];
    
    $template->assign_vars(array(
        'FOLDER_ID'     => $folder['id'],
        'NAME'          => $folder['foldername'],
        'DATE'          => $folder['date'],
        'AUTHOR'        => $folder['author'],
        'MODE'          => 'edit',
    ));
}
