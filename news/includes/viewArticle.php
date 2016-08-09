<?php
$site           = 'viewArticle';
$breadcrumpName = 'Alle News';
$breadcrumpLink = append_sid("{$phpbb_root_path}news.$phpEx" . '?list=article&offset=0');

$article = getArticleById($id);

if($article != false) {
    
    if($article['category'] == 1) {
        header('Location: ' . $phpbb_root_path. 'news.' . $phpEx . '?view=event&id='.$id);
        exit;
    }
    
    $category = getCategoryName($article['category']);
    $pageTitle  = $article['title'];
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

