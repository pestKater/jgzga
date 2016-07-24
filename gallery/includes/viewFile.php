<?php

$sql = 'SELECT name, date, descr, author FROM phpbb_gallery WHERE id= ' . $id;
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

if($row != false) {
    $title = $row['name'];
    $descr = $row['descr'];
    $date = date("d.m.Y", strtotime($row['date']));
    $author = $row['author'];

    $sql = 'SELECT username, user_avatar FROM ' . USERS_TABLE . " WHERE user_id = " . $row['author'];
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);

    $username = $row['username'];
    $avatar = $row['user_avatar'];

    page_header('Galerie: ' . $title);

    if($userId == $author) {
        $canEditPictures = true;
    }

    $template->assign_vars(array(
        'TITLE'         => $title,
        'DESCRIPTION'   => str_replace('\n', '<br>', $descr),
        'DATE'          => $date,
        'USER'          => $username,
        'USERID'        => $author,
        'AVATAR'        => $avatar,
        'AVAILIBLE'     => true,
        'CANEDIT'       => $canEditPictures,
        'CANADD'        => $canAddPictures,
        'PICTURE'       => $id,
    ));
} else {       
    page_header('Fehler');

    $template->assign_vars(array(
    'TITLE'     => 'Fehler',
    'AVAILIBLE' => false,
));
}

$template->assign_block_vars('navlinks', array(
    'FORUM_NAME'    => 'Galerie',
    'U_VIEW_FORUM'  => append_sid("{$phpbb_root_path}gallery.$phpEx" . '?list=images&offset=0'),
));

// Load Template
$template->set_filenames(array(
    'body' => 'showPicture.html',
));

