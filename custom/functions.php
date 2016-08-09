<?php

function isUserAdmin($userId) {
    global $db;
    
    $sql = 'SELECT count(*) AS count FROM ' . USER_GROUP_TABLE . ' WHERE user_id = ' . $userId . ' AND group_id = 5';
    $result = $db->sql_query($sql);

    $row = $db->sql_fetchrow($result);

    if($row['count'] == 1) {
            return true;
    } 
    $db->sql_freeresult($result);
    
    return false;
}

function getCustomsite($id) {
        global $db;
        
        $sql = 'SELECT title, content FROM phpbb_customsite WHERE id = ' . $id;
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);

        $data['title']      = $row['title'];
        $data['content']    = $row['content'];

        $db->sql_freeresult($result);
        
        return $data;
}

function makeLinks($str, $target='_blank') {
    if ($target)
    {
        $target = ' target="'.$target.'"';
    }
    else
    {
        $target = '';
    }
    // find and replace link
    $str = preg_replace('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', '<a href="$1" '.$target.'>$1</a>', $str);
    // add "http://" if not set
    $str = preg_replace('/<a\s[^>]*href\s*=\s*"((?!https?:\/\/)[^"]*)"[^>]*>/i', '<a href="http://$1" '.$target.'>', $str);
    
    return $str;
}