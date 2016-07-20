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
    $sql = 'SELECT group_id, group_name, group_desc, group_avatar FROM ' . GROUPS_TABLE . ' WHERE ' . $db->sql_in_set('group_id', $group_ids);
    $result = $db->sql_query($sql);
    
    // Write Data for each Group
    while ($row = $db->sql_fetchrow($result)) {
        $data[$row['group_id']]['id'] = $row['group_id'];
        $data[$row['group_id']]['name'] = $row['group_name'];
        $data[$row['group_id']]['desc'] = $row['group_desc'];
        $data[$row['group_id']]['avatar'] = $row['group_avatar'];
    }
    $db->sql_freeresult($result);
    
    foreach($data as $group) {
        
        $template->assign_block_vars('unit', array(
            'NAME'          => $group['name'],
            'DESCRIPTION'   => $group['desc'],
            'LOGO'        => $group['avatar']
        ));
        
        $sql_arr = array(
            'SELECT'    => 'u.user_id, u.username, u.user_avatar, r.rank_title, r.rank_image',
            'FROM'      => array(
                USER_GROUP_TABLE => 'g',
                USERS_TABLE => 'u',
                RANKS_TABLE => 'r'
            ),
            'WHERE'     => 'g.group_id = '. $group['id'] . ' AND u.user_id = g.user_id AND u.user_rank = r.rank_id ORDER BY r.rank_image DESC',
        );
        $sql = $db->sql_build_query('SELECT', $sql_arr);
        $result = $db->sql_query($sql);
        
        while($row = $db->sql_fetchrow($result)) {
            $data[$group['id']]['users'][$row['user_id']] = array(
                'id'        => $row['user_id'],
                'name'      => $row['username'],
                'avatar'    => $row['user_avatar'],
                'rank'      => $row['rank_title'],
                'rankimage' => $row['rank_image']
            );
            $template->assign_block_vars('unit.member', array(
                'USERNAME'  => $row['username'],
                'AVATAR'    => $row['user_avatar'],
                'USERID'    => $row['user_id'],
                'RANK'      => $row['rank_title'],
                'RANKIMAGE' => $row['rank_image'],
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