<?php
$site           = 'editEvent';
$breadcrumpName = 'Termine';
$breadcrumpLink = append_sid("{$phpbb_root_path}news.$phpEx" . '?list=events');

$categories = getAllEventCategories();

foreach($categories as $category) {
    $template->assign_block_vars('categories', array(
        'CAT_ID'    => $category['id'],
        'NAME'  => $category['name'],
    ));
}

if($id == 'new') {
    $pageTitle = 'Neuen Termin eintragen';
    
    $template->assign_vars(array(
        'DATE'          => date("Y-m-d H:i:s"),
        'AUTHOR'        => $userId,
        'MODE'          => 'new',
    ));
    
} else {
    $article = getEventById($id);
    
    $pageTitle = 'Termin bearbeiten: ' . $article['title'];
    $template->assign_vars(array(
        'DATE'          => date("Y-m-d H:i:s"),
        'AUTHOR'        => $userId,
        'MODE'          => 'edit',
        'ARTICLE_ID'    => $article['id'],
        'AUTHOR'        => $article['author'],
        'TITLE'         => $article['title'],
        'DESCRIPTION'   => str_replace('\n', "\r\n" , $article['content']),
        'CATEGORY'      => $article['eventCategory'],
        'DUE_DATE'      => $article['dueDate'],
    ));
}