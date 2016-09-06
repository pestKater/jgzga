<?php
    define('IN_PHPBB', true);
    $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);
    include('wunschbox/functions.php');

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();
    
    // Check if the User is Member
    $isMember = isMember($user->data['user_id']);
    
    if(!$isMember) {
        header('Location: ' . $phpbb_root_path. 'index.' . $phpEx);
        exit();
    }
    
    $template->assign_vars(array(
        'USERID'        => $user->data['user_id'],
        'EMAIL'         => $user->data['user_email'],
        'NICK'          => $user->data['username']
    ));
    
    // Rendering stuff
    page_header($pageTitle);
    
    // Template laden
    $template->set_filenames(array(
        'body' => 'wunschbox.html',
    ));
    
    // Template abschließen
    make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
    page_footer();
?>

