<?php
$mode = request_var('view', 'file');
$id = request_var('id', '1');
        
if($mode == 'image') {
    include 'gallery/includes/viewPicture.php';
}
if($mode == 'folder') {
    $offset = request_var('offset', 0);
    
    include 'gallery/includes/viewFolder.php';
}