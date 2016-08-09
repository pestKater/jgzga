<?php

define('IN_PHPBB', true);
    $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);
    include('news/functions.php');

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();
    
    for($i = 1; $i <= 100; $i++) {
        $sql_arr = array(
            'title' => 'Testtitle ' . $i,
            'content' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
            'author' => 2,
            'postdate' => date("Y-m-d H:i:s"),
            'category' => 2,
        );
        
        $sql = 'INSERT INTO phpbb_news_articles ' . $db->sql_build_array('INSERT', $sql_arr);
        $db->sql_query($sql);
    }
    
    // Template laden
    $template->set_filenames(array(
        'body' => 'news.html',
    ));
    
    // Template abschließen
    make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
    page_footer();
