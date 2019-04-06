<?php

class Manager
{
	protected function dbConnect()
	{
		include('private/config/database.php');

		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);

		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		return $db;
	}
}
