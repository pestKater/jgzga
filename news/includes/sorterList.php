<?php
$mode = request_var('list', 'overview');
$id = request_var('id', 2);

if($mode == 'overview') {
    include 'news/includes/listOverview.php';
}
elseif($mode == 'category') {
    include 'news/includes/listCategory.php';
}
elseif($mode == 'archive') {
    include 'news/includes/listAll.php';
}
elseif($mode == 'shouts') {
    include 'news/includes/listShouts.php';
}
elseif($mode == 'events') {
    include 'news/includes/listEvents.php';
}

