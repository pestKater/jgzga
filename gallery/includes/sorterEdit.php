<?php

$context = request_var('edit', 'image');
$id = request_var('id', 'new');

if($context == 'image') {

    include 'gallery/includes/editPicture.php';

} else {
    include 'gallery/includes/editFolder.php';
}

