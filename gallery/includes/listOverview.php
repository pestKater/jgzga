<?php

// Folder auslesen
$sql = 'SELECT id, foldername FROM phpbb_gallery_folders ORDER BY id DESC LIMIT 8';
$result = $db->sql_query($sql);

while($row = $db->sql_fetchrow($result)) {
    $data[$row['id']]['id'] = $row['id'];
    $data[$row['id']]['name'] = $row['foldername'];
}

// Thumbs für Folder holen
foreach($data as $folder) {

    $template->assign_block_vars('folder', array(
        'ID'    => $folder['id'],
        'NAME'  => $folder['name'],
    ));

    $sql = 'SELECT id FROM phpbb_gallery WHERE in_group= '. $folder['id'] .' LIMIT 9';
    $result = $db->sql_query($sql);

    while($row = $db->sql_fetchrow($result)) {
        $template->assign_block_vars('folder.thumbs', array(
            'ID'    => $row['id'],
    ));
    }
}

$tmp = array(
    'SELECT count(*) AS count FROM phpbb_gallery',
    'SELECT count(*) AS count FROM phpbb_gallery_folders'
);

foreach($tmp as $sql) {
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $count[] .= $row['count'];
}

$template->assign_vars(array(
    'MAXCOLL'   => $count[1] - 8,
    'MAXFILE'   => $count[0] - 8,
    'SITE'      => 'overview',
));

// Bilder auslesen
$sql = 'SELECT id, name FROM phpbb_gallery ORDER BY id DESC LIMIT 8';
$result = $db->sql_query($sql);

while($row = $db->sql_fetchrow($result)) {
    $template->assign_block_vars('pic', array(
        'NAME'      => $row['name'],
        'ID'        => $row['id'],
    ));
}

$pageHeader = 'test';