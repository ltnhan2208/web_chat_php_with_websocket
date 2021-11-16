<?php 

	class PrivateChat
	{
		private $chat_message_id;
		private $from_user_id;
		private $to_user_id;
		private $chat_message;
		private $create_date;
		private $status;
		private $conn;

		public function __construct()
		{
			require_once('database_connection.php');
			$database = new conDB;
			$this->conn = $database->connect();
		}

		public function setChatId($chat_message_id)
		{
			$this->chat_message_id = $chat_message_id;
		}

		public function getChatId()
		{
			return $this->chat_message_id;
		}

		public function setFromId($from_user_id)
		{
			$this->from_user_id = $from_user_id;
		}

		public function getFromId()
		{
			return $this->from_user_id;
		}

		public function setToId($to_user_id)
		{
			$this->to_user_id = $to_user_id;
		}

		public function getToId()
		{
			return $this->to_user_id;
		}

		public function setChatMessage($chat_message)
		{
			$this->chat_message = $chat_message;
		}

		public function getChatMessage()
		{
			return $this->chat_message;
		}

		public function setCreateDate($create_date)
		{
			$this->create_date = $create_date;
		}

		public function getCreateDate()
		{
			return $this->create_date;
		}

		public function setStatus($status)
		{
			$this->status = $status;
		}

		public function getStatus()
		{
			return $this->status;
		}

		public function change_chat_status()
		{
			$sql = "UPDATE tbl_chat_message SET status=:status
			WHERE from_user_id = :from_user_id 
			AND to_user_id = :to_user_id
			AND status = 'Yes'";
			
			$stm=$this->conn->prepare($sql);
			$stm->bindParam(':status',$this->status);
			$stm->bindParam(':from_user_id',$this->from_user_id);
			$stm->bindParam(':to_user_id',$this->to_user_id);

			$stm->execute();
		}

		public function get_all_chat()
		{
			$sql = "SELECT * FROM tbl_chat_message
			INNER JOIN tbl_user a ON tbl_chat_message.from_user_id = a.user_id 
			INNER JOIN tbl_user b ON tbl_chat_message.to_user_id = b.user_id 
			WHERE (tbl_chat_message.from_user_id = :from_user_id AND tbl_chat_message.to_user_id = :to_user_id)
			OR (tbl_chat_message.to_user_id = :from_user_id AND tbl_chat_message.to_user_id = :from_user_id)";
			$stm=$this->conn->prepare($sql);
			$stm->bindParam(':from_user_id',$this->from_user_id);
			$stm->bindParam(':to_user_id',$this->to_user_id);

			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_ASSOC); 
		}

		public function save_chat()
		{
			$sql = "INSERT INTO tbl_chat_message(from_user_id, to_user_id, chat_message, create_date, status) VALUES(:from_user_id, :to_user_id, :chat_message, :create_date,:status)";

			$stm = $this->conn->prepare($sql);
			$stm->bindParam(':from_user_id',$this->from_user_id);
			$stm->bindParam(':to_user_id',$this->to_user_id);
			$stm->bindParam(':chat_message',$this->chat_message);
			$stm->bindParam(':create_date',$this->create_date);
			$stm->bindParam(':status',$this->status);

			$stm->execute();
			$stm2 = $this->conn->query("SELECT LAST_INSERT_ID()");
			$lastId = $stm2->fetchColumn();

			return $lastId;
		}

		public function update_chat_status()
		{
			$sql = "UPDATE tbl_chat_message SET status = :status WHERE chat_message_id = :chat_message_id";

			$stm = $this->conn->prepare($sql);
			$stm->bindParam(':status',$this->status);
			$stm->bindParam(':chat_message_id',$this->chat_message_id);

			$stm->execute();
		}

	}

?>