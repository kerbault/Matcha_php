<?php

class hydrate extends user
{
	public function registerUser($fname, $lname, $birthDate, $username, $email, $password, $creationDate, $key, $gender, $sexualOrientation, $bio, $position, $popularity)
	{
		$db = $this->dbConnect();

		$sql = $db->prepare('INSERT INTO `users`(`firstName`, `lastName`, `birthDate`, `userName`, `email`, `password`, `creationDate`, `validKey`, `userStatus_ID`, `genders_ID`, `orientations_ID`, `shortBio`, `position`, `popularity`) VALUES(? , ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
		$sql->execute([$fname, $lname, $birthDate, $username, $email, $password, $creationDate, $key, 4, $gender, $sexualOrientation, $bio, $position, $popularity]);
	}
}
