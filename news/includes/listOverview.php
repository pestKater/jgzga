<?php

$pageTitle  = 'Jägerzug Achilles I';
$site       = 'overview';
$breadcrumpName = 'News';
$breadcrumpLink = append_sid("{$phpbb_root_path}news.$phpEx" . '?list=overview');

// get the featured articles
$featuredArticles = getFeaturedArticles();

foreach($featuredArticles as $singleFeatured) {
    $template->assign_block_vars('featured', array(
        'ID'        => $singleFeatured['id'],
        'TITLE'     => $singleFeatured['title'],
        'CONTENT'   => substr($singleFeatured['content'], 0, 590),
    ));
}

// get the other articles
$otherArticles = getOverviewArticles();

foreach($otherArticles as $singleArticle) {
    
    $category = getCategoryName($singleArticle['category']);
    
    $template->assign_block_vars('articles', array(
        'ID'        => $singleArticle['id'],
        'TITLE'     => $singleArticle['title'],
        'CONTENT'   => substr($singleArticle['content'], 0, 190),
        'CATEGORY'  => $category,
    ));
}
// get the next events


// get the last shouts
$shouts = getShoutsOverview();

$template->assign_vars(array(
    'USER_ID' => $userId,
));

foreach ($shouts as $shout) {
    $poster = getUserData($shout['author']);
    
    $template->assign_block_vars('shouts', array(
        'SHOUT_ID'    => $shout['id'],
        'AUTHOR_ID'     => $shout['author'],
        'AUTHOR_NAME'    => $poster['username'],
        'DATE'    => date("\a\m m.d.y \u\m H:i", strtotime($shout['date'])),
        'SHOUT'    => $shout['comment'],
        'AVATAR'        => $poster['user_avatar'],
    ));
}

