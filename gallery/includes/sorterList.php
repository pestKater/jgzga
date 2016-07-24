<?php
$mode = request_var('list', 'overview');
$offset = request_var('offset', 0);
        
if($mode == 'image') {
    
    include 'gallery/includes/listPictures.php';

}

if($mode == 'folder') {
    
    include 'gallery/includes/listFolders.php';

}

if($mode == 'overview') {

    include 'gallery/includes/listOverview.php';

}
