<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Page Regist</title>
	<link rel="stylesheet" href="public/style/sign-in-up.css"/>
	<link rel="stylesheet" href="public/lib/fontawesome/css/all.css"/>
	<link rel="stylesheet" href="public/lib/bootstrap/css/bootstrap.css"/>
	<link rel="stylesheet" href="public/lib/bootstrap/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="public/lib/jquery-3.6.0.min.js"/>
</head>
<body id="body">
	<div class="container-fluid">
		<div class="box_svg-top">
		<div class="box_svg-child svg_top-left">
			<image src="public/images/images_svg/topleft.svg" alt="Loading..."/>
			<image src="public/images/images_svg/mail.svg" alt="Loading..."/>
			&emsp;&emsp;&emsp;&emsp;&emsp;
		</div>
		<div class="box_svg-child svg_top-right">
			&emsp;&emsp;&emsp;&emsp;&emsp;
			<image src="public/images/images_svg/line.svg" alt="Loading..."/>
			<image src="public/images/images_svg/topright.svg" alt="Loading..."/>
		</div>
	</div>

	<div class="box_svg-mid">
		<div class="box_svg-child svg_mid-left">
			<div>
			<image src="public/images/images_svg/bar.svg" alt="Loading..."/>
			</div>
			&emsp;&emsp;&emsp;&emsp;
		</div>
		<div class="box_svg-child svg_mid-right">
			&emsp;&emsp;&emsp;&emsp;
			<div>
			<image src="public/images/images_svg/picture.svg" alt="Loading..."/>
			</div>
		</div>
	</div>
	<br/>
	<div class="box_svg-bot">
		<div class="box_svg-child svg_bot-left">
			<image src="public/images/images_svg/botleft.svg" alt="Loading..."/>
			<image src="public/images/images_svg/location.svg" alt="Loading..."/>
			&emsp;&emsp;&emsp;&emsp;&emsp;
		</div>
		<div class="box_svg-child svg_bot-right">
			&emsp;&emsp;&emsp;&emsp;&emsp;
			<image src="public/images/images_svg/calender.svg" alt="Loading..."/>
			<image src="public/images/images_svg/botright.svg" alt="Loading..."/>
		</div>
	</div>
		
			<?php
				if($error != "")
				{
					echo "$error";
				}
				if($success_message!="")
				{
					echo "$success_message";
				}
			 ?>
			 <div class="box_form row justify-content-around">
			<h1>REGISTER</h1>
			<br/><br/>
				<form action="controller/userController.php" method="POST" id="form_in-up">
				<input class="form_ip" type="text" name="user_name" placeholder="Enter your name..."/>
				<br/><br/>
				<input class="form_ip" type="email" name="user_email" placeholder="Enter your email..."/>
				<br/><br/>
				<input class="form_ip" type="password" name="user_password" placeholder="Enter your password..."/>
				<br/><br/>
				<button type="submit" name="btn" value="register" class="btn_in-up btn btn-primary">Xác nhận</button>
				</form>
			</div>
	
	</div>
</body>
</html>
