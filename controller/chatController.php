<?php
session_start();
	if(isset($_POST['func_chat']) && $_POST['func_chat']=='load_chat')
	{
		require_once('../model/PrivateChat.php');
		$privateChat = new PrivateChat;
		$privateChat->setFromId($_POST['from_user_id']);
		$privateChat->setToId($_POST['to_user_id']);
		// if(isset($_SESSION['dt']))
		// {
		// 	unset($_SESSION['dt']);
		// 	$_SESSION['dt'] = json_encode($privateChat->get_all_chat());
		// }
		// else
		// {
		// 	$_SESSION['dt'] = json_encode($privateChat->get_all_chat());
		// }
		$privateChat->setStatus('No');
		$privateChat->change_chat_status();
		echo json_encode($privateChat->get_all_chat());	
	}
 ?>