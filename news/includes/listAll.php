<?php
$pageTitle  = 'Archiv';
$site       = 'archive';
$breadcrumpName = 'News';
$breadcrumpLink = append_sid("{$phpbb_root_path}news.$phpEx" . '?list=overview');

// get all the Articles
$articles = getAllArticles($isMember);

foreach($articles as $article) {
    $template->assign_block_vars('article', array(
        'ARTICLE_ID'    => $article['id'],
        'TITLE'         => $article['title'],
        'CONTENT'       => substr($article['content'], 0, 350),
        'ARCHIVE'       => true,
        'CATEGORY'      => getCategoryName($article['category']),
    ));
}