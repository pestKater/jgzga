<?php
$site           = 'viewEvent';
$breadcrumpName = 'Alle Events';
$breadcrumpLink = append_sid("{$phpbb_root_path}news.$phpEx" . '?list=events&offset=0');

$article = getArticleById($id);

if($article != false) {
    
    $category = getEventCategory($article['eventCategory']);
    $pageTitle  = date('d.m.Y \u\m H:i',strtotime($article['dueDate'])) . ': ' . $article['title'];
    $author     = getUserData($article['author']);

    $userrank = getRankName($author['user_rank']);
 
    $template->assign_vars(array(
        'TITLE'         => $article['title'],
        'DESCRIPTION'   => str_replace('\n', '<br>', $article['content']),
        'DATE'          => date("d.m.Y", strtotime($article['postdate'])),
        'FOLDERNAME'    => $category,
        'FOLDER_ID'     => $article['category'],
        'USER'          => $author['username'],
        'USER_ID'       => $author['user_id'],
        'AVATAR'        => $author['user_avatar'],
        'AVAILIBLE'     => true,
        'ARTICLE_ID'    => $id,
        'RANK'          => $userrank,
    ));
    
} else {       
    $pageTitle = 'Fehler!';

    $template->assign_vars(array(
        'AVAILIBLE' => false,
    ));
}

