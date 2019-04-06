<?php

include('private/config/database.php');

try {
	$db = new PDO($DB_DSN_LIGHT, $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$create_db = file_get_contents('private/config/matcha.sql');
	$db->exec($create_db);
	$db = null;
} catch (PDOException $e) {
	throw new Exception("Database creation failed: " . $e->getMessage());
}