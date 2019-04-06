<?php

class chat extends Manager
{
	public function get_last_id($userID, $matchID) {
		$db = $this->dbConnect();

		$sql = $db->prepare("SELECT `private_ID` FROM messages WHERE (`users_ID` = ? AND `receiver_ID` = ?) OR (`users_ID` = ? AND `receiver_ID` = ?) ORDER BY `private_ID` DESC");
		$sql->execute([$userID, $matchID, $matchID, $userID]);

		$result = $sql->fetchAll(PDO::FETCH_ASSOC);
		if (isset($result[0]['private_ID']))
			return $result[0]['private_ID'];
		else
			return 0;
	}

	public function get_messages($userID, $matchID)
	{
		$db = $this->dbConnect();

		$sql = $db->prepare("SELECT `private_id`, `users_ID`, `receiver_ID`, `message`, `timestamp`, `status` FROM messages WHERE (`users_ID` = ? AND `receiver_ID` = ?) OR (`users_ID` = ? AND `receiver_ID` = ?)");
		$sql->execute([$userID, $matchID, $matchID, $userID]);
		$result = $sql->fetchAll(PDO::FETCH_ASSOC);
		if (isset($result[0]['private_id']))
			return $result;
		else
			return false;
	}

	public function get_message($pid, $userID, $matchID)
	{
		$db = $this->dbConnect();
		$sql = $db->prepare("SELECT * FROM messages WHERE `private_ID` = ? AND ((`users_ID` = ? AND `receiver_ID` = ?) OR (`users_ID` = ? AND `receiver_ID` = ?))");
		$sql->execute([$pid, $userID, $matchID, $matchID, $userID]);
		$result = $sql->fetchAll(PDO::FETCH_ASSOC);
		if (isset($result[0]['private_ID']))
			return $result[0];
		else
			return false;
	}

	public function add_message($id, $userID, $receiverID, $msg)
	{
		$db = $this->dbConnect();

		$sql = $db->prepare('INSERT INTO messages(`private_id`, `users_ID`, `receiver_ID`, `message`, `timestamp`, `status`) VALUES(?, ?, ?, ?, ?, ?)');
		$sql->execute([$id, $userID, $receiverID, $msg, date("Y-m-d H:i:s"), 0]);
	}

	public function getNewMessages($userID) {
		$db = $this->dbConnect();

		$sql = $db->prepare('SELECT * FROM `messages` WHERE `receiver_ID` = :id AND `status` = 0 ORDER BY `timestamp` DESC');
		$sql->bindParam(':id', $userID);
		$sql->execute();

		$res = $sql->fetchAll(PDO::FETCH_ASSOC);
		if (isset($res[0]['users_ID']))
			return $res;
		else
			return NULL;
	}

	public function getMessages($userID) {
		$db = $this->dbConnect();

		$sql = $db->prepare('SELECT * FROM `messages` WHERE `receiver_ID` = :id ORDER BY `timestamp` DESC');
		$sql->bindParam(':id', $userID);
		$sql->execute();

		$res = $sql->fetchAll(PDO::FETCH_ASSOC);
		if (isset($res[0]['users_ID']))
			return $res;
		else
			return NULL;
	}

	public function updateNewMessages($userID) {
		$db = $this->dbConnect();

		$sql = $db->prepare('UPDATE `messages` SET `status` = 1 WHERE `receiver_ID` = :id AND `status` = 0');
		$sql->bindParam(':id', $userID);
		$sql->execute();
	}
}
