<?php
    define('IN_PHPBB', true);
    $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();
    
    $canEditPictures = false;
    $canAddPictures = false;
    
    /**
     * Kann editieren?
     * Kann hinzufügen?
     */
    $userId = $user->data['user_id'];
    $groups = array(
        8,
    );
    $sql = "SELECT count(*) AS count FROM " . USER_GROUP_TABLE . " WHERE user_id = " . $userId . " AND " . $db->sql_in_set('group_id', $groups);
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    
    if($row['count'] > 0) {
        $canEditPictures = true;
    }
    
    $sql = "SELECT count(*) AS count FROM " . USER_GROUP_TABLE . " WHERE user_id = " . $userId . " AND " . $db->sql_in_set('group_id', $groups);
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    
    if($row['count'] > 0) {
        $canAddPictures = true;
    }
    
    
    
    /**
     * Übersicht der Bilder
     */
    if(isset($_GET['gallery'])) {
        
        // Inits Languagefile
        page_header('Galerie');

        // Load Template
        $template->set_filenames(array(
            'body' => 'members.html',
        ));
    }
    
    /**
     * Einzelansicht
     */
    if(isset($_GET['file'])) {
        
        // Inits Languagefile
        page_header('Galerie');

        // Load Template
        $template->set_filenames(array(
            'body' => 'members.html',
        ));
    }
    
    /**
     * Editieren
     */
    if(isset($_GET['edit'])) {
        $mode = request_var('edit', 'new');
        
        if($mode == 'new') {
            // Inits Languagefile
            page_header('Neues Bild');
            
            if($canAddPictures) {
                $canEditPictures = true;
            }
            
            $template->assign_vars(array(
                'NEWPICTURE'    => true,
                'DATE'          => date("Y-m-d"),
                'AUTHOR'        => $user->data['user_id'],
                'MODE'          => 'new',
                'CANEDIT'        => $canEditPictures,
            ));
        } elseif($mode == 'edit') {
            $picture = request_var('id', '1');
            
            $sql = 'SELECT id, name, descr, date, author FROM phpbb_gallery WHERE id = ' . $picture;
            $data = $db->sql_query($sql);
            
            while($row = $db->sql_fetchrow($data)) {
                
                $picture = $row['id'];
                $name   = $row['name'];
                $descr  = $row['descr'];
                $date   = $row['date'];
                $author = $row['author'];
            }
            $db->sql_freeresult($data);   
            
            if($userId == $author) {
                $canEditPictures = true;
            }
            
            page_header('Bild bearbeiten: '.$name);
            
            $template->assign_vars(array(
                'PICTUREID'     => $picture,
                'NAME'          => $name,
                'DESCRIPTION'   => $descr,
                'DATE'          => $date,
                'AUTHOR'        => $author,
                'MODE'          => 'edit',
                'CANEDIT'       => $canEditPictures,
            ));
        }
        
        // Load Template
        $template->set_filenames(array(
            'body' => 'editpicture.html',
        ));
    }
    
    
    
    // End Controller
    make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
    page_footer();
?>




