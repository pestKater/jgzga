<?php
$intern = array(
        1,
        3,
        4,
    );

function canUserAdd($userId) {
    global $db;
    
    $groups = array(
        5,
        15,
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

function getAllEventCategories() {
    global $db;
    $data = array();
    
    $sql = "SELECT id, name FROM phpbb_events_categories ORDER BY id ASC";
    $result = $db->sql_query($sql);
    
    while($row = $db->sql_fetchrow($result)) {
        $data[$row['id']]['id'] = $row['id'];
        $data[$row['id']]['name'] = $row['name'];
    }
    
    return $data;
}

function getFeaturedArticles($isMember) {
    global $db;
    global $intern;
    $data = array();
    
    if($isMember) {
        $sql = "SELECT id, title, content FROM phpbb_news_articles ORDER BY id DESC LIMIT 1";
    } else {
        $sql = "SELECT id, title, content FROM phpbb_news_articles WHERE " . $db->sql_in_set('eventCategory', $intern, true) . " ORDER BY id DESC LIMIT 1";
    }
    $result = $db->sql_query($sql);
    
    while($row = $db->sql_fetchrow($result)) {
        $data[$row['id']]['id'] = $row['id'];
        $data[$row['id']]['title'] = $row['title'];
        $data[$row['id']]['content'] = $row['content'];
    }
    return $data;
}

function getOverviewArticles($isMember) {
    global $db;
    global $intern;
    $data = array();
    
    if($isMember) {
        $sql = "SELECT id, title, content, category FROM phpbb_news_articles ORDER BY id DESC LIMIT 5 OFFSET 1";
    } else {
        $sql = "SELECT id, title, content, category FROM phpbb_news_articles WHERE " . $db->sql_in_set('eventCategory', $intern, true) . " OR eventCategory IS NULL ORDER BY id DESC LIMIT 5 OFFSET 1";
    }
    var_dump($sql);
    die;
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
    $sql = "SELECT id, title, content, category, author, postdate, eventCategory, dueDate FROM phpbb_news_articles WHERE id = " . $id;
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

function getAllArticlesById($articleId, $isMember) {
    global $db;
    $data = array();
    
    $sql = 'SELECT id, title, content, eventCategory FROM phpbb_news_articles WHERE category = ' . $articleId . ' ORDER BY id DESC';
    $result = $db->sql_query($sql);
    
    while($row = $db->sql_fetchrow($result)) {
        if($row['eventCategory'] == 1 || $row['eventCategory'] == 3 || $row['eventCategory'] == 4) {
            if($ismember) {
                $data[$row['id']]['id'] = $row['id'];
                $data[$row['id']]['title'] = $row['title'];
                $data[$row['id']]['content'] = $row['content'];
            }
        } else {
            $data[$row['id']]['id'] = $row['id'];
            $data[$row['id']]['title'] = $row['title'];
            $data[$row['id']]['content'] = $row['content'];
        }
    }
    
    return $data;
}

function getAllArticles($isMember) {
    global $db;
    global $intern;
    $data = array();
    
    if($isMember) {
        $sql = 'SELECT id, title, content, category FROM phpbb_news_articles ORDER BY id DESC';
    } else {
        $sql = 'SELECT id, title, content, category FROM phpbb_news_articles WHERE ' . $db->sql_in_set('eventCategory', $intern, true) . ' OR eventCategory IS NULL ORDER BY id DESC';
    }
    $result = $db->sql_query($sql);
    
    while($row = $db->sql_fetchrow($result)) {
        $data[$row['id']]['id'] = $row['id'];
        $data[$row['id']]['title'] = $row['title'];
        $data[$row['id']]['content'] = $row['content'];
        $data[$row['id']]['category'] = $row['category'];
    }
    
    return $data;
}

function getShoutsOverview() {
    global $db;
    $data = array();
    
    $sql = 'SELECT id, author, date, comment FROM phpbb_shoutbox ORDER BY id DESC LIMIT 5';
    $result = $db->sql_query($sql);
    
    while($row = $db->sql_fetchrow($result)) {
        $data[$row['id']]['id'] = $row['id'];
        $data[$row['id']]['author'] = $row['author'];
        $data[$row['id']]['date'] = $row['date'];
        $data[$row['id']]['comment'] = $row['comment'];
    }
    
    return $data;
}

function getAllShouts() {
    global $db;
    $data = array();
    
    $sql = 'SELECT id, author, date, comment FROM phpbb_shoutbox ORDER BY id DESC';
    $result = $db->sql_query($sql);
    
    while($row = $db->sql_fetchrow($result)) {
        $data[$row['id']]['id'] = $row['id'];
        $data[$row['id']]['author'] = $row['author'];
        $data[$row['id']]['date'] = $row['date'];
        $data[$row['id']]['comment'] = $row['comment'];
    }
    
    return $data;
}

function isMember($userId) {
    global $db;
    global $intern;
    
    $sql = 'SELECT count(*) AS count FROM '.USER_GROUP_TABLE.' WHERE user_id = ' . $userId . ' AND group_id = 8';
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    
    if($row['count'] == 1) {
        return true;
    }
    
    return false;
    
}

function getEventById($id) {
    global $db;
    $sql = "SELECT id, title, content, eventCategory, author, postdate, dueDate FROM phpbb_news_articles WHERE id = " . $id;
    $result = $db->sql_query($sql);
    
    return $db->sql_fetchrow($result);
}

function getUpcomingEvents($isMember) {
    global $db;
    global $intern;
    $data = array();
    
    if($isMember) {
        $sql = 'SELECT id, title, author, content, category, eventCategory, dueDate FROM phpbb_news_articles WHERE category = 1 AND dueDate >= NOW() ORDER BY id ASC LIMIT 3';
    } else {
        $sql = 'SELECT id, title, author, content, category, eventCategory, dueDate FROM phpbb_news_articles WHERE category = 1 AND ' . $db->sql_in_set('eventCategory', $intern, true) . ' AND dueDate >= NOW() ORDER BY id ASC LIMIT 3';
    }
    $result = $db->sql_query($sql);
    
    while($row = $db->sql_fetchrow($result)) {
        if($row['eventCategory'] == 1 || $row['eventCategory'] == 3 || $row['eventCategory'] == 4) {
            if($isMember) {
                $data[$row['id']]['id'] = $row['id'];
                $data[$row['id']]['author'] = $row['author'];
                $data[$row['id']]['title'] = $row['title'];
                $data[$row['id']]['content'] = $row['content'];
                $data[$row['id']]['eventCategory'] = $row['eventCategory'];
                $data[$row['id']]['dueDate'] = $row['dueDate'];
            }
        } else {
            $data[$row['id']]['id'] = $row['id'];
            $data[$row['id']]['author'] = $row['author'];
            $data[$row['id']]['title'] = $row['title'];
            $data[$row['id']]['content'] = $row['content'];
            $data[$row['id']]['eventCategory'] = $row['eventCategory'];
            $data[$row['id']]['dueDate'] = $row['dueDate'];
        }
    }
    
    return $data;
}

function getEventCategory($id) {
    global $db;
    $sql = "SELECT name FROM phpbb_events_categories WHERE id = " . $id;
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrow($result);
    
    return $result['name'];
}

function getAllUpcomingEvents($isMember) {
    global $db;
    global $intern;
    $data = array();
    
    if($isMember) {
        $sql = 'SELECT id, title, author, content, category, eventCategory, dueDate FROM phpbb_news_articles WHERE category = 1 AND dueDate >= NOW() ORDER BY id ASC';
    } else {
        $sql = 'SELECT id, title, author, content, category, eventCategory, dueDate FROM phpbb_news_articles WHERE category = 1 AND ' . $db->sql_in_set('eventCategory', $intern, true) . ' AND dueDate >= NOW() ORDER BY id ASC';
    }
    $result = $db->sql_query($sql);
    
    while($row = $db->sql_fetchrow($result)) {
        
        $data[$row['id']]['id'] = $row['id'];
        $data[$row['id']]['author'] = $row['author'];
        $data[$row['id']]['title'] = $row['title'];
        $data[$row['id']]['content'] = $row['content'];
        $data[$row['id']]['eventCategory'] = $row['eventCategory'];
        $data[$row['id']]['dueDate'] = $row['dueDate'];
    }
    
    return $data;
}

function getAllPastEvents($isMember) {
    global $db;
    global $intern;
    $data = array();

    if($isMember) {
        $sql = 'SELECT id, title, author, content, category, eventCategory, dueDate FROM phpbb_news_articles WHERE category = 1 AND dueDate <= NOW() ORDER BY id ASC';
    } else {
        $sql = 'SELECT id, title, author, content, category, eventCategory, dueDate FROM phpbb_news_articles WHERE category = 1 AND ' . $db->sql_in_set('eventCategory', $intern, true) . ' AND dueDate <= NOW() ORDER BY id ASC';
    }
    $result = $db->sql_query($sql);
    
    while($row = $db->sql_fetchrow($result)) {
        if($row['eventCategory'] == 1 || $row['eventCategory'] == 3 || $row['eventCategory'] == 4) {
            if($isMember) {
                $data[$row['id']]['id'] = $row['id'];
                $data[$row['id']]['author'] = $row['author'];
                $data[$row['id']]['title'] = $row['title'];
                $data[$row['id']]['content'] = $row['content'];
                $data[$row['id']]['eventCategory'] = $row['eventCategory'];
                $data[$row['id']]['dueDate'] = $row['dueDate'];
            }
        } else {
            $data[$row['id']]['id'] = $row['id'];
            $data[$row['id']]['author'] = $row['author'];
            $data[$row['id']]['title'] = $row['title'];
            $data[$row['id']]['content'] = $row['content'];
            $data[$row['id']]['eventCategory'] = $row['eventCategory'];
            $data[$row['id']]['dueDate'] = $row['dueDate'];
        }
    }
    
    return $data;
}

function makeLinks($str, $target='_blank')
{
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