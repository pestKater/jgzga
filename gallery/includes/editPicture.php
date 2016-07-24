<?php

if($id == 'new') {
    // Inits Languagefile
    page_header('Neues Bild');

    if($canAddPictures) {
        $canEditPictures = true;
    }

    $template->assign_vars(array(
        'NEWPICTURE'    => true,
        'DATE'          => date("Y-m-d"),
        'AUTHOR'        => $user->data['user_id'],
        'MODE'          => 'new',
        'CANEDIT'        => $canEditPictures,
    ));
} else {

    $sql = 'SELECT id, name, descr, date, author FROM phpbb_gallery WHERE id = ' . $id;
    $data = $db->sql_query($sql);

    while($row = $db->sql_fetchrow($data)) {

        $picture = $row['id'];
        $name   = $row['name'];
        $descr  = $row['descr'];
        $date   = $row['date'];
        $author = $row['author'];
    }
    $db->sql_freeresult($data);   

    if($userId == $author) {
        $canEditPictures = true;
    }

    page_header('Bild bearbeiten: '.$name);

    $template->assign_vars(array(
        'PICTUREID'     => $picture,
        'NAME'          => $name,
        'DESCRIPTION'   => nl2br($descr),
        'DATE'          => $date,
        'AUTHOR'        => $author,
        'MODE'          => 'edit',
        'CANEDIT'       => $canEditPictures,
    ));
}

$template->assign_block_vars('navlinks', array(
    'FORUM_NAME'    => 'Galerie',
    'U_VIEW_FORUM'  => append_sid("{$phpbb_root_path}gallery.$phpEx" . '?gallery=true&offset=0'),
));

// Load Template
$template->set_filenames(array(
    'body' => 'editpicture.html',
));
