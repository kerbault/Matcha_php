<?php
require_once('private/model/Manager.php');

class user extends Manager
{
    public function createAccount($fname, $lname, $date, $userName, $email, $password, $key)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('INSERT INTO users(`firstName`, `lastName`, `birthDate`, `userName`, `email`, `password`, `creationDate`, `validKey`) VALUES(?, ?, ?, ?, ?, ?, ?, ?)');
        $sql->execute([$fname, $lname, $date, $userName, $email, $password, date('Y-m-d H:i:s'), $key]);
    }

    public function checkUserExists($userName, $email)
    {
        $db = $this->dbConnect();

        $sql_user = $db->prepare("SELECT `userName` FROM `users` WHERE `userName` = :name");
        $sql_user->bindParam(':name', $userName);
        $sql_user->execute();

        $sql_email = $db->prepare("SELECT `email` FROM `users` WHERE `email` = :email");
        $sql_email->bindParam(':email', $email);
        $sql_email->execute();

        if ($sql_user->rowCount() > 0 || $sql_email->rowCount() > 0) {
            return false;
        }
        return true;
    }

    public function verifyKey($userName, $key)
    {
        $db = $this->dbConnect();

        $user_key = $db->prepare("SELECT `validKey` FROM `users` WHERE `userName` = :name");
        $user_key->bindParam(':name', $userName);
        $user_key->execute();

        if ($user_key->rowCount() > 0) {
            $real_key = $user_key->fetchAll(PDO::FETCH_ASSOC);
            if ($real_key[0]['validKey'] == $key) {
                $sql = $db->prepare("UPDATE users SET `userStatus_ID` = 3,`validKey` = '0' WHERE `userName` = :name");
                $sql->bindParam(':name', $userName);
                $sql->execute();
            }
        }
    }

    public function verifyLogin($userName)
    {
        $db = $this->dbConnect();

        $userLoginTmp = $db->prepare("SELECT `password`,`userStatus_ID` FROM `users` WHERE `userName` = :name");
        $userLoginTmp->bindParam(':name', $userName);
        $userLoginTmp->execute();

        $userLogin = $userLoginTmp->fetch();

        return $userLogin;
    }

    public function loginUser($userName)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare("UPDATE users SET `online` = 1, `lastLog` = NOW() WHERE `userName` = :name");
        $sql->bindParam(':name', $userName);
        $sql->execute();
    }

    public function logoutUser($userName)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare("UPDATE users SET `online` = 0, `lastLog` = NOW() WHERE `userName` = :name");
        $sql->bindParam(':name', $userName);
        $sql->execute();
    }

    public function fetchProfile($userID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare("SELECT * FROM users WHERE `ID` = :userID");
        $sql->bindParam(':userID', $userID);
        $sql->execute();

        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($result[0]['ID'])) {
            return $result[0];
        }
        return false;
    }

    public function fetchInfo($user, $param)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT ' . $param . ' FROM users WHERE `userName` = :user');
        $sql->bindParam(':user', $user);

        $sql->execute();

        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($result[0][$param])) {
            return $result[0][$param];
        }
        return false;
    }

    public function updateInfo($user, $param, $value)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare("UPDATE users SET $param = :content WHERE `userName` = :user");
        $sql->bindParam(':content', $value);
        $sql->bindParam(':user', $user);
        $sql->execute();
    }

    public function forceLocation($username, $loc)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('UPDATE users SET `position` = :pos WHERE `userName` = :user');
        $sql->bindParam(':user', $username);
        $sql->bindParam(':pos', $loc);
        $sql->execute();
    }

    public function fetchUsername($id)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT `userName` FROM users WHERE `ID` = :id');
        $sql->bindParam(':id', $id);
        $sql->execute();

        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($result[0]['userName'])) {
            return $result[0]['userName'];
        }
        return false;
    }

    public function listUsers()
    {
        $db = $this->dbConnect();

        $users = $db->prepare('SELECT users.ID,`userName`, userStatus.userStatus AS `status`
                                         FROM users INNER JOIN userStatus ON userStatus.ID = users.userStatus_ID
                                         ORDER BY users.ID');

        return $users;
    }

    public function listRoles()
    {
        $db = $this->dbConnect();

        $roles = $db->prepare('SELECT `ID`,`userStatus` FROM `userStatus` ORDER BY `ID`');

        return $roles;
    }

    public function listReported()
    {
        $db = $this->dbConnect();

        $reports = $db->prepare('SELECT reports.reported_ID AS `ID`, users.userName AS `user`, COUNT(`users_ID`) AS `reports`
                                           FROM `reports` INNER JOIN users ON users.ID = reports.reported_ID
                                           WHERE users.userStatus_ID != 1
                                           GROUP BY reports.reported_ID
                                           ORDER BY COUNT(`users_ID`) DESC');
        return $reports;
    }

    public function bannedUsers()
    {
        $db = $this->dbConnect();

        $bans = $db->prepare('SELECT users.ID AS `ID`, users.userName AS `user`
                                        FROM `users`
                                        WHERE users.userStatus_ID = 1
                                        ORDER BY users.ID ASC');

        return $bans;
    }

    public function report($userID, $reportedID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('INSERT INTO reports (`users_ID`, `reported_ID`, `timestamp`) VALUES(:user, :reported, NOW())');
        $sql->bindParam(':user', $userID);
        $sql->bindParam(':reported', $reportedID);
        $sql->execute();
    }

    public function alreadyReported($userID, $reportedID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT * FROM reports WHERE `users_ID` = :user AND `reported_ID` = :reported');
        $sql->bindParam(':user', $userID);
        $sql->bindParam(':reported', $reportedID);
        $sql->execute();

        $res = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($res[0]['users_ID']))
            return true;
        return false;
    }

    public function block($userID, $blockedID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('INSERT INTO blocked (`users_ID`, `blocked_ID`, `timestamp`) VALUES(:user, :blocked, NOW())');
        $sql->bindParam(':user', $userID);
        $sql->bindParam(':blocked', $blockedID);
        $sql->execute();
    }

    public function unblockUser($userID, $blockedID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('DELETE FROM blocked WHERE `users_ID` = :user AND `blocked_ID` = :blocked');
        $sql->bindParam(':user', $userID);
        $sql->bindParam(':blocked', $blockedID);
        $sql->execute();
    }

    public function getBlocked($userID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT * FROM blocked WHERE `users_ID` = :user');
        $sql->bindParam(':user', $userID);
        $sql->execute();

        $res = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($res[0]['users_ID']))
            return $res;
        return false;
    }

    public function alreadyBlocked($userID, $blockedID)
    {
        $db = $this->dbConnect();

        $sql = $db->prepare('SELECT * FROM blocked WHERE `users_ID` = :user AND `blocked_ID` = :blocked');
        $sql->bindParam(':user', $userID);
        $sql->bindParam(':blocked', $blockedID);
        $sql->execute();

        $res = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (isset($res[0]['users_ID']))
            return true;
        return false;
    }

    public function listViewed($userID)
    {
        $db = $this->dbConnect();

        $liked = $db->prepare('SELECT viewed.timestamp AS `date`,users.userName AS `userName`
                                         FROM `users` INNER JOIN `viewed` ON viewed.viewed_ID = users.ID 
                                         WHERE `users_ID` = :userID
                                         ORDER BY `date` DESC');
        $liked->bindParam(':userID', $userID);
        $liked->execute();

        return $liked;
    }

    public function listViewedBy($userID)
    {
        $db = $this->dbConnect();

        $liked = $db->prepare('SELECT viewed.timestamp AS `date`,users.userName AS `userName`
                                         FROM `users` INNER JOIN `viewed` ON viewed.users_ID = users.ID 
                                         WHERE `viewed_ID` = :userID
                                         ORDER BY `date` DESC');
        $liked->bindParam(':userID', $userID);
        $liked->execute();

        return $liked;
    }

    public function getUserByMail($email)
    {
        $db = $this->dbConnect();

        $sql_email = $db->prepare("SELECT `userName` FROM `users` WHERE `email` = :email");
        $sql_email->bindParam(':email', $email);
        $sql_email->execute();


        if ($sql_email->rowCount() > 0) {

            return $sql_email->fetch();
        }
        return false;
    }

    public function isBlocked($user, $target)
    {
        $db = $this->dbConnect();

        $blocked = $db->prepare("SELECT * FROM `blocked` WHERE `users_ID` = :user AND `blocked_ID` = :target");
        $blocked->bindParam(':user', $user);
        $blocked->bindParam(':target', $target);
        $blocked->execute();


        if ($blocked->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
