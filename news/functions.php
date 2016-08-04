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

function getAllCategories() {
    global $db;
    $data = array();
    
    $sql = "SELECT id, name FROM phpbb_news_categories ORDER BY id ASC";
    $result = $db->sql_query($sql);
    
    while($row = $db->sql_fetchrow($result)) {
        if($row['name'] != 'Event') {
            $data[$row['id']]['id'] = $row['id'];
            $data[$row['id']]['name'] = $row['name'];
        }
    }
    
    return $data;
}

function getFeaturedArticles() {
    global $db;
    $data = array();
    
    $sql = "SELECT id, title, content FROM phpbb_news_articles ORDER BY id DESC LIMIT 1";
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
    
    $sql = "SELECT id, title, content, category FROM phpbb_news_articles ORDER BY id DESC LIMIT 5 OFFSET 1";
    $result = $db->sql_query($sql);
    
    while($row = $db->sql_fetchrow($result)){
        $data[$row['id']]['id'] = $row['id'];
        $data[$row['id']]['title'] = $row['title'];
        $data[$row['id']]['content'] = $row['content'];
        $data[$row['id']]['category'] = $row['category'];
    }
    
    return $data;
}

function getArticleById($id) {
    global $db;
    $sql = "SELECT id, title, content, category, author, postdate FROM phpbb_news_articles WHERE id = " . $id;
    $result = $db->sql_query($sql);
    
    return $db->sql_fetchrow($result);
}

function getCategoryName($id) {
    global $db;
    $sql = "SELECT name FROM phpbb_news_categories WHERE id = " . $id;
    $result = $db->sql_query($sql);
    $return =  $db->sql_fetchrow($result);
    
    return $return['name'];
}

function getUserData($userId) {
    global $db;
    
    $sql = 'SELECT username, user_avatar, user_rank FROM ' . USERS_TABLE . " WHERE user_id = " . $userId;
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    
    return $row;
}

function getRankName($rankId) {
    global $db;
    
    $sql = 'SELECT rank_title FROM ' . RANKS_TABLE . " WHERE rank_id = " . $rankId;
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    
    return $row['rank_title'];
}