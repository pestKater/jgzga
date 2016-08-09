<?php   

$id = request_var('page', 'new');

if($id == 'new') {
    header('Location: ' . $phpbb_root_path. 'index.' . $phpEx . '?page=forum');
    exit;
}

$site = getCustomsite($id);

$page = 'view';
$pageTitle = $site['title'];
$content = str_replace('\n', '', $site['content']);
$pageLink = $phpbb_root_path . 'custom.' . $phpEx .'?page=' . $id;