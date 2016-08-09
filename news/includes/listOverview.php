<?php

$pageTitle  = 'Startseite';
$site       = 'overview';
$breadcrumpName = 'News';
$breadcrumpLink = append_sid("{$phpbb_root_path}news.$phpEx" . '?list=overview');

$template->assign_vars(array(
    'RA_SITE_DESC' => true,
    'RA_ENABLE' => true,
));

// get the featured articles
$featuredArticles = getFeaturedArticles($isMember);

foreach($featuredArticles as $singleFeatured) {
    $template->assign_block_vars('featured', array(
        'ID'        => $singleFeatured['id'],
        'TITLE'     => $singleFeatured['title'],
        'CONTENT'   => substr($singleFeatured['content'], 0, 590),
    ));
}

// get the other articles
$otherArticles = getOverviewArticles($isMember);

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

$events = getUpcomingEvents($isMember);

foreach ($events as $event) {
    $category = getEventCategory($event['eventCategory']);
    
    $template->assign_block_vars('events', array(
        'EVENT_ID'        => $event['id'],
        'TITLE'             => $event['title'],
        'CONTENT'   =>  substr($event['content'], 0, 190),
        'CATEGORY'  => $category,
        'DUE_DATE'  => date('d.m.Y \u\m H:i',strtotime($event['dueDate'])),
        
    ));
}

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
        'SHOUT'    => str_replace('\n', '<br>', $shout['comment']),
        'AVATAR'        => $poster['user_avatar'],
    ));
}

