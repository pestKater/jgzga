<?php

$sql = 'SELECT id, foldername FROM phpbb_gallery_folders ORDER BY id DESC LIMIT 40 OFFSET ' . $offset;
$result = $db->sql_query($sql);

while($row = $db->sql_fetchrow($result)) {
    $data[$row['id']]['id'] = $row['id'];
    $data[$row['id']]['name'] = $row['foldername'];
}

foreach($data as $pic) {

   $template->assign_block_vars('pic', array(
        'NAME'      => $pic['name'],
        'PICTURE'   => $pic['id'],
    )); 
}

$sql = 'SELECT count(*) AS count FROM phpbb_gallery_folders';
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);

$max_files = $row['count']; 
$curSite = ($offset + 1) / 40;

$template->assign_vars(array(
    'PREVIOUS'  => $offset-40,
    'NEXT'      => $offset+40,
    'CANADD'    => $canAddPictures,
    'MAXSITES'  => ceil($max_files / 40),
    'ROWS'      => $max_files,
    'SITE'      => floor($curSite) + 1,
));

// Inits Languagefile
page_header('Galerie');

// Load Template
$template->set_filenames(array(
    'body' => 'showGallery.html',
));