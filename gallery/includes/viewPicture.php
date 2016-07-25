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
    
    // Pagination
    $latestPicture      = getLastPicture();
    $firstPicture       = getFirstPicture();
    $neighbors          = getNeighbors($id);
    $lastPicture        = $neighbors[0];
    $nextPicture        = $neighbors[1];
    
    
    $folder = getFolderData($image['in_group']);
    
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
        'PAGINATION'    => 'view=image',
        'PREVIOUS'      => $lastPicture,
        'NEXT'          => $nextPicture,
        'MAX_PAGES'     => $latestPicture,
        'CUR_PAGE'      => $id,
        'FIRST_PAGE'    => $firstPicture,
        'VIEWERS_ID'    => $userId,
    ));
    
    // GET COMMENTS
    $comments = getComments($id);
    
    foreach($comments as $comment) {
        
        $usernameComment = getUserData($comment['user']);
        $text = str_replace('\n', '<br>', $comment['text']);
        
        $template->assign_block_vars('comment', array(
            'USERID'    => $comment['user'],
            'USERNAME'  => $usernameComment['username'],
            'DATE'      => date("d.m.Y", strtotime($comment['date'])),
            'COMMENT'   => $text,
            'AVATAR'    => $usernameComment['user_avatar'],
        ));
    }
    
} else {       
    $pageTitle = 'Fehler!';

    $template->assign_vars(array(
        'AVAILIBLE' => false,
    ));
}