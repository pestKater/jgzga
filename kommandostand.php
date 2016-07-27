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
	$title 		= 'Kommandostand';
	$content 	= 'Diese Seite ist nicht verfügbar';
	$admin 		= false;
	
	
	
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
		'body' => 'kommandostand.html',
	));
	

	// Completing the script and displaying the page.
	page_footer();

?>