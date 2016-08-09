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
    $userId = $user->data['user_id'];
    
    // Can add news
    $canAddNews = canUserAdd($userId);
    
    if(isset($_GET['list'])) {
        $site = 'list';
    }
    
    if(isset($_GET['view'])) {
        $site = 'view'; 
    }
    
    if(isset($_GET['edit'])) {
        $site = 'edit';
    }
    
    switch ($site) {
        case 'list':
            include('news/includes/sorterList.php');
            break;
        case 'view':
            include('news/includes/sorterView.php');
            break;
        case 'edit':
            include('news/includes/sorterEdit.php');
            break;
        default:
            include('news/includes/listOverview.php');
            break;
    }
    
    // Rendering stuff
    page_header($pageTitle);
    
    $template->assign_vars(array(
        'PAGETITLE'         => $pageTitle,
        'CAN_ADD'           => $canAddNews,
        'SITE'              => $site,
        'IS_MEMBER'         => isMember($userId),
    ));
    
    
    
    $template->assign_block_vars('navlinks', array(
        'FORUM_NAME'    => $breadcrumpName,
        'U_VIEW_FORUM'  => $breadcrumpLink,
    ));
    
    // Template laden
    $template->set_filenames(array(
        'body' => 'news.html',
    ));
    
    // Template abschließen
    make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
    page_footer();
?>