<?php 
	session_start();

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require '../vendor/phpmailer/phpmailer/src/Exception.php';
	require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
	require '../vendor/phpmailer/phpmailer/src/SMTP.php';

	require '../vendor/autoload.php';


if(isset($_POST['btn']) && $_POST['btn']=="register")
{
	$error = '';

	$success_message = '';

	if(isset($_POST["btn"]))
	{
	    if(isset($_SESSION['user_data']))
	    {
	        header('location:../ChatRoom.php');
	    }
	    require_once('../model/ChatUser.php');

	    $user_object = new ChatUser;

	    $user_object->setUserName($_POST['user_name']);

	    $user_object->setUserEmail($_POST['user_email']);

	    $user_object->setUserPassword($_POST['user_password']);

	    $user_object->setUserProfile(null);

	    $user_object->setUserStatus('Disabled');

	    $user_object->setUserCreateDate(date('Y-m-d H:i:s'));

	    $user_object->setUserVerCode(md5(uniqid()));

	    $user_data = $user_object->get_user_data_by_email();

	    if(is_array($user_data) && count($user_data) > 0)
	    {
	        $error = 'This Email Already Register';
	    }
	    else
	    {
	        if($user_object->save_data())
	        {
	        	$mail = new PHPMailer(true);
	        	$mail->CharSet = "UTF-8";
	            $mail->SMTPDebug = 1;      
	        	$mail->isSMTP();
	        	$mail->Host = "smtp.gmail.com";
	        	$mail->SMTPAuth = true;
	        	$mail->Username = '3nhankaiser@gmail.com';
	        	$mail->Password = 'Nhan0135792468';
	        	$mail->SMTPSecure = 'ssl';
	        	$mail->Port = 465;
	        	$mail->setFrom = "3nhankaiser@gmail.com";
	        	$mail->addAddress($user_object->getUserEmail());
	        	$mail->isHTML(true);
	        	$mail->Subject = "Đăng ký trang web chat php";
	        	$mail->Body = ' 
	        		<h1>Bạn đã đăng ký web chat php, vui lòng chọn xác thực</h1>
	        		<a href="http://localhost/web_chat_php/verify.php?code='.$user_object->getUserVerCode().'">Xác thực</a>
	        	 ';
	        	$mail->send();
	            $success_message = 'We sent to your mail a message verify, please access mail to verify';
	            header('location:../register-message.php');
	        }
	        else
	        {
	            $error = 'Something went wrong try again';
	        }

	    }

	}
}


if(isset($_POST['btn']) && $_POST['btn']=="update")
{
	if(!isset($_SESSION['user']))
	{
		header('location:login.php');
	}
	else
	{
		if(isset($_POST['btn']))
		{
			require_once('../model/ChatUser.php');
			$user_object = new ChatUser;

			if($_FILES['user_avatar']!="" || $_FILES['user_avatar']['name']!="")
			{
				$user_object->setUserProfile($_FILES['user_avatar']['name']);
				$user_profile = $user_object->upload_image($_FILES['user_avatar']);
				$_SESSION['user']['user_profile'] = $user_profile;
			}

			if($_POST['user_name']!="")
			{
				$user_object->setUserId($_SESSION['user']['user_id']);
				$user_object->setUserName($_POST['user_name']);
				$user_object->setUserEmail($_POST['user_email']);
				$user_object->setUserPassword($_POST['user_password']);
				$user_object->save_update_profile();
				header('location:../profile.php');
			}
			else
			{
				$_SESSION['failed']="Cập nhật thông tin thất bại!";
				header('location:../profile.php');
			}

			

		}
	}
}


if(isset($_POST['btn']) && $_POST['btn']=='login')
{
		require_once('../model/ChatUser.php');

		$user_object = new ChatUser;
		$user_object->setUserEmail($_POST['user_email']);
		$user_data = $user_object->get_user_data_by_email();

		if(is_array($user_data) && count($user_data)>0)
		{
			if($user_data['user_status']=="enable")
			{
				if($user_data['user_password']==$_POST['user_password'])
				{
					$user_object->setUserId($user_data['user_id']);
					$user_object->setUserToken(md5(uniqid())); 
					$user_object->update_login_status();
					$user_data = $user_object->get_user_data_by_email();
					$_SESSION['user'] = $user_data;
					header('location:../index.php');
				}
				else
				{
					$_SESSION['login_error'] = "Wrong password";
					header('location:../login.php');
				}
			}
			else
			{
				$_SESSION['login_error'] = "Unverify email";
				header('location:../login.php');
			}
		}
		else
		{
			$_SESSION['login_error'] = "Email not exist";
			header('location:../login.php');
		}
	}

if(isset($_POST['btn']) && $_POST['btn']=="logout")
{
	if(isset($_SESSION['user']['user_id']))
	{
		require_once('../model/ChatUser.php');
		$user_object = new ChatUser;
		$user_object->update_logout_status();
		unset($_SESSION['user']);
		echo json_encode(['status'=>1]);
		// header('location:../login.php');
	}
}

	
?>