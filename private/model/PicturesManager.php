<?php

class pictures extends Manager
{
    public function registerPicture($img, $userId, $setProfilePic)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('INSERT INTO pictures(`users_ID`, `path`) VALUES(:id, :img)');
        $sql->bindParam(':id', $userId);
        $sql->bindParam(':img', $img);
        $sql->execute();
        $picID = $db->lastInsertId();

        if ($setProfilePic === true) {
            $this->registerMainPicture($userId, $picID);
        }
    }

    public function getPicturesId($userId)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT `ID` FROM `pictures` WHERE `users_ID` = :user');
        $sql->bindParam(':user', $userId);
        $sql->execute();
        $picsID = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($picsID[0]['ID']))
            return $picsID;
        else
            return FALSE;
    }

    public function registerMainPicture($userID, $picID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('UPDATE users SET `profilePicture_ID` = :picID WHERE `ID` = :userID');
        $sql->bindParam(':picID', $picID);
        $sql->bindParam(':userID', $userID);
        $sql->execute();
    }

    public function getProfilePicturePath($picture_id)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT `path` FROM `pictures` WHERE `ID` = :id');
        $sql->bindParam(':id', $picture_id);
        $sql->execute();

        $res = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($res[0]['path']))
            return $res[0]['path'];
        else
            return FALSE;
    }

    public function listPicture($userID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT `ID`,`path` FROM `pictures` WHERE `users_ID` = :userID');
        $sql->bindParam(':userID', $userID);
        $sql->execute();

        $res = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function countUserPicture($userID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT COUNT(ID) AS `pictureNb` FROM `pictures` WHERE `users_ID` = :userID');
        $sql->bindParam(':userID', $userID);
        $sql->execute();

        $res = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($res[0]['pictureNb']))
            return $res[0]['pictureNb'];
        else
            return FALSE;
    }

    public function remPicture($pictureID)
    {
        $db = $this->dbConnect();

        $selected = $db->prepare('SELECT `path` FROM `pictures` WHERE `ID` = ?');
        $selected->execute(array($pictureID));
        $deleted = $selected->fetch();

        $picture = $db->prepare('DELETE FROM `pictures` WHERE `ID` = ?');
        $picture->execute(array($pictureID));

        return ($deleted);
    }

    public function fetchInfo($pictureID, $param)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT ' . $param . ' FROM pictures WHERE `ID` = :pictureID');
        $sql->bindParam(':pictureID', $pictureID);

        $sql->execute();

        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($result[0][$param])) {
            return $result[0][$param];
        }
        return false;
    }
}
