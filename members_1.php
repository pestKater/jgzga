<?php
    define('IN_PHPBB', true);
    $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();
    
    // Inits Languagefile
    page_header('test');
    
    // Groups that should displayed
    $group_ids = array(
        5,
        9,
    );
    
    $data = array();
    
    // Get Groups from Database
    $sql = 'SELECT group_id, group_name, group_desc FROM ' . GROUPS_TABLE . ' WHERE ' . $db->sql_in_set('group_id', $group_ids);
    $result = $db->sql_query($sql);
    
    // Write Data for each Group
    while ($row = $db->sql_fetchrow($result)) {
        $data[$row['group_id']]['group_name'] = $row['group_name'];
        $data[$row['group_id']]['group_desc'] = $row['group_desc'];
        
        /*$template->assign_block_vars('unit', array(
            'GROUPNAME'     => $row['group_name'],
            'DESCRIPTION'   => $row['group_desc'],
        ));
        
        // Get Groupmembers from Database
        $sqlusers = 'SELECT user_id FROM ' . USER_GROUP_TABLE . ' WHERE group_id = ' . $row['group_id'];
        
        $users = $db->sql_query($sqlusers);
        
        // Get User from Database
        while ($user = $db->sql_fetchrow($users)) {
            $sql_arr = array(
                'SELECT'    => 'u.user_id, u.username, u.user_avatar, r.rank_title, r.rank_image',
                'FROM'      => array(
                    USERS_TABLE => 'u',
                    RANKS_TABLE => 'r'
                ),
                'WHERE'     => 'u.user_id = ' . $user['user_id'] . ' AND u.user_rank = r.rank_id',
            );
            $sqluserdata = $db->sql_build_query('SELECT', $sql_arr);
            
            $userdata = $db->sql_query($sqluserdata);         
            
            // Write Data for each User
            while($single = $db->sql_fetchrow($userdata)) {
                $template->assign_block_vars('unit.member', array(
                    'USERID'    => $single['user_id'],
                    'USERNAME'  => $single['username'],
                    'AVATAR'    => $single['user_avatar'],
                    'RANK'      => $single['rank_title'],
                    'RANGIMAGE' => $single['rank_image'],
                ));
            }
            $db->sql_freeresult($userdata);
        } 
        $db->sql_freeresult($users);*/
    }
    $db->sql_freeresult($result);
    
    // Load Template
    $template->set_filenames(array(
        'body' => 'members.html',
    ));

    // End Controller
    make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
    page_footer();
?>