<?php
/*******************************************************************************
 * Includes
 ******************************************************************************/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path. 'common.' . $phpEx);
require_once('../functions.php');

/*******************************************************************************
 * Errorhandling
 ******************************************************************************/
if($request->is_set_post('submit')) {
   // do nothing
} else {
   exit;
}

/**
 * DATEN
 */
$nick = $request->variable('nick', ''); //username
$mail = $request->variable('mail', ''); // email
$userid = $request->variable('userid', '');
$p1 = $request->variable('pass1', '');
$p2 = $request->variable('pass2', '');
$enabled = 1;
$protected = 0;
$access = 25;
$cookie = auth_generate_unique_cookie_string();

// Abfangen falls die Passwörter nicht übereinstimmen
if($p1 == $p2) {
    $password = md5($p1);
} else {
    echo 'Das Passwort stimmt nicht überein';
    exit;
}

if(!isMember($userid)) {
    echo 'Du bist nicht berechtigt diese Aktion auszuführen';
    exit;
}

// In Datenbank schreiben
$query = "INSERT INTO mantis_user_table (username, email, password, enabled, protected, access_level, cookie_string, date_created) VALUES ('$nick', '$mail', '$password', '$enabled', '$protected', '$access', '$cookie', UNIX_TIMESTAMP())";
$connection = mysql_connect('localhost', 'bugs', 'ffets2016!bugs') or die("Datenbank nicht verfügbar!");
mysql_select_db('bugs') or die("Tabelle nicht verfügbar!");

$result = mysql_query($query) OR die("Error: $query <br>".mysql_error());

mysql_close($connection);

header('Location: http://89.163.134.245/bugs/');
?>