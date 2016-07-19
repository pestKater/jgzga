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
    page_header('Members');
    
    // Groups that should displayed
    $group_ids = array(
        5,
        9,
    );
    
    // Initialize Data-Array
    $data = array();
    
    // Get Groups from Database
    $sql = 'SELECT group_id, group_name, group_desc FROM ' . GROUPS_TABLE . ' WHERE ' . $db->sql_in_set('group_id', $group_ids);
    $result = $db->sql_query($sql);
    
    // Write Data for each Group
    while ($row = $db->sql_fetchrow($result)) {
        $data[$row['group_id']]['groupid'] = $row['group_id'];
        $data[$row['group_id']]['groupname'] = $row['group_name'];
        $data[$row['group_id']]['description'] = $row['group_desc'];
        $data[$row['group_id']]['users'] = array();
        
        // Get Groupmembers from Database
        $sqlusers = 'SELECT user_id FROM ' . USER_GROUP_TABLE . ' WHERE group_id = ' . $row['group_id'];
        $users = $db->sql_query($sqlusers);
        
        // Get User from Database
        while ($user = $db->sql_fetchrow($users)) {
            $sqluserdata = 'SELECT user_id, username, user_avatar, user_rank FROM ' . USERS_TABLE . ' WHERE user_id = ' . $user['user_id'];
            $userdata = $db->sql_query($sqluserdata);
            
            // Write Data for each User
            while($single = $db->sql_fetchrow($userdata)) {
                $data[$row['group_id']]['users'][$single['user_id']]['userid'] =  $single['user_id'];
                $data[$row['group_id']]['users'][$single['user_id']]['username'] =  $single['username'];
                $data[$row['group_id']]['users'][$single['user_id']]['avatar'] =  $single['user_avatar'];
                
                // Get Ranks from Database
                $sqlrank = 'SELECT rank_title, rank_image FROM ' . RANKS_TABLE . ' WHERE rank_id = ' . $single['user_rank'];
                $rank = $db->sql_query($sqlrank);
                
                // Write Ranks to User
                while($singlerank = $db->sql_fetchrow($rank)) {
                    $data[$row['group_id']]['users'][$single['user_id']]['rank'] =  $singlerank['rank_title'];
                    $data[$row['group_id']]['users'][$single['user_id']]['rankimage'] =  $singlerank['rank_image'];
                }
                $db->sql_freeresult($rank);
            }
            $db->sql_freeresult($userdata);
        } 
        $db->sql_freeresult($users);
    }
    $db->sql_freeresult($result);
    
    // Give the Data to the Template
    foreach($data as $unit) {
        
        $template->assign_block_vars('unit', array(
            'GROUPNAME'     => $unit['groupname'],
            'DESCRIPTION'   => $unit['description'],
        ));
        
        foreach($unit['users'] as $member) {
            $template->assign_block_vars('unit.member', array(
                'USERID'    => $member['userid'],
                'USERNAME'  => $member['username'],
                'AVATAR'    => $member['avatar'],
                'RANK'      => $member['rank'],
                'RANGIMAGE' => $member['rankimage'],
            ));
        }
       
    }
    
    // Load Template
    $template->set_filenames(array(
        'body' => 'members.html',
    ));

    // End Controller
    make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
    page_footer();
?>