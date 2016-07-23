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
        4,
        5,
    );
    $sql = "SELECT count(*) AS count FROM " . USER_GROUP_TABLE . " WHERE user_id = " . $userId . " AND " . $db->sql_in_set('group_id', $groups);
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    
    if($row['count'] > 0) {
        $canEditPictures = true;
    }
    
    $groups = array(
        8,
    );
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
        
        $mode = request_var('gallery', 'overview');
        
        if($mode == 'images') {
            $offset = request_var('offset', 0);

            $sql = 'SELECT id, name FROM phpbb_gallery ORDER BY id DESC LIMIT 40 OFFSET ' . $offset;
            $result = $db->sql_query($sql);

            while($row = $db->sql_fetchrow($result)) {
                $data[$row['id']]['id'] = $row['id'];
                $data[$row['id']]['name'] = $row['name'];
            }

            foreach($data as $pic) {

               $template->assign_block_vars('pic', array(
                    'NAME'      => $pic['name'],
                    'PICTURE'   => $pic['id'],
                )); 
            }

            $sql = 'SELECT count(*) AS count FROM phpbb_gallery';
            $result = $db->sql_query($sql);
            $row = $db->sql_fetchrow($result);

            $max_files = $row['count']; 
            $curSite = ($offset + 1) / 40;

            $template->assign_vars(array(
                'PREVIOUS'  => $offset-40,
                'NEXT'      => $offset+40,
                'CANADD'    => $canAddPictures,
                'MAXSITES'  => ceil($max_files / 40),
                'ROWS'      => $max_files,
                'SITE'      => floor($curSite) + 1,
            ));

            // Inits Languagefile
            page_header('Galerie');

            // Load Template
            $template->set_filenames(array(
                'body' => 'showGallery.html',
            ));
        }
        
        if($mode == 'overview') {
            // Folder auslesen
            $sql = 'SELECT id, foldername FROM phpbb_gallery_folders ORDER BY id DESC LIMIT 8';
            $result = $db->sql_query($sql);
            
            while($row = $db->sql_fetchrow($result)) {
                $data[$row['id']]['id'] = $row['id'];
                $data[$row['id']]['name'] = $row['foldername'];
            }
            
            // Thumbs für Folder holen
            foreach($data as $folder) {
                
                $template->assign_block_vars('folder', array(
                    'ID'    => $folder['id'],
                    'NAME'  => $folder['name'],
                ));
                
                $sql = 'SELECT id FROM phpbb_gallery WHERE in_group= '. $folder['id'] .' LIMIT 9';
                $result = $db->sql_query($sql);
                
                while($row = $db->sql_fetchrow($result)) {
                    $template->assign_block_vars('folder.thumbs', array(
                        'ID'    => $row['id'],
                ));
                }
            }
            
            $tmp = array(
                'SELECT count(*) AS count FROM phpbb_gallery',
                'SELECT count(*) AS count FROM phpbb_gallery_folders'
            );
            
            foreach($tmp as $sql) {
                $result = $db->sql_query($sql);
                $row = $db->sql_fetchrow($result);
                $count[] .= $row['count'];
                
                
            }
            
            $template->assign_vars(array(
                'MAXCOLL' => $count[1] - 8,
                'MAXFILE'   => $count[0] - 8,
            ));
            
            // Bilder auslesen
            $sql = 'SELECT id, name FROM phpbb_gallery ORDER BY id DESC LIMIT 8';
            $result = $db->sql_query($sql);
            
            while($row = $db->sql_fetchrow($result)) {
                $template->assign_block_vars('pic', array(
                    'NAME'      => $row['name'],
                    'ID'        => $row['id'],
                ));
            }

            page_header('Usergalerie');

            // Load Template
            $template->set_filenames(array(
                'body' => 'galleryOverview.html',
            ));
        }
    }
    
    /**
     * Einzelansicht
     */
    if(isset($_GET['file'])) {
        
        $file = request_var('file', '1');
        
        $sql = 'SELECT name, date, descr, author FROM phpbb_gallery WHERE id= ' . $file;
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $db->sql_freeresult($result);
        
        if($row != false) {
            $title = $row['name'];
            $descr = $row['descr'];
            $date = date("d.m.Y", strtotime($row['date']));
            $author = $row['author'];
            
            $sql = 'SELECT username, user_avatar FROM ' . USERS_TABLE . " WHERE user_id = " . $row['author'];
            $result = $db->sql_query($sql);
            $row = $db->sql_fetchrow($result);
            $db->sql_freeresult($result);
            
            $username = $row['username'];
            $avatar = $row['user_avatar'];
            
            page_header('Galerie: ' . $title);
            
            if($userId == $author) {
                $canEditPictures = true;
            }
            
            $template->assign_vars(array(
                'TITLE'         => $title,
                'DESCRIPTION'   => str_replace('\n', '<br>', $descr),
                'DATE'          => $date,
                'USER'          => $username,
                'USERID'        => $author,
                'AVATAR'        => $avatar,
                'AVAILIBLE'     => true,
                'CANEDIT'       => $canEditPictures,
                'CANADD'        => $canAddPictures,
                'PICTURE'         => $file,
            ));
        } else {       
            page_header('Fehler');
            
            $template->assign_vars(array(
            'TITLE'     => 'Fehler',
            'AVAILIBLE' => false,
        ));
        }
        
        $template->assign_block_vars('navlinks', array(
            'FORUM_NAME'    => 'Galerie',
            'U_VIEW_FORUM'  => append_sid("{$phpbb_root_path}gallery.$phpEx" . '?gallery=true&offset=0'),
        ));
        
        // Load Template
        $template->set_filenames(array(
            'body' => 'showPicture.html',
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
                'DESCRIPTION'   => nl2br($descr),
                'DATE'          => $date,
                'AUTHOR'        => $author,
                'MODE'          => 'edit',
                'CANEDIT'       => $canEditPictures,
            ));
        }
        
        $template->assign_block_vars('navlinks', array(
            'FORUM_NAME'    => 'Galerie',
            'U_VIEW_FORUM'  => append_sid("{$phpbb_root_path}gallery.$phpEx" . '?gallery=true&offset=0'),
        ));
        
        // Load Template
        $template->set_filenames(array(
            'body' => 'editpicture.html',
        ));
    }
      
    // End Controller
    make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
    page_footer();
?>




