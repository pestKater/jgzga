<?php
	/**
	* @ignore
	*/
	define('IN_PHPBB', true);
    $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();
	
	/**
	* DATENBANKFOO UM AN DATEN ZU KOMMEN
	* Sprich: $title und $content
	*/
	$get 		= $request->variable('page', 0);
	$title 		= 'Da lief was schief';
	$content 	= 'Diese Seite ist nicht verfügbar';
	$admin 		= false;
	
	if ($get > 0) {
		
		$sql = 'SELECT title, content FROM phpbb_customsite WHERE id = ' . $get;
		$result = $db->sql_query($sql);
		
		$row = $db->sql_fetchrow($result);

		if($row != false) {
			$title = $row['title'];
			$content = $row['content'];
		}
		              
		$db->sql_freeresult($result);
		

		/**
		* Ist User = Admin?
		*/
		$userId 	= $user->data['user_id'];
		$groupId	= 5;
		
		$sql = 'SELECT count(*) AS count FROM ' . USER_GROUP_TABLE . ' WHERE user_id = ' . $userId . ' AND group_id = ' . $groupId;
		$result = $db->sql_query($sql);

		$row = $db->sql_fetchrow($result);
		
		if($row['count'] == 1) {
			$admin = true;
		} 
		$db->sql_freeresult($result);
	} 
	
	// Seitentitel in Tab/Window
	page_header($title);
	
	$template->assign_vars(array(
		'PAGETITLE'		=> $title,
		'PAGECONTENT'	=> $content,
		'ISADMIN'		=> $admin,
		'PAGEID'		=> $get,
	));

	// Set the filename of the template you want to use for this file.
	$template->set_filenames(array(
		'body' => 'custom.html',
	));
	

	// Completing the script and displaying the page.
	page_footer();

?>