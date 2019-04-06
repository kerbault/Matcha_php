<?php

class suggestions extends user
{
    public function suggestedProfiles($userName, $gender, $orientation, $order)
    {
        $db = $this->dbConnect();

        $blocked = $db->prepare('SELECT `blocked_ID` FROM `blocked` inner join `users` ON users.ID = `blocked`.users_ID WHERE users.userName = :userName');

        $blocked->bindParam(':userName', $userName);
        $blocked->execute();

        $listBlocked = $blocked->fetchAll(PDO::FETCH_ASSOC);

        if ($gender == 2) {
            // MALE CASE
            switch ($orientation) {
                case 1:
                    $sql = $db->prepare("SELECT `ID`, `username`, `firstName`, `popularity`, `birthDate`, `profilePicture_ID`, `shortBio`, `position`, `popularity` FROM `users`
						WHERE (`userName` != :name 
						    AND `userStatus_ID` >= 4 
						    AND `orientations_ID` = 1 
						    AND `ID` NOT IN ('" . implode("', '", array_column($listBlocked, 'blocked_ID')) . "'))
						OR (`genders_ID` = 2 AND `orientations_ID` = 3 AND `ID` NOT IN ('" . implode("', '", array_column($listBlocked, 'blocked_ID')) . "')) 
						OR (`genders_ID` = 3 AND `orientations_ID` = 2 AND `ID` NOT IN ('" . implode("', '", array_column($listBlocked, 'blocked_ID')) . "'))
						" . $order);
                    break;
                case 2:
                    $sql = $db->prepare("SELECT `ID`, `username`, `firstName`, `popularity`, `birthDate`, `profilePicture_ID`, `shortBio`, `position`, `popularity` FROM `users`
						WHERE `userName` != :name 
						    AND `userStatus_ID` >= 4
						    AND `genders_ID` = 3 
						    AND `ID` NOT IN ('" . implode("', '", array_column($listBlocked, 'blocked_ID')) . "')
						    AND (`orientations_ID` = 1 OR `orientations_ID` = 2)
						" . $order);
                    break;
                case 3:
                    $sql = $db->prepare("SELECT `ID`, `username`, `firstName`, `popularity`, `birthDate`, `profilePicture_ID`, `shortBio`, `position`, `popularity` FROM `users`
						WHERE `userName` != :name 
						AND `userStatus_ID` >= 4 
						AND `genders_ID` = 2 
						AND `ID` NOT IN ('" . implode(", ", array_column($listBlocked, 'blocked_ID')) . "')
						AND (`orientations_ID` = 1 OR `orientations_ID` = 3)
						" . $order);

                    break;
            }
        } else if ($gender == 3) {
            // FEMALE CASE
            switch ($orientation) {
                case 1:
                    $sql = $db->prepare("SELECT `ID`, `username`, `firstName`, `popularity`, `birthDate`, `profilePicture_ID`, `shortBio`, `position`, `popularity` FROM `users`
						WHERE `userName` != :name 
						    AND `userStatus_ID` >= 4 
						    AND `orientations_ID` = 1 
						    AND `ID` NOT IN ('" . implode("', '", array_column($listBlocked, 'blocked_ID')) . "')
						OR (`genders_ID` = 2 AND `orientations_ID` = 2 AND `ID` NOT IN ('" . implode("', '", array_column($listBlocked, 'blocked_ID')) . "'))
						OR (`genders_ID` = 3 AND `orientations_ID` = 3 AND `ID` NOT IN ('" . implode("', '", array_column($listBlocked, 'blocked_ID')) . "'))
						" . $order);
                    break;
                case 2:
                    $sql = $db->prepare("SELECT `ID`, `username`, `firstName`, `popularity`, `birthDate`, `profilePicture_ID`, `shortBio`, `position`, `popularity` FROM `users`
						WHERE `userName` != :name 
						AND `userStatus_ID` >= 4 
						AND `genders_ID` = 2 
						AND (`orientations_ID` = 1 OR `orientations_ID` = 1)
						AND `ID` NOT IN ('" . implode("', '", array_column($listBlocked, 'blocked_ID')) . "')
						" . $order);
                    break;
                case 3:
                    $sql = $db->prepare("SELECT `ID`, `username`, `firstName`, `popularity`, `birthDate`, `profilePicture_ID`, `shortBio`, `position`, `popularity` FROM `users`
						WHERE `userName` != :name 
						AND `userStatus_ID` >= 4 
						AND `genders_ID` = 3 
						AND (`orientations_ID` = 1 OR `orientations_ID` = 3)
						AND `ID` NOT IN ('" . implode("', '", array_column($listBlocked, 'blocked_ID')) . "')
						" . $order);
                    break;
            }
        }

        $sql->bindParam(':name', $userName);
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}

//
// SELECT `ID` FROM `users`
// LEFT JOIN `blocked` ON `users`.`ID` = `blocked`.`blocked_ID`
// WHERE `userStatus_ID` >= 4 AND `genders_ID` = 3 AND (`orientations_ID` = 1 OR `orientations_ID` = 2)
// UNION
// SELECT `ID` FROM `users`
// LEFT JOIN `blocked` ON `users`.`ID` = `blocked`.`blocked_ID`
// WHERE `blocked`.`users_ID` IS NULL AND `userStatus_ID` >= 4 AND `genders_ID` = 3 AND (`orientations_ID` = 1 OR `orientations_ID` = 2)
