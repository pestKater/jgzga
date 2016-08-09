<?php

$id = request_var('edit', 'new');
$page = 'edit';

if($id == 'new') {
    $pageTitle = 'Neue Seite';
} else {
    $site = getCustomsite($id);
    
    $pageTitle = $site['title'];
    $content = str_replace('\n', "\r\n" , $site['content']);
}
    
    

