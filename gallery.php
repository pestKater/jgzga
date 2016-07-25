<?php
    // Definition der Funktionen
    define('IN_PHPBB', true);
    $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);
    include('gallery/functions.php');

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();
    $userId = $user->data['user_id'];
    
    // Darf ein Nutzer editieren oder anlegen?
    if(isset($_GET['image']) || isset($_GET['folder'])) {
        $idFromGet = $request->variable('id');
        
        if(isset($_GET['image'])) {
            $pictureId = $idFromGet;
        } else {
            $folderId = $idFromGet;
        }
    } else {
        $pictureId = 0;
    }
    
    $canEditPictures    = canUserEdit($userId, $pictureId);
    $canAddPictures     = canUserAdd($userId);
    $maxImages          = countPictures();
    $maxFolders         = countFolders();
    
    // Prüfen welcher Get-Parameter gesetzt ist
    if(isset($_GET['list'])) {
        $site = 'list';
    }
    
    if(isset($_GET['view'])) {
        $site = 'view'; 
    }
    
    if(isset($_GET['edit'])) {
        $site = 'edit';
    }
    
    // Je nach Get-Parameter entsprechenden Code einbinden
    switch($site) {
        case 'list': 
            include 'gallery/includes/sorterList.php';
            break;
        case 'view':
            include 'gallery/includes/sorterView.php';
            break;
        case 'edit':
            include 'gallery/includes/sorterEdit.php';
            break;
        default :
            include 'gallery/includes/listOverview.php';
            break;
    }
    
    // Globale Site-Variablen setzen
    page_header($pageTitle);
    
    $template->assign_vars(array(
        'AMOUNT_FOLDERS'    => $maxFolders,
        'AMOUNT_IMAGES'     => $maxImages,
        'PAGETITLE'         => $pageTitle,
        'SITE'              => $site,
        'CAN_EDIT'          => $canEditPictures,
        'CAN_ADD'           => $canAddPictures,
    ));
    
    $template->assign_block_vars('navlinks', array(
        'FORUM_NAME'    => $breadcrumpName,
        'U_VIEW_FORUM'  => $breadcrumpLink,
    ));
    
    // Template laden
    $template->set_filenames(array(
        'body' => 'gallery.html',
    ));
    
    // Template abschließen
    make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
    page_footer();
?>