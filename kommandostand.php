<?php
	/**
	* @ignore
	*/
	define('IN_PHPBB', true);
    $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);
	
	include ('kommandostand/functions.php');
	require_once("serverstatus/config.inc.php");
	require_once("serverstatus/gameq/src/GameQ/Autoloader.php");
	
    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();
	
	$userId = $user->data['user_id'];
	$isMember = isUserMember($userId);
	$allServer = getAllServer();
	$title 		= 'Kommandostand';
	
	// Aktuellen Serverstatus holen
	$gq = \GameQ\GameQ::factory();
	$gq->addServers($servers);
	$gq->setOption('timeout', 3);
	$serverstatus = $gq->process();
	
	// Schleife über alle Server
	forEach ($allServer As $Server) {
		$allModsets = getAllModsets();
		$isOnline = false;
		
		forEach($serverstatus as $singleServer) {
			if($singleServer['port'] == $Server['ServerPort']) {
				$isOnline = $singleServer['gq_online'];
			}
		}
		
		$template->assign_block_vars ('server', array(
			'ID' => $Server['ServerId'],
			'NAME' => $Server['ServerName'],
			'IS_ONLINE' => $isOnline,
		));
		
		forEach ($allModsets As $modset) {
			$template->assign_block_vars ('server.modset', array(
				'ID' => $modset['ModsetId'],
				'NAME' => $modset['ModsetName'],
			));
		}
	}

	page_header($title);
	$template->assign_vars(array(
		'IS_MEMBER'		=> $isMember,
	));
	$template->set_filenames(array(
		'body' => 'kommandostand.html',
	));
	
	page_footer();

?>