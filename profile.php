<?php 
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Chat Room</title>
	<link rel="stylesheet" href="public/style/profile.css"/>
	<link rel="stylesheet" href="public/lib/fontawesome/css/all.css"/>
	<link rel="stylesheet" href="public/lib/bootstrap/css/bootstrap.css"/>
	<link rel="stylesheet" href="public/lib/bootstrap/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="public/lib/jquery-3.6.0.min.js"/>
</head>
<body>
	<br/><br/>
	<div class="container">
		<h2 class="text-center">Web App Chat</h2>
		<hr/>
		<br/>
	<form action="controller/userController.php" method="POST" id="form_edit" enctype="multipart/form-data">
		<div class="edit_title">
			<span>Profile</span>
			<a href="chatroom.php">Go to chat</a>
		</div>
		<br/>
		<div class="form_label">Tên</div>
		<input class="form_ip" type="text" name="user_name" value="<?php echo $_SESSION['user']['user_name'] ?>"/>
		<br/><br/>
		<div class="form_label">Email</div>
		<input class="form_ip form_ip-email" type="email" name="user_email" readonly value="<?php echo $_SESSION['user']['user_email'] ?>"/>
		<br/><br/>
		<div class="form_label">Mật khẩu</div>
		<input class="form_ip" type="password" name="user_password" value="<?php echo $_SESSION['user']['user_password'] ?>"/>
		<br/><br/>
		<div class="form_label">Chọn ảnh</div>
		<div class="box__avatar">
			<img  <?php ?>src="public/images/"/>
		</div>
		<input type="file" name="user_avatar"/>
		<br/><br/>
		<button type="submit" name="btn" value="update" class="btn_update">UPDATE</button>
		<br/><br/>
	</form>
	<br/>
	</div>


<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
	if($_SESSION['access']!="" || $_SESSION['access']!=NULL)
	{
	?>
		<script>
		swal("Cập nhật thành công!");
		</script>
	<?php	
	unset($_SESSION['access']);
	}
?>
<?php
	if($_SESSION['failed']!="" || $_SESSION['failed']!=NULL)
	{
	?>
		<script>
		swal("Cập nhật thất bại!");
		</script>
	<?php	
	unset($_SESSION['failed']);
	}
?>
</body>
</html>