<?php

class tags extends Manager
{
	public function getTags()
	{
		$db = $this->dbConnect();

		$sql = $db->prepare('SELECT * FROM `tags`');
		$sql->execute();

		$tags = $sql->fetchAll(PDO::FETCH_ASSOC);
		return $tags;
	}
	public function delAllTags($username) {
		$db = $this->dbConnect();

		$sql = $db->prepare('DELETE FROM users_has_tag WHERE `users_ID` = :user');
		$sql->bindParam(':user', $username);
		$sql->execute();
	}
	public function getTagName($tagID) {
		$db = $this->dbConnect();

		$sql = $db->prepare('SELECT `tag` FROM `tags` WHERE `ID` = :id');
		$sql->bindParam(':id', $tagID);
		$sql->execute();

		$tag = $sql->fetchAll(PDO::FETCH_ASSOC);
		if (isset($tag[0]['tag']))
			return $tag[0]['tag'];
		else
			return false;
	}
	public function newTag($tag) {
		$db = $this->dbConnect();

		$sql = $db->prepare('INSERT INTO tags(`tag`) VALUES(:tag)');
		$sql->bindParam(':tag', $tag);
		$sql->execute();
	}
	public function getTagId($tag) {
		$db = $this->dbConnect();

		$sql = $db->prepare('SELECT `ID` FROM tags WHERE `tag` = :tag');
		$sql->bindParam(':tag', $tag);
		$sql->execute();

		$tag = $sql->fetchAll(PDO::FETCH_ASSOC);
		if (isset($tag[0]['ID']))
			return $tag[0]['ID'];
		else
			return false;
	}
	public function addTagToUser($tag, $user)
	{
		$db = $this->dbConnect();

		$sql = $db->prepare('SELECT * FROM `users_has_tag` WHERE `users_id` = :user AND `tag_ID` = :tag');
		$sql->bindParam(':user', $user);
		$sql->bindParam(':tag', $tag);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return false;
		}
		$sql = $db->prepare('INSERT INTO users_has_tag(`users_id`, `tag_ID`) VALUES(:user, :tag)');
		$sql->bindParam(':user', $user);
		$sql->bindParam(':tag', $tag);
		$sql->execute();
		return true;
	}

	public function deleteTagFromUser($tag, $user)
	{
		$db = $this->dbConnect();

		$sql = $db->prepare('DELETE FROM `users_has_tag` WHERE `users_ID` = :user AND `tag_ID` = :tag');
		$sql->bindParam(':user', $user);
		$sql->bindParam(':tag', $tag);
		$sql->execute();
	}

	public function getUserTags($id)
	{
		$db = $this->dbConnect();

		$sql = $db->prepare('SELECT `tag_ID` FROM `users_has_tag` WHERE `users_id` = :id');
		$sql->bindParam(':id', $id);
		$sql->execute();

		$tags = $sql->fetchAll(PDO::FETCH_ASSOC);
		return $tags;
	}
}
