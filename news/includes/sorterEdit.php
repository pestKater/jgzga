<?php
$mode = request_var('edit', 'article');
$id = request_var('id', 'new');

if($mode == 'article') {
    include 'news/includes/editArticle.php';
} elseif($mode == 'event') {
    //
}

