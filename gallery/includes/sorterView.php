<?php
$mode = request_var('view', 'file');
$id = request_var('id', '1');
        
if($mode == 'file') {
    include 'gallery/includes/viewFile.php';
}
if($mode == 'folder') {
    include 'gallery/includes/viewFolder.php';
}