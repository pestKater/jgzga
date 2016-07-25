<?php
$folder         = getFolderData($id);
$pageTitle      = 'Collection: ' . $folder['foldername'];
$site           = 'viewFolder';
$breadcrumpName = 'Collections';
$breadcrumpLink = append_sid("{$phpbb_root_path}gallery.$phpEx" . '?list=folder');
$images         = getAllImages($id, 40, $offset);
$maxPages       = ceil(countPictures($id) / 40);
$currentPage    = floor((($offset + 1) / 40) + 1);

foreach($images as $image) {

   $template->assign_block_vars('list', array(
        'ID'        => $image['id'], 
        'NAME'      => $image['name'],
    )); 
}

$template->assign_vars(array(
    'FOLDER'        => $id,
    'PREVIOUS'      => $offset-40,
    'NEXT'          => $offset+40,
    'MAX_PAGES'     => $maxPages,
    'CUR_PAGE'      => $currentPage,
));

$template->assign_vars(array(
    'CONTEXT'       => 'image',
    'PAGINATION'    => 'view=folder',
));