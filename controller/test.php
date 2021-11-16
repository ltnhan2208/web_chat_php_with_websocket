<?php 
	require dirname (__DIR__) . '/model/PrivateChat.php';

	$ok = new PrivateChat;
	echo $ok->getFromId();
?>