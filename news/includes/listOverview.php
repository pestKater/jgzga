<?php

$pageTitle  = 'Startseite';
$site       = 'overview';
$breadcrumpName = 'News';
$breadcrumpLink = append_sid("{$phpbb_root_path}news.$phpEx" . '?list=overview');

$template->assign_vars(array(
    'IS_LANDINGPAGE' => true,
));

// get the featured articles
$featuredArticles = getFeaturedArticles($isMember);

foreach($featuredArticles as $singleFeatured) {
    $template->assign_block_vars('featured', array(
        'ID'        => $singleFeatured['id'],
        'TITLE'     => $singleFeatured['title'],
        'CONTENT'   => str_replace('\n', " " , substr($singleFeatured['content'], 0, 590)),
    ));
}

// get the other articles
$otherArticles = getOverviewArticles($isMember);

foreach($otherArticles as $singleArticle) {
    
    $category = getCategoryName($singleArticle['category']);
    
    $template->assign_block_vars('articles', array(
        'ID'        => $singleArticle['id'],
        'TITLE'     => $singleArticle['title'],
        'CONTENT'   => str_replace('\n', " " , substr($singleArticle['content'], 0, 190)),
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
        'DATE'    => date("\a\m d.m.y \u\m H:i", strtotime($shout['date'])),
        'SHOUT'    => str_replace('\n', '<br>', $shout['comment']),
        'AVATAR'        => $poster['user_avatar'],
    ));
}

// get the last pictures
$pictures = getNewestPictures();

foreach ($pictures as $picture) {
    $template->assign_block_vars('pictures', array(
        'ID'    => $picture['id'],
        'NAME'  => $picture['name'],
    ));
}

// get last posts:
$topics = getLastTopics();

foreach($topics as $topic) {
    $link       = 'viewtopic.php?f='.$topic['forum_id'].'&p='.$topic['topic_last_post_id'].'#p'.$topic['topic_last_post_id'];
    $name       = $topic['topic_title'];
    
    if(strlen($name) >= 24) {
        $name = substr($name, 0, 23) . '...';
    }
    
    $post       = getPostText($topic['topic_last_post_id']);
    
    $postText   = preg_replace('#\[[^\]]+\]#', '', $post['post_text']);
    $postText   = str_replace('{SMILIES_PATH}', './images/smilies', $postText);
    $postText   = substr($postText,0,150);
    $postDate   = date('d.m.Y \u\m H:i',$post['post_time']);
    
    $poster = getUserData($post['poster_id']);
    
    $template->assign_block_vars('posts', array(
        'TOPIC_NAME'    => $name,
        'TOPIC_LINK'    => $link,
        'POST_TEXT'     => $postText,
        'POST_DATE'     => $postDate,
        'AUTHOR_ID'     => $poster['user_id'],
        'AVATAR' => $poster['user_avatar'],
        'AUTHOR_NAME'   => $poster['username'],
    ));   
}

