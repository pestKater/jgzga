<?php
function canUserAdd($userId) {
    global $db;
    
    $groups = array(
        8,
    );
    $sql = "SELECT count(*) AS count FROM " . USER_GROUP_TABLE . " WHERE user_id = " . $userId . " AND " . $db->sql_in_set('group_id', $groups);
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    
    if($row['count'] > 0) {
        return true;
    }
    
    return false;
}

function getFeaturedArticles() {
    global $db;
    $data = array();
    
    $sql = "SELECT id, title, content FROM phpbb_news_articles ORDER BY id DESC LIMIT 3";
    $result = $db->sql_query($sql);
    
    while($row = $db->sql_fetchrow($result)) {
        $data[$row['id']]['id'] = $row['id'];
        $data[$row['id']]['title'] = $row['title'];
        $data[$row['id']]['content'] = $row['content'];
    }
    
    return $data;
}

function getOverviewArticles() {
    global $db;
    $data = array();
    
    $sql = "SELECT id, title, content FROM phpbb_news_articles ORDER BY id DESC LIMIT 5 OFFSET 4";
    $result = $db->sql_query($sql);
    
    while($row = $db->sql_fetchrow($result)){
        $data[$row['id']]['id'] = $row['id'];
        $data[$row['id']]['title'] = $row['title'];
        $data[$row['id']]['content'] = $row['content'];
    }
    
    return $data;
}
