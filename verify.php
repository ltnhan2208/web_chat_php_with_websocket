<?php 
	session_start();
	$error="";
	$success_message="";
	if(isset($_GET['code']))
	{
		require_once('model/ChatUser.php');
		$user_object = new ChatUser;
		$user_object->setUserVerCode($_GET["code"]);
		if($user_object->is_valid_email_verification_code())
		{
			$user_object->setUserStatus("enable");
			if($user_object->enable_user_status())
			{
				$success_message="Congratulations on your successful verify";
			}
			else
			{
				$error="Error!";
			}
		}
	   else
	   {
	   	$errpr = "Error!";
	   }
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Verify code</title>
	<link rel="stylesheet" href="public/style/sign-in-up.css"/>
	<link rel="stylesheet" href="public/lib/fontawesome/css/all.css"/>
	<link rel="stylesheet" href="public/lib/bootstrap/css/bootstrap.css"/>
	<link rel="stylesheet" href="public/lib/bootstrap/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="public/lib/jquery-3.6.0.min.js"/>
</head>
<body id="body">
	<div class="container text-center text-success">
		<br/><br/><br/><br/>
		<h2>
		<?php
		if($success_message!="")
		{
			echo $success_message;
		}
		if($error !="")
		{
			echo $error;
		}
	 ?></h2>
	 <br/>
	 <h4><a href="login.php">Go to login</a></h4>
	</div>
</body>
</html>