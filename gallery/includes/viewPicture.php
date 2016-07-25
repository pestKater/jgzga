<?php

$pageTitle      = 'Alle Bilder';
$site           = 'viewPicture';
$breadcrumpName = 'Alle Bilder';
$breadcrumpLink = append_sid("{$phpbb_root_path}gallery.$phpEx" . '?list=image&offset=0');

$image = getPictureData($id);

if($image != false) {
    $pageTitle  = 'Bild: ' . $image['name'];
    $author     = getUserData($image['author']);
    $ismember   = canUserAdd($image['author']);

    if($ismember == true) {
        $userrank = getRankName($author['user_rank']);
    } else {
        $userrank = '';
    }
    
    $folder = getFolderData($image['in_group']);
    
    $descr = $image['descr'];
    $descr = preg_replace("~[\r\n]+~", '<br>', $descr);


    var_dump($descr);
    die;
    
    $template->assign_vars(array(
        'TITLE'         => $title,
        'DESCRIPTION'   => str_replace('\n', '<br>', $image['descr']),
        'DATE'          => date("d.m.Y", strtotime($image['date'])),
        'FOLDERNAME'    => $folder['foldername'],
        'FOLDER_ID'     => $folder['id'],
        'USER'          => $author['username'],
        'USER_ID'       => $author['user_id'],
        'AVATAR'        => $author['user_avatar'],
        'AVAILIBLE'     => true,
        'PICTURE'       => $id,
        'RANK'          => $userrank,
    ));
} else {       
    $pageTitle = 'Fehler!';

    $template->assign_vars(array(
        'AVAILIBLE' => false,
    ));
}