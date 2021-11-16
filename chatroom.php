<?php 
	session_start();

	if(!isset($_SESSION['user']))
	{
		header("location:login.php");
	}
	else
	{
		require_once('model/ChatUser.php');
		require_once('model/ChatRoom.php');

		$room_chat = new ChatRoom;
		$data_chat = $room_chat->get_message_chat();

		$user_object = new ChatUser;
		$list_user = $user_object->get_all_user();


	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Chat Room</title>
	<link rel="stylesheet" href="public/style/chatroom.css"/>
	<link rel="stylesheet" href="public/lib/fontawesome/css/all.css"/>
	<link rel="stylesheet" href="public/lib/bootstrap/css/bootstrap.css"/>
	<link rel="stylesheet" href="public/lib/bootstrap/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="public/lib/jquery-3.6.0.min.js"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
</head>
<body>
	<div class="container-fluid">
		<br/>
		<div class="row">
				<div class="col-lg-4 text-center">
				<div>
					<img class="avatar" 
					<?php 
					if($_SESSION['user']['user_profile']!=null)
						{echo "src='public/images/".$_SESSION['user']['user_profile']."'";}
					else{echo "src='public/images/avatar.png'";}
					?>
					/>
				</div>
				<br/>
				<span><?php echo $_SESSION['user']['user_name'] ?>
					<input id="user_id-ip" type="text" value="<?php echo $_SESSION['user']['user_id'] ?>" hidden />
				</span>
				<br/>
				<a class="btn btn-primary" href="profile.php">Edit Profile</a>&emsp;
				<a class="btn btn-dark" href="controller/userController.php?action=logout">Logout</a>
				<br/>
				<br/>
				<div class="col-lg-3 text-center box_list-user">
					<ul class="list_user">
						<li>List friends</li>
						<?php
							foreach($list_user as $value)
							{
								if($value['user_id']!=$_SESSION['user']['user_id'])
								{
									if($value['user_login_status']=='login')
									{
										$color_status = "user_online";
										$text_status = "online";
									}
									else
									{
										$color_status = "user_offline";
										$text_status = "offline";
									}
									 
									echo '<li><span>'.$value['user_name'].'</span><i class="fas fa-circle '.$color_status.'">&nbsp;'.$text_status.'</i></li>';
								}
								}
							
						 ?>
					</ul>
					<br/>
				</div>
			</div>
			<div class="col-lg-8">
				<h3 class="text-center">Box Chat</h3>
				<hr/>
				<div class="row justify-content-start border-danger">
					&nbsp;&nbsp;&nbsp;
					<a href="private-chat.php" class="btn btn-success col-lg-2">Private Chat</a>
					
				</div>
				<div id="chat_area">
					<?php 
						foreach($data_chat as $value)
						{
							if($value['user_id'] == $_SESSION['user']['user_id'])
							{
								echo '<br/><div class="row_chat-me"><div class="message_chat message_me">'
									.$value["room_message"].'<br/>
									<small><i>'.$value["message_create_date"].'</i></small>
								</div>&emsp;</div><br/>';
							}
							else
							{
								echo '<br/><div class="row_chat-user">&emsp;<div class="message_chat message_user">'
									.$value["room_message"].'<br/>
									<small><i>'.$value["message_create_date"].'</i></small>
								</div></div><br/>';
							}
						}
					?>
					<!-- <div class="user_chat">ok la</div>
					<div class="other_chat">la ok</div> -->
				</div>
				<form class="form_chat" method="post">
						<div class="input-group">
						<textarea id="input_message" class="input_message form-control" name="input_message" placeholder="Type message here" required></textarea>
						<button class="btn btn-primary" type="submit" name="send" id="send"><i class="fa fa-paper-plane"></i></button>
						</div>
					</form>
			</div>
		
		</div>
	</div>










	


	<script type="text/javascript">
		 $('#chat_area').scrollTop($('#chat_area')[0].scrollHeight);
		$(document).ready(function(){
			var conn = new WebSocket('ws://localhost:8080');
			conn.onopen = function(e) {
			    console.log("Connection established!");
			};

			conn.onmessage = function(e) {
			    console.log(e.data);

			    var data = JSON.parse(e.data);

			    // var row_class = 'row justify-content-start';

			    // var background_class = 'text-dark alert-success';
			    var row_class="";
			    var background_class="";

			    if(data.from == "Me")
			    {
			    	 var row_class = 'row_chat-me';
			    	 var message_chat = 'message_chat message_me';
			    	 var space_me ="&emsp;";
			    	 var space_user ="";
			    }
			    else
			    {
			    	 var row_class = 'row_chat-user';
			    	 var message_chat = 'message_chat message_user';
			    	 var space_me ="";
			    	 var space_user ="&emsp;";
			    }

			    var html_data = '<br/><div class="'+row_class+'">'+space_user+'<div class="'+message_chat+'">'+data.msg+'<br/><small><i>'+data.datetime+'</i></small></div>'+space_me+'</div><br/>';

			    $('#chat_area').append(html_data);
			    $('#input_message').val(' ');

			    $('#chat_area').scrollTop($('#chat_area')[0].scrollHeight);
			};

			$('.form_chat').on('submit',function(event){
				event.preventDefault();
				var user_id = $('#user_id-ip').val();
				var message = $('#input_message').val();
				var data = {
					userId : user_id,
					msg : message
				};
				conn.send(JSON.stringify(data));
				$('#input_message').val(' ');
				$('#chat_area').scrollTop($('#chat_area')[0].scrollHeight);
			});
		});
	</script>
</body>
</html>