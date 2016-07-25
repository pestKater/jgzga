<?php

global $db;

/**
 * Prüft ob ein Nutzer ein Bild editieren darf
 * 
 * @param type $userId
 * @param type $pictureId
 * @return boolean
 */
function canUserEdit($userId, $pictureId) {
    global $db;
    
    // Checken ob User Mod oder Admin ist
    $groups = array(
        4,
        5,
    );
    $sql = "SELECT count(*) AS count FROM " . USER_GROUP_TABLE . " WHERE user_id = " . $userId . " AND " . $db->sql_in_set('group_id', $groups);
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    
    // Wenn ja: Darf editieren
    if($row['count'] > 0) {
        return true;
    }
    
    // Prüfen ob das Bild vom User eingestellt wurde
    $sql = "SELECT count(*) AS count FROM phpbb_gallery WHERE id = " . $pictureId . " AND author = " . $userId;
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    
    // Wenn ja: Darf editieren
    if($row['count'] > 0) {
        return true;
    }
    
    // Ansonsten: Nein
    return false;
}

/**
 * Prüft ob ein Nutzer ein Bild oder Ordner anlegen darf
 * 
 * @global type $db
 * @param type $userId
 * @return boolean
 */
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

/**
 * Zählt die Bilder in der Datenbank. Optional kann die FolderID übergeben werden
 * 
 * @global type $db
 * @param type $folderId
 * @return type
 */
function countPictures($folderId = NULL) {
    global $db;
    $sql = 'SELECT count(*) AS count FROM phpbb_gallery';
    
    if(!is_null($folderId)) {
        $sql .= ' WHERE in_group = ' . $folderId;
    }
    
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    
    $db->sql_freeresult($result);
    
    return $row['count'];
}

/**
 * Zählt die Ordner in der Datenbank.
 * 
 * @global type $db
 * @return type
 */
function countFolders() {
    global $db;
    $sql = 'SELECT count(*) AS count FROM phpbb_gallery_folders';
    
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    
    $db->sql_freeresult($result);
    
    return $row['count'];
}

/**
 * Holt alle Ordner mit einem definiertem Offset und Limit
 * 
 * @global type $db
 * @param type $limit
 * @param type $offset
 * @return type
 */
function getAllFolders($limit = NULL, $offset = NULL) {
    global $db;
    $sql = 'SELECT id, foldername FROM phpbb_gallery_folders ORDER BY id DESC';
    
    if(!is_null($offset)) {
        $sql .= ' LIMIT ' . $limit;
    }
    
    if(!is_null($offset)) {
        $sql .= ' OFFSET ' . $offset;
    }
    
    $result = $db->sql_query($sql);

    while($row = $db->sql_fetchrow($result)) {
        $data[$row['id']]['id'] = $row['id'];
        $data[$row['id']]['name'] = $row['foldername'];
    }
    
    $db->sql_freeresult($result);
    
    return $data;
}

/**
 * Holt alle Bilder aus der Datenbank. 
 * 
 * @global type $db
 * @param type $folderId
 * @param type $limit
 * @param type $offset
 * @return type
 */
function getAllImages($folderId = NULL, $limit = NULL, $offset = NULL) {
    global $db;
    
    $sql = 'SELECT id, name FROM phpbb_gallery';
    
    if(!is_null($folderId)) {
        $sql .= ' WHERE in_group = ' . $folderId;
    }
    
    $sql .= ' ORDER BY id DESC';
    
    if(!is_null($limit)) {
        $sql .= ' LIMIT ' . $limit;
    }
    
    if(!is_null($offset)) {
        $sql .= ' OFFSET ' . $offset;
    }
    
    $result = $db->sql_query($sql);

    while($row = $db->sql_fetchrow($result)) {
        $data[$row['id']]['id'] = $row['id'];
        $data[$row['id']]['name'] = $row['name'];
    }
    
    $db->sql_freeresult($result);
    
    return $data;
}

/**
 * Holt die Details eines Bildes aus der Datenbank
 * 
 * @global type $db
 * @param type $pictureId
 * @return type
 */
function getPictureData($pictureId) {
    global $db;
    
    $sql = 'SELECT id, name, date, descr, author, in_group FROM phpbb_gallery WHERE id= ' . $pictureId;
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    
    return $row;
}

function getFolderData($folderId) {
    global $db;
    
    $sql = 'SELECT id, foldername FROM phpbb_gallery_folders WHERE id= ' . $folderId;
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    
    return $row;
}

/**
 * Gibt den Usernamen und den Avatar eines Users aus
 * 
 * @global type $db
 * @param type $userId
 * @return type
 */
function getUserData($userId) {
    global $db;
    
    $sql = 'SELECT username, user_avatar, user_rank FROM ' . USERS_TABLE . " WHERE user_id = " . $userId;
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    
    return $row;
}

/**
 * Gibt den Rangtitel aus
 * 
 * @global type $db
 * @param type $rankId
 * @return type
 */
function getRankName($rankId) {
    global $db;
    
    $sql = 'SELECT rank_title FROM ' . RANKS_TABLE . " WHERE rank_id = " . $rankId;
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    
    return $row['rank_title'];
}

function getFolderByName($folderName) {
    global $db;
    
    $sql = 'SELECT id FROM phpbb_gallery_folders WHERE foldername = ' . $folderName;
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    
    return $row['id'];
}