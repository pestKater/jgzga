<?php
$site           = 'edit';
$breadcrumpName = 'News';
$breadcrumpLink = append_sid("{$phpbb_root_path}news.$phpEx" . '?list=overview');

$categories = getAllCategories();

foreach($categories as $category) {
    $template->assign_block_vars('categories', array(
        'CAT_ID'    => $category['id'],
        'NAME'  => $category['name'],
    ));
}

if($id == 'new') {
    $pageTitle = 'Neuen Post erstellen';
    
    $template->assign_vars(array(
        'DATE'          => date("Y-m-d H:i:s"),
        'AUTHOR'        => $userId,
        'MODE'          => 'new',
    ));
    
} else {
    $article = getArticleById($id);
    $pageTitle = 'News bearbeiten: ' . $article['title'];
    
    $template->assign_vars(array(
        'DATE'          => date("Y-m-d H:i:s"),
        'AUTHOR'        => $userId,
        'MODE'          => 'edit',
        'ARTICLE_ID'    => $article['id'],
        'AUTHOR'        => $article['author'],
        'TITLE'         => $article['title'],
        'DESCRIPTION'   => str_replace('\n', "\r\n" , $article['content']),
        'CATEGORY'      => $article['category'],
    ));
}