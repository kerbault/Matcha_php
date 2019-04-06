<?php

	class views extends Manager {

		public function viewUser($userID, $viewedID) {
			$db = $this->dbConnect();

			$sql = $db->prepare('INSERT INTO `viewed` (`users_ID`, `viewed_ID`, `timestamp`, `status`) VALUES (:user, :viewed, NOW(), 0)');
			$sql->bindParam(':user', $userID);
			$sql->bindParam(':viewed', $viewedID);
			$sql->execute();
		}

		public function reviewUser($userID, $viewedID) {
			$db = $this->dbConnect();

			$sql = $db->prepare('UPDATE `viewed` SET `status` = 0, `timestamp` = NOW() WHERE `users_id` = :user AND `viewed_ID` = :viewed');
			$sql->bindParam(':user', $userID);
			$sql->bindParam(':viewed', $viewedID);
			$sql->execute();
		}

		public function alreadyVisited($userID, $viewedID) {
			$db = $this->dbConnect();

			$sql = $db->prepare('SELECT * FROM `viewed` WHERE `users_ID` = :user AND `viewed_ID` = :viewed');
			$sql->bindParam(':user', $userID);
			$sql->bindParam(':viewed', $viewedID);
			$sql->execute();

			$res = $sql->fetchAll(PDO::FETCH_ASSOC);
			if (isset($res[0]['users_ID']))
				return TRUE;
			else
				return FALSE;
		}

		public function getNewViews($userID) {
			$db = $this->dbConnect();

			$sql = $db->prepare('SELECT * FROM `viewed` WHERE `viewed_ID` = :id AND `status` = 0 ORDER BY `timestamp` DESC');
			$sql->bindParam(':id', $userID);
			$sql->execute();

			$res = $sql->fetchAll(PDO::FETCH_ASSOC);
			if (isset($res[0]['users_ID']))
				return $res;
			else
				return NULL;
		}

		public function updateNewViews($userID) {
			$db = $this->dbConnect();

			$sql = $db->prepare('UPDATE `viewed` SET `status` = 1 WHERE `viewed_ID` = :id AND `status` = 0');
			$sql->bindParam(':id', $userID);
			$sql->execute();
		}

		public function getViews($userID) {
			$db = $this->dbConnect();

			$sql = $db->prepare('SELECT COUNT(`viewed_ID`) AS nb FROM viewed WHERE `viewed_ID` = :user');
			$sql->bindParam(':user', $userID);
			$sql->execute();

			$res = $sql->fetchAll(PDO::FETCH_ASSOC);
			return ($res[0]['nb']);
		}

		public function getOtherViews($userID) {
			$db = $this->dbConnect();

			$sql = $db->prepare('SELECT * FROM viewed WHERE `viewed_ID` = :id');
			$sql->bindParam(':id', $userID);
			$sql->execute();

			$res = $sql->fetchAll(PDO::FETCH_ASSOC);
			if (isset($res[0]['users_ID']))
				return ($res);
			return false;
		}
	}
