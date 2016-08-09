<?php
$pageTitle  = 'Schreischachtel';
$site       = 'shoutbox';
$breadcrumpName = 'News';
$breadcrumpLink = append_sid("{$phpbb_root_path}news.$phpEx" . '?list=overview');

$shouts = getAllShouts();

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