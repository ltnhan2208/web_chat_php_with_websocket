<?php 

	class ChatUser
	{
		private $user_id;
		private $user_name;
		private $user_email;
		private $user_password;
		private $user_profile;
		private $user_status;
		private $user_create_date;
		private $user_verification_code;
		private $user_login_status;
		private $user_token;
		private $user_connection_id;
		public $conn;

		public function __construct()
		{
			require_once('database_connection.php');
			$database = new conDB;
			$this->conn = $database->connect();
		}

		function setUserId($user_id)
		{
			$this->user_id = $user_id;
		}
		function getUserId()
		{
			return $this->user_id;
		}

		function setUserName($user_name)
		{
			$this->user_name = $user_name;
		}
		function getUserName()
		{
			return $this->user_name;
		}

		function setUserEmail($user_email)
		{
			$this->user_email = $user_email;
		}
		function getUserEmail()
		{
			return $this->user_email;
		}

		function setUserPassword($user_password)
		{
			$this->user_password = $user_password;
		}
		function getUserPassword()
		{
			return $this->user_password;
		}

		function setUserProfile($user_profile)
		{
			$this->user_profile = $user_profile;
		}
		function getUserProfile()
		{
			return $this->user_profile;
		}

		function setUserStatus($user_status)
		{
			$this->user_status = $user_status;
		}
		function getUserStatus()
		{
			return $this->user_status;
		}

		function setUserCreateDate($user_create_date)
		{
			$this->user_create_date = $user_create_date;
		}
		function getUserCreateDate()
		{
			return $this->user_create_date;
		}

		function setUserVerCode($user_verification_code)
		{
			$this->user_verification_code = $user_verification_code;
		}
		function getUserVerCode()
		{
			return $this->user_verification_code;
		}

		function setUserLogin($user_login_status)
		{
			$this->user_login_status = $user_login_status;
		}
		function getUserLogin()
		{
			return $this->user_login_status;
		}

		function setUserToken($user_token)
		{
			$this->user_token = $user_token;
		}
		function getUserToken()
		{
			return $this->user_token;
		}

		function setConnectionId($user_connection_id)
		{
			$this->user_connection_id = $user_connection_id;
		}
		function getConnectionId()
		{
			return $this->user_connection_id;
		}

		function get_user_data_by_id()
		{
			$sql = "
				SELECT * from tbl_user where user_id = :user_id
			";

			$stm = $this->conn->prepare($sql);
			$stm->bindParam(":user_id",$this->user_id);

			if($stm->execute())
			{
				$user_data = $stm->fetch(PDO::FETCH_ASSOC);
			}
			else
			{
				$user_data = array();
			}
			return $user_data;
		}

		function get_user_data_by_email()
		{
			$sql = "
				SELECT * from tbl_user where user_email = ?
			";

			$stm = $this->conn->prepare($sql);
			$stm->bindParam(1,$this->user_email);

			if($stm->execute())
			{
				$user_data = $stm->fetch(PDO::FETCH_ASSOC);
			}
			return $user_data;
		}
		function save_data()
		{
				$sql = "
			INSERT INTO tbl_user (user_name, user_email, user_password, user_profile, user_status, user_create_date, user_verification_code) 
			VALUES (:user_name, :user_email, :user_password, :user_profile, :user_status, :user_create_date, :user_verification_code)
			";
			$statement = $this->conn->prepare($sql);

			$statement->bindParam(':user_name', $this->user_name);

			$statement->bindParam(':user_email', $this->user_email);

			$statement->bindParam(':user_password', $this->user_password);

			$statement->bindParam(':user_profile', $this->user_profile);

			$statement->bindParam(':user_status', $this->user_status);

			$statement->bindParam(':user_create_date', $this->user_create_date);

			$statement->bindParam(':user_verification_code', $this->user_verification_code);

			if($statement->execute())
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		function is_valid_email_verification_code()
		{
			$sql = "SELECT * from tbl_user where user_verification_code = ?";
			$stm = $this->conn->prepare($sql);
			$stm->bindParam(1,$this->user_verification_code);
			$stm->execute();
			if($stm->rowCount()>0)
			{return true;}
			else
			{return false;}
		}

		function enable_user_status()
		{
			$sql = "UPDATE tbl_user SET user_status = ?
				WHERE user_verification_code = ?
			 ";

			 $stm = $this->conn->prepare($sql);
			 $stm->bindParam(1,$this->user_status);
			 $stm->bindParam(2,$this->user_verification_code);
			 
			 if($stm->execute())
			 {
			 	return true;
			 }
			 else
			 {
			 	return false;
			 }
		}

		function update_login_status ()
		{
				$sql = "UPDATE tbl_user SET user_login_status = 'login', user_token = :user_token
				where user_id = :user_id";

				$stm = $this->conn->prepare($sql);

				$stm->bindParam(":user_id",$this->user_id);
				$stm->bindParam(":user_token",$this->user_token);

				$stm->execute();
		}

		function update_logout_status()
		{
				$sql = "UPDATE tbl_user SET user_login_status = 'logout'
				where user_id = :user_id";

				$stm = $this->conn->prepare($sql);

				$stm->bindParam(":user_id",$_SESSION['user']['user_id']);

				$stm->execute();
		}

		function upload_image($user_avatar)
		{
			$img = $user_avatar['name'];
			$path = '../public/images/'.basename($img);
			$tmp_name = $user_avatar['tmp_name'];
			$move = move_uploaded_file($tmp_name,$path);
			$img = $user_avatar['name'];
			$path = '../public/images/'.basename($img);
			$tmp_name = $user_avatar['tmp_name'];
			$move = move_uploaded_file($tmp_name,$path);
			if($move)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		function save_update_profile()
		{
			$sql = "UPDATE tbl_user SET user_name = :user_name, user_email=:user_email,user_password=:user_password,user_profile=:user_profile where user_id =:user_id";

			$stm = $this->conn->prepare($sql);
			$stm->bindParam(":user_id",$this->user_id);
			$stm->bindParam(":user_name",$this->user_name);
			$stm->bindParam(":user_email",$this->user_email);
			$stm->bindParam(":user_password",$this->user_password);
			$stm->bindParam(":user_profile",$this->user_profile);

			if($stm->execute())
			{
				$_SESSION['user']['user_name'] = $this->user_name;
				$_SESSION['user']['user_password'] = $this->user_password;
				$_SESSION['user']['user_profile'] = $this->user_profile;
				$_SESSION['user']['user_email'] = $this->user_email;

				$_SESSION['access']="Cập nhật thông tin thành công!";
				return true;
			}
			else
			{
				$_SESSION['failed']="Cập nhật thông tin thất bại!";
				return false;
			}	 
		}

		function get_all_user()
		{
			$sql = "SELECT * FROM tbl_user";

			$stm = $this->conn->prepare($sql);
			if($stm->execute())
			{
				$list_user = $stm->fetchAll(PDO::FETCH_ASSOC);
				return $list_user;
			}
			else
			{
				return false;
			}
		}

		function get_all_user_status_count()
		{
			$sql = "SELECT *,(SELECT COUNT(*) FROM tbl_chat_message WHERE to_user_id = :user_id AND from_user_id = tbl_user.user_id AND status='No') AS count_status FROM tbl_user";

			$stm = $this->conn->prepare($sql);
			$stm->bindParam(':user_id',$this->user_id);
			if($stm->execute())
			{
				$list_user = $stm->fetchAll(PDO::FETCH_ASSOC);
				return $list_user;
			}
			else
			{
				return false;
			}
		}

		function update_connection_id()
		{
			$sql = "UPDATE tbl_user SET user_connection_id = :user_connection_id WHERE user_token = :user_token";

			$stm = $this->conn->prepare($sql);
			$stm->bindParam(':user_connection_id',$this->user_connection_id);
			$stm->bindParam(':user_token',$this->user_token);

			if($stm->execute())
			{
				return true;
			}
			else
			{
				echo false;
			}
		}

		function get_user_data_by_token()
		{
			$sql = "
				SELECT * from tbl_user where user_token = :user_token
			";

			$stm = $this->conn->prepare($sql);
			$stm->bindParam(":user_token",$this->user_token);

			$stm->execute();

			$user_id = $stm->fetch(PDO::FETCH_ASSOC);
		
			return $user_id;
		}


	}
?>