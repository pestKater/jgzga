<?php
    /**
    * @ignore
    */
    define('IN_PHPBB', true);
    $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);
    include($phpbb_root_path . 'custom/functions.' . $phpEx);
    

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();
    $isAdmin = isUserAdmin($user->data['user_id']);
    
    if(isset($_GET['page'])) {
        $site = 'view'; 
    }
    
    if(isset($_GET['edit'])) {
        $site = 'edit';
    }
    
    switch ($site) {
        case 'view':
            include('custom/includes/view.php');
            break;
        case 'edit':
            include('custom/includes/edit.php');
            break;
    } 

    // Seitentitel in Tab/Window
    page_header($pageTitle);

    $template->assign_vars(array(
            'PAGE'              => $page,
            'PAGETITLE'		=> $pageTitle,
            'PAGECONTENT'	=> $content,
            'ISADMIN'		=> $isAdmin,
            'PAGEID'		=> $id,
            'PAGELINK'          => $pageLink,
    ));

    // Set the filename of the template you want to use for this file.
    $template->set_filenames(array(
            'body' => 'custom.html',
    ));

    // Completing the script and displaying the page.
    page_footer();

?>