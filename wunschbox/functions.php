<?php

global $db;

function isMember($userId) {
    global $db;
    
    $sql = 'SELECT count(*) AS count FROM '.USER_GROUP_TABLE.' WHERE user_id = ' . $userId . ' AND group_id = 8';
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    
    if($row['count'] == 1) {
        return true;
    }
    
    return false;
}

