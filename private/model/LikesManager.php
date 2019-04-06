<?php

class likes extends Manager
{

    public function getLikes($id)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT * FROM `likes` WHERE `users_ID` = :id');
        $sql->bindParam(':id', $id);
        $sql->execute();

        $res = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($res[0]['users_ID']))
            return $res;
        else
            return FALSE;
    }

    public function getOtherLikes($id)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT * FROM `likes` WHERE `liked_ID` = :id');
        $sql->bindParam(':id', $id);
        $sql->execute();

        $res = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($res[0]['users_ID']))
            return $res;
        else
            return FALSE;
    }

    public function getNewLikes($id)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT * FROM `likes` WHERE `liked_ID` = :id AND `status` = 0 ORDER BY `date` DESC');
        $sql->bindParam(':id', $id);
        $sql->execute();

        $res = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($res[0]['users_ID']))
            return $res;
        else
            return NULL;
    }

    public function getNewMatches($id)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT * FROM `likes` WHERE `liked_ID` = :id AND `status` = 0 ORDER BY `date` DESC');
        $sql->bindParam(':id', $id);
        $sql->execute();

        $res = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($res[0]['users_ID']))
            return $res;
        else
            return NULL;
    }

    public function updateNewLikes($id)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('UPDATE `likes` SET `status` = 1 WHERE `liked_ID` = :id AND `status` = 0');
        $sql->bindParam(':id', $id);
        $sql->execute();
    }

    public function getLikedBy($id)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT * FROM `likes` WHERE `liked_ID` = :id');
        $sql->bindParam(':id', $id);
        $sql->execute();

        $res = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($res[0]['users_ID']))
            return $res;
        else
            return FALSE;
    }

    public function likeUser($userID, $likedID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('INSERT INTO likes (`users_ID`, `liked_ID`, `date`, `status`, `liked`) VALUES (:user, :liked, NOW(), 0, 1)');
        $sql->bindParam(':user', $userID);
        $sql->bindParam(':liked', $likedID);
        $sql->execute();
    }

    public function relikeUser($userID, $likedID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('UPDATE likes SET `liked` = 1, `date` = NOW(), `status` = 0 WHERE `users_ID` = :user AND `liked_ID` = :liked');
        $sql->bindParam(':user', $userID);
        $sql->bindParam(':liked', $likedID);
        $sql->execute();
    }

    public function dislikeUser($userID, $likedID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('UPDATE likes SET `liked` = 0, `date` = NOW(), `status` = 0 WHERE `users_ID` = :user AND `liked_ID` = :liked');
        $sql->bindParam(':user', $userID);
        $sql->bindParam(':liked', $likedID);
        $sql->execute();
    }

    public function getLikeStatus($userID, $likedID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT `liked` FROM likes WHERE `users_ID` = :user AND `liked_ID` = :liked');
        $sql->bindParam(':user', $userID);
        $sql->bindParam(':liked', $likedID);
        $sql->execute();

        $res = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($res[0]['liked']))
            return $res[0]['liked'];
        else
            return false;
    }

    public function getLikesNumber($userID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT COUNT(`liked_ID`) AS nb FROM likes WHERE `liked_ID` = :user AND `liked` = 1');
        $sql->bindParam(':user', $userID);
        $sql->execute();

        $res = $sql->fetchAll(PDO::FETCH_ASSOC);
        return ($res[0]['nb']);
    }

    public function listLiked($userID)
    {
        $db = $this->dbConnect();

        $liked = $db->prepare('SELECT likes.date AS `date`,users.userName AS `userName`
                                         FROM `users` INNER JOIN `likes` ON likes.liked_ID = users.ID 
                                         WHERE `users_ID` = :userID AND likes.liked = 1
                                         ORDER BY `date` DESC');
        $liked->bindParam(':userID', $userID);
        $liked->execute();

        return $liked;
    }

    public function listLikedBy($userID)
    {
        $db = $this->dbConnect();

        $liked = $db->prepare('SELECT likes.date AS `date`,users.userName AS `userName`
                                         FROM `users` INNER JOIN `likes` ON likes.users_ID = users.ID 
                                         WHERE `liked_ID` = :userID AND likes.liked = 1
                                         ORDER BY `date` DESC');
        $liked->bindParam(':userID', $userID);
        $liked->execute();

        return $liked;
    }
}
