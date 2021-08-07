<?php
	class DB
	{
		static $db = null;

		static $hostname = "localhost";
		static $dbname = "baipajoin";
		static $username  = "root";
		static $password = "";

		public static function query($sql, $params=array(), $transactType)
		{
			if(!is_array($params))
				$params = array($params);

			$rows = array();
			try
			{
				$db = new PDO("mysql:host=". self::$hostname ."; dbname=". self::$dbname,  self::$username,  self::$password);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$cmd = $db->prepare($sql);
				$cmd->execute($params);
				if($transactType == "READ")
					$rows = $cmd->fetchAll();
				else
					$rows = $db->lastInsertId();
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
			$db = null;
			unset($db);
			return $rows;
		}

		function newQuery($sql) {
			try {
				$conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$conn->exec($sql);
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
		}
	}
?>
