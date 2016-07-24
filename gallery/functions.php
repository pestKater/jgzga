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

