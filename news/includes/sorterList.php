<?php
$mode = request_var('list', 'overview');

if($mode == 'overview') {
    include 'news/includes/listOverview.php';
}

