<?php

class popularity extends Manager {
	public function updatePopularity($nb, $userID) {
		$db = $this->dbConnect();

		$sql = $db->prepare('UPDATE users SET `popularity` = :nb WHERE `ID` = :user');
		$sql->bindParam(':nb', $nb);
		$sql->bindParam(':user', $userID);
		$sql->execute();
	}
}
