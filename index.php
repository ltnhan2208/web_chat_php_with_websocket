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

		$token = $_SESSION['user']['user_token'];
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
					if($_SESSION['user']['user_profile']!=null){
						echo "src='public/images/".$_SESSION['user']['user_profile']."'";
					}
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
				<!-- <a class="btn btn-dark" href="controller/userController.php?action=logout">Logout</a> -->
				<input id="logout" type="button" class="btn btn-dark" name="logout" value="logout"/>
				<br/>
				<br/>
				
				<div class="col-lg-3 text-center box_list-user">
					<input hidden id="box_chat_active" value="No"/>
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
									 
									echo '<li class="select_user text-dark" data-userid="'.$value['user_id'].'"><span id="receiver_id_'.$value['user_id'].'">'.$value['user_name'].'<span id="userid'.$value['user_id'].'"></span></span><span id="userid'.$value['user_id'].'_status"><i class="fas fa-circle '.$color_status.'">&nbsp;'.$text_status.'</i></span></li>';
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
				<div id="chat_box">
				</div>
				</div>



			</div>
	</div>










	


	<script type="text/javascript">
		 
		$(document).ready(function(){
			var receiver_id="";
			var conn = new WebSocket('ws://localhost:8080?token=<?php echo $token;?>');
			conn.onopen = function(e) {
			    console.log("Connection established!");
			};

			conn.onmessage = function(e) {
				var data = JSON.parse(e.data);

			    if(data.status_type == "Online")
			    {
			    	$('#userid'+data.userId+'_status').html('<i class="fa fa-circle text-success"></i>');
			    }
			    else if(data.status_type == "Offline")
			    {
			    	$('#userid'+data.userId+'_status').html('<i class="fa fa-circle text-danger"></i>');
			    }
			    else
				{
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
			    if(receiver_id == data.userId || data.from == "Me")
			    {
			    	var html_data = '<br/><div class="'+row_class+'">'+space_user+'<div class="'+message_chat+'">'+data.msg+'<br/><small><i>'+data.datetime+'</i></small></div>'+space_me+'</div><br/>';

			    	$('#chat_area').append(html_data);
					$('#input_message').val(' ');

					$('#chat_area').scrollTop($('#chat_area')[0].scrollHeight);
			    }
			    else
			    {
			    	var count_chat = $('#userid'+data.userId).text();
			    	if(count_chat=='')
			    	{
			    		count_chat=0;
			    	}
			    	count_chat++;
			    	$('#userid'+data.userId).html('<span class="text-danger">'+count_chat+'</span>');
			    }
				}
			};

			conn.onclose = function(e) {

			};

			function make_chat_area(to_user_id,user_name)
			{
				var html = `
					<div class="card">
						<div class="card-header">
						<div class="row">
							<div class="col col-sm-6">
							<b>Chat with<span class="text-danger" id="chat_user_name">&emsp;`+user_name+`</span></b>
							</div>
							<div class="col col-sm-6 text-right">
								<a href="chatroom.php" class="btn btn-success">Group chat</a>
								<button type="button" class="close" id="close_chat_area" data-dismiss="alert" arial-label="Close">
								<span aria-hidden="true">Close chat</span>
								</button>
							</div>
						</div>
						</div>


						<div class="card-body" id="chat_area"></div>
					</div>

					<form id="form_chat" method="POST">
						<div class="input-group">
						<textarea id="input_message" class="input_message form-control" name="input_message" placeholder="Type message here" required></textarea>
						
						<button class="btn btn-primary" type="submit" name="send" id="send"><i class="fa fa-paper-plane"></i></button>
						</div>
					</form>
				`;

				$('#chat_box').html(html);
			}

			$(document).on('click','.select_user',function(){
				$('#box_chat_active').val('Yes');
				receiver_id = $(this).data('userid');
				var from_user_id = $('#user_id-ip').val();
				var receiver_name = $('#receiver_id_'+receiver_id).text();
				//console.log(receiver_name);
				
				make_chat_area(receiver_id,receiver_name);

				$('.select_user.active').removeClass('active');
				$(this).addClass('active');
				$('#userid'+receiver_id).html('');
				$.ajax({
					url:'controller/chatController.php',
					method:'POST',
					data:{func_chat:'load_chat',from_user_id:from_user_id,to_user_id:receiver_id},
					dataType:'JSON',
					success:function(data)
					{
						if(data.length > 0)
						{
							var html_data = '';
							for(var count =0; count < data.length;count++)
							{
								if(data[count].from_user_id == from_user_id)
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

								html_data += `<br/><div class="`+row_class+`">`+space_user+`<div class="`+message_chat+`">`+data[count].chat_message+`<br/><small><i>`+data[count].create_date+`</i></small></div>`+space_me+`</div><br/>`;

								console.log(data[count].chat_message);
								
								$('#chat_area').html(html_data);
								$('#chat_area').scrollTop($('#chat_area')[0].scrollHeight);
							}
						}
					}

				});
			});

			$(document).on('click','#close_chat_area',function(){
				$('#chat_box').html(' ');
			});

			/*Nhấn nút gủi tin nhắn*/
			$(document).on('submit','#form_chat',function(e){
				e.preventDefault();
				
				var from_user_id = $('#user_id-ip').val();
				var msg = $('#input_message').val();
				var data = {
					userId:from_user_id,
					msg:msg,
					receiver_id:receiver_id,
					command:'private',
				};
				conn.send(JSON.stringify(data));
				$('#input_message').val(' ');
				$('#chat_area').scrollTop($('#chat_area')[0].scrollHeight);
			});

			$('#logout').click(function(){
				var userId =  $('#user_id-ip').val();
				var btn = $(this).val();
				$.ajax({
					url:'controller/userController.php',
					method:'POST',
					data:{
						btn:'logout',
						userId:userId,
					},
					success:function(data)
					{
						var response = JSON.parse(data);
						if(response.status == 1)
						{
							conn.close();
							location = 'login.php';
						}
						else
						{
							console.log(data);
						}
					}

				});
			});
		});
	</script>
</body>
</html>