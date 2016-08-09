<?php
$pageTitle  = 'Alle Termine';
$site       = 'archive';
$breadcrumpName = 'News';
$breadcrumpLink = append_sid("{$phpbb_root_path}news.$phpEx" . '?list=overview');

// get all the upcoming events
$upcomingEvents = getAllUpcomingEvents($isMember);
$pastEvents = getAllPastEvents($isMember);

$template->assign_vars(array(
    'EVENTS' => true,
));

foreach($upcomingEvents as $upcome) {
    $template->assign_block_vars('article', array(
        'ARTICLE_ID'    => $upcome['id'],
        'TITLE'         => $upcome['title'],
        'CONTENT'       => substr($upcome['content'], 0, 350),
        'DUE_DATE'      => date('d.m.Y \u\m H:i',strtotime($upcome['dueDate'])),
    ));
}
foreach($pastEvents as $past) {
    $template->assign_block_vars('past', array(
        'ARTICLE_ID'    => $past['id'],
        'TITLE'         => $past['title'],
        'CONTENT'       => substr($past['content'], 0, 350),
        'DUE_DATE'      => date('d.m.Y \u\m H:i',strtotime($past['dueDate'])),
    ));
}