<?php
$pageTitle      = 'Alle Bilder';
$site           = 'listImages';
$breadcrumpName = 'Usergalerie';
$breadcrumpLink = append_sid("{$phpbb_root_path}gallery.$phpEx" . '?list=overview');
$images         = getAllImages(NULL, 40, $offset);
$maxPages       = ceil(countPictures() / 40);
$currentPage    = floor((($offset + 1) / 40) + 1);

foreach($images as $image) {

   $template->assign_block_vars('list', array(
        'ID'        => $image['id'], 
        'NAME'      => $image['name'],
    )); 
}

$template->assign_vars(array(
    'CONTEXT'       => 'image',
    'PAGINATION'    => 'list=image',
));