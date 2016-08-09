<?php
$mode = request_var('view', 'article');
$id = request_var('id', '1');

if($mode == 'article') {
    include 'news/includes/viewArticle.php';
} elseif($mode == 'event') {
    include 'news/includes/viewEvent.php';
}

