<?php 
	class conDB
	{
		private $host = "localhost";
		private $user = "root";
		private $pass = "";
		private $dbname = "webchat";

		function connect()
		{
			$dsn = "mysql:host=".$this->host.";port=3308;dbname=".$this->dbname;
			$pdo = new PDO($dsn,$this->user,$this->pass);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$pdo->query('set names utf8');
			if($pdo)
			{
				return $pdo;
			}
			else
			{
				echo "Not connected";
			}
		}
	}
?>