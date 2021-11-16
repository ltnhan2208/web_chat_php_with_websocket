<?php 
	
	class ChatRoom
	{
		private $room_id;
		private $user_id;
		private $message;
		private $message_create_date;
		private $conn;

		public function __construct()
		{
			require_once('database_connection.php');
			$database = new conDB;
			$this->conn = $database->connect();
		}

		public function setRoomId($room_id)
		{
			$this->room_id = $room_id;
		}

		public function getRoomId()
		{
			return $this->room_id;
		}

		public function setUserId($user_id)
		{
			$this->user_id = $user_id;
		}

		public function getUserId()
		{
			return $this->user_id;
		}

		public function setMessage($message)
		{
			$this->message = $message;
		}

		public function getMessage()
		{
			return $this->message;
		}

		public function setMessageCreate($message_create_date)
		{
			$this->message_create_date = $message_create_date;
		}

		public function getMessageCreate()
		{
			return $this->message_create_date;
		}

		public function save_chat()
		{
			$sql = "INSERT INTO tbl_room (user_id,room_message,message_create_date) 
			VALUES(:user_id,:message,:message_create_date)";

			$stm=$this->conn->prepare($sql);
			$stm->bindParam(':user_id', $this->user_id);
			$stm->bindParam(':message', $this->message);
			$stm->bindParam(':message_create_date', $this->message_create_date);

			if($stm->execute())
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function get_message_chat()
		{
			$sql = "SELECT * FROM tbl_room";

			$stm = $this->conn->prepare($sql);
			if($stm->execute())
			{
				$data = $stm->fetchAll(PDO::FETCH_ASSOC);
			}
			else
			{
				return false;
			}
			return $data;
		}
	}

?>