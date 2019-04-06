<?php
/**
 * Created by PhpStorm.
 * User: kerbault
 * Date: 2019-03-26
 * Time: 19:38
 */

class userinfo extends user
{
    public $id;
    public $online;
    public $lastlog;
    public $creationDate;
    public $img;
    public $fname;
    public $lname;
    public $birthDate;
    public $age;
    public $email;
    public $valid;
    public $gender;
    public $sexualOrientation;
    public $position;
    public $popularity;
    public $bio;
    public $likes;
    public $city;

    public function fetchAll($user)
    {
        $this->id = $this->fetchInfo($user, 'ID');
        $this->fname = $this->fetchInfo($user, 'firstName');
        if (!$this->fname) {
            return false;
        }
        $this->lastlog = $this->fetchInfo($user, 'lastlog');
        $this->online = $this->fetchInfo($user, 'online');
        $this->creationDate = $this->fetchInfo($user, 'creationDate');
        $this->city = $this->fetchInfo($user, 'city');
        $this->lname = $this->fetchInfo($user, 'lastName');
        $this->birthDate = $this->fetchInfo($user, 'birthdate');
        $from = new DateTime($this->birthDate);
        $to = new DateTime('today');
        $this->age = $from->diff($to)->y;
        $this->email = $this->fetchInfo($user, 'email');
        $this->valid = $this->fetchInfo($user, 'userStatus_ID');
        $this->gender = $this->fetchInfo($user, 'genders_ID');
        $this->sexualOrientation = $this->fetchInfo($user, 'orientations_ID');
        $this->position = $this->fetchInfo($user, 'position');
        $this->popularity = $this->fetchInfo($user, 'popularity');
        $this->bio = $this->fetchInfo($user, 'shortBio');
        $this->img = $this->fetchInfo($user, 'profilePicture_ID');
        return true;
    }

    public function addImgToProfiles($array)
    {
        $pics = new pictures();
        foreach ($array as $i => $value) {
            $array[$i]['img'] = $pics->getProfilePicturePath($array[$i]['profilePicture_ID']);
        }
        return $array;
    }
}

//╔═════════════════════════════════════════════════════════════════╗
//║ 						Individual check						║
//╚═════════════════════════════════════════════════════════════════╝

function checkUserName($userName)
{
    if (preg_match('/' . RGX_USER_NAME . '/', $userName) == 1) {
        return true;
    }
    return false;
}

function checkRealName($realName)
{
    if (preg_match('/' . RGX_REAL_NAME . '/', $realName) == 1) {
        return true;
    }
    return false;
}

function checkBirthDate($date)
{
    $birthDate = explode("-", $date);
    if (isset($birthDate[0]) && isset($birthDate[1]) && isset($birthDate[2])) {
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
            ? ((date("Y") - $birthDate[0]) - 1)
            : (date("Y") - $birthDate[0]));
        if ($age >= 18 && $age <= 100) {
            return true;
        }
        return false;
    }
    return false;
}

function checkPassword($pass)
{
    if (preg_match('/' . RGX_PASSWD . '/', $pass) == 1) {
        return true;
    }
    return false;
}

function checkEmail($email)
{
    $user = new user();

    if (preg_match('/' . RGX_EMAIL . '/', $email) == 1 && $user->checkUserExists("@Muda@", $email)) {
        return true;
    }
    return false;
}

function checkShortBio($bio)
{
    $bioLen = strlen($bio);

    if ($bioLen < 4 || $bioLen > 255) {
        return false;
    } else {
        return true;
    }
}

function checkCity($city)
{
    $cityLen = strlen($city);

    if ($cityLen < 2 || $cityLen > 255) {
        return false;
    }
    $url = "https://www.mapquestapi.com/geocoding/v1/address?key=lwb1H9IvnAbtBpACX60gxxsZh5wZ7lS8&inFormat=json&outFormat=json&location=" . urlencode($city) . "&thumbMaps=false";
    $response = json_decode(file_get_contents($url));
    if ($response->results[0]->locations[0]) {
        if ($response->results[0]->locations[0]->adminArea1 != '' && $response->results[0]->locations[0]->adminArea4 != '' && $response->results[0]->locations[0]->adminArea5 != '') {
            return ($response->results[0]->locations[0]->latLng->lat . ',' . $response->results[0]->locations[0]->latLng->lng);
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function verifyStatus()
{
    $user = new user();

    if (isset($_SESSION['username']) && $_SESSION['username']) {
        online_user();
        return $user->fetchInfo($_SESSION['username'], 'userStatus_ID');
    } else {
        return '0';
    }
}

//╔═════════════════════════════════════════════════════════════════╗
//║ 						Individual Updates						║
//╚═════════════════════════════════════════════════════════════════╝

function updateFirstName($userName, $firstNameNsfw)
{
    $firstName = htmlspecialchars($firstNameNsfw);

    if (!checkRealName($firstName)) {
        return false;
    } else {
        $user = new user();
        $user->updateInfo($userName, 'firstName', $firstName);
        return true;
    }
}

function updateLastName($userName, $lastNameNsfw)
{
    $lastName = htmlspecialchars($lastNameNsfw);

    if (!checkRealName($lastName)) {
        return false;
    } else {
        $user = new user();
        $user->updateInfo($userName, 'lastName', $lastName);
        return true;
    }
}

function updateBirthDate($userName, $birthDate)
{
    if (!checkBirthDate($birthDate)) {
        return false;
    } else {
        $user = new user();
        $user->updateInfo($userName, 'birthDate', $birthDate);
        return true;
    }
}

function updateEmail($userName, $emailNsfw)
{
    $email = htmlspecialchars($emailNsfw);

    if (!checkEmail($email)) {
        return false;
    } else {
        $user = new user();
        $user->updateInfo($userName, 'email', $email);
        return true;
    }
}

function updateCity($userName, $cityNsfw)
{
    $city = htmlspecialchars($cityNsfw);
    $pos = checkCity($city);
    if (!$pos) {
        return false;
    } else {
        $user = new user();
        $user->updateInfo($userName, 'city', $city);
        $user->updateInfo($userName, 'position', $pos);
        return true;
    }
}

function updatePassword($userName, $password)
{
    if (!checkPassword($password)) {
        return false;
    } else {
        $newpasswd = password_hash($password, PASSWORD_DEFAULT);

        $user = new user();
        $user->updateInfo($userName, 'password', $newpasswd);
        return true;
    }
}

function updateGenderID($userName, $genderID)
{
    if ($genderID < 2 || $genderID > 3) {
        return false;
    } else {
        $user = new user();
        $user->updateInfo($userName, 'genders_ID', $genderID);
        return true;
    }
}

function updateOrientationID($userName, $orientationID)
{
    if ($orientationID < 1 || $orientationID > 3) {
        return false;
    } else {
        $user = new user();
        $user->updateInfo($userName, 'orientations_ID', $orientationID);
        return true;
    }
}

function updateBio($userName, $bioNsfw)
{
    $bio = htmlspecialchars($bioNsfw);

    if (!checkShortBio($bio)) {
        return false;
    } else {
        $user = new user();
        $user->updateInfo($userName, 'shortBio', $bio);
        return true;
    }
}

function addTags($userName, $list)
{
    $tags = new tags();
    $user = new user();

    $userID = $user->fetchInfo($userName, 'ID');
    foreach ($list as $tag) {
        $tagID = $tags->getTagId($tag);
        if ($tagID) {
            $tags->addTagToUser($tagID, $userID);
        } else {
            return false;
        }
    }
    return true;
}

function update_gps($coords)
{
    $array = explode(',', $coords);
    if (sizeof($array) == 2) {
        if (is_numeric($array[0]) && is_numeric($array[1])) {
            $user = new user();
            $user->forceLocation($_SESSION['username'], $coords);
        }
    }
}

function updateLocation()
{
    $user = new user();

    $ip = $_SERVER['REMOTE_ADDR'];
    $city = $user->fetchInfo($_SESSION['username'], 'city');

    if ($city) {
        return false;
    }
    if (preg_match('/^172./', $ip)) {
        $ip = '185.15.27.37';
    }
    $details = json_decode(file_get_contents("http://ipinfo.io/" . $ip . "/json"));
    $user->forceLocation($_SESSION['username'], $details->loc);
    return true;
}

function updateProfile()
{
    if (isset($_POST['firstName'])) updateFirstName($_SESSION['username'], $_POST['firstName']);
    if (isset($_POST['lastName'])) updateLastName($_SESSION['username'], $_POST['lastName']);
    if (isset($_POST['email'])) updateEmail($_SESSION['username'], $_POST['email']);
    if (isset($_POST['date'])) updateBirthDate($_SESSION['username'], $_POST['date']);
    if (isset($_POST['city'])) updateCity($_SESSION['username'], $_POST['city']);
    if (isset($_POST['bio'])) updateBio($_SESSION['username'], $_POST['bio']);
    if (isset($_POST['inputGender'])) updateGenderID($_SESSION['username'], $_POST['inputGender']);
    if (isset($_POST['inputOrientation'])) updateOrientationID($_SESSION['username'], $_POST['inputOrientation']);

    if ($_SESSION['loggedIn'] = true) {
        $info = new userinfo();
        $info->fetchAll($_SESSION['username']);
    }
    require('private/view/profileView.php');
}

function updateSecurity()
{
    if (isset($_POST['oldPasswd']) && isset($_POST['newPasswd']) && isset($_POST['confirmPasswd'])) {
        $user = new user();

        $newPasswd = htmlspecialchars($_POST['newPasswd']);
        $userInfo = $user->verifyLogin($_SESSION['username']);

        if ($_POST['newPasswd'] !== $_POST['confirmPasswd']) {
            $error = 'Confirm password is different';
        } else if (!isset($userInfo['userStatus_ID']) || !isset($userInfo['password'])) {
            $error = 'This account doesn\'t exist';
        } else if (!password_verify($_POST['oldPasswd'], $userInfo['password'])) {
            $error = 'Wrong password';
        } else if (!updatePassword($_SESSION['username'], $newPasswd)) {
            $error = 'invalid new password';
        } else {
            $error = 'nope';
        }
    }
    require('private/view/securityView.php');
}

//╔═════════════════════════════════════════════════════════════════╗
//║ 				Registration / login / logout  					║
//╚═════════════════════════════════════════════════════════════════╝

function createUser($fnameNsfw, $lnameNsfw, $date, $usernameNsfw, $emailNsfw, $passwordNsfw, $confirmPass)
{
    $user = new user();

    $fname = htmlspecialchars($fnameNsfw);
    $lname = htmlspecialchars($lnameNsfw);
    $username = htmlspecialchars($usernameNsfw);
    $email = htmlspecialchars($emailNsfw);
    $passwordTmp = htmlspecialchars($passwordNsfw);
    $password = password_hash($passwordTmp, PASSWORD_DEFAULT);
    $key = uniqid();

    if (!checkRealName($fname) || !checkRealName($lname)) {
        $error = 1;
    } else if (checkBirthDate($date) == false) {
        $error = 2;
    } else if ($user->checkUserExists($username, $email) == false) {
        $error = 3;
    } else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $error = 4;
    } else if (checkPassword($passwordTmp) == false || $passwordNsfw != $confirmPass) {
        $error = 5;
    } else {
        $user->createAccount($fname, $lname, $date, $username, $email, $password, $key);

        $headers = 'From: admin@matcha.fr' . "\r\n" .
            'Reply-To:' . $email . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($email, "Matcha", "Welcome to matcha " . $fname . " !\rTo validate your account, please follow this link:\rhttp://localhost/validate_account/" . $username . "/" . $key, $headers);

        $error = 0;
    }
    require('private/view/signupView.php');
}

function addInfoUser()
{
    $user = new user();
    $genderID = $_POST['gender'];
    $orientationID = $_POST['orientation'];
    $bioNsfw = $_POST['bio'];
    $tags = json_decode($_POST['tags']);

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    $userInfo = $user->fetchProfile($userID);
    if (!updateGenderID($_SESSION['username'], $genderID)) {
        $error = 1;
    } else if (!updateOrientationID($_SESSION['username'], $orientationID)) {
        $error = 2;
    } else if ($userInfo['profilePicture_ID'] == null && uploadPicture(uniqid(), $_FILES['file'], true) != 'OK') {
        $error = 3;
    } else if (!updateBio($_SESSION['username'], $bioNsfw)) {
        $error = 4;
    } else if (!addTags($_SESSION['username'], $tags)) {
        $error = 5;
    } else {
        if ($userInfo['userStatus_ID'] === 3) {
            $user->updateInfo($_SESSION['username'], 'userStatus_ID', 4);
        }
        $error = 0;
    }
    require('private/view/accountView.php');
}

function validateAccount($username, $key)
{
    $user = new user();

    $userStatus = $user->fetchInfo($username, 'userStatus_ID');

    if ($key == '0' || $userStatus != 2) {
        header('Location: /login');
    } else {
        $user->verifyKey($username, $key);
        header('Location: /login');
    }
}

function loginUser($userNameNsfw, $passwordNsfw)
{
    sleep(1);
    $user = new user();

    $userName = htmlspecialchars($userNameNsfw);
    $password = htmlspecialchars($passwordNsfw);

    $userInfo = $user->verifyLogin($userName);

    if (!isset($userInfo['userStatus_ID']) || !isset($userInfo['password'])) {
        $error = 'This account doesn\'t exist';
    } else if ($userInfo['userStatus_ID'] < 3) {
        $error = 'Account is blocked or not verified';
    } else if (!password_verify($password, $userInfo['password'])) {
        $error = 'Wrong password';
    } else {
        $user->loginUser($userName);
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $userName;
        if (updateLocation()) {
            ?>
            <script>
                window.addEventListener('load', getLocation);

                function getLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(showPosition, showError);
                    }
                }

                function showPosition(position) {
                    const lat = position.coords.latitude.toString();
                    const long = position.coords.longitude.toString();
                    const loc = lat.substring(0, 19) + "," + long.substring(0, 19);
                    $.ajax({
                        url: "/ajaxNewCoordinates/" + loc,
                        success: function () {
                            window.location.replace('/');
                        },
                        error: function (res) {
                            console.log(res);
                        }
                    });
                }

                function showError(error) {
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            window.location.replace('/');
                            break;
                    }
                }
            </script>
            <?php
        } else {
            header("Location: /");
        }
    }
    require('private/view/loginView.php');

}

function logoutUser()
{
    offline_user();
    session_destroy();
    unset($_SESSION['logedIn']);
    unset($_SESSION['username']);
    header('location: /');
}

function resetPassword1st()
{
    if (isset($_POST['email13'])) {

        $mail = htmlspecialchars($_POST['email13']);
        $user = new user();

        $userNameTmp = $user->getUserByMail($mail);
        $userName = $userNameTmp['userName'];

        if ($userName && filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $headers = 'From: admin@matcha.fr' . "\r\n" .
                'Reply-To:' . $mail . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            $key = hash('sha1', uniqid());

            $user->updateInfo($userName, 'validKey', $key);
            mail($mail,
                "Matcha",
                "It seems that you tried to recover your information " . $userName . " !
                \rTo reset your password, please follow this link:\rhttp://localhost/resetPassword/" . $userName . "/" . $key, $headers);

        }
        $valid = 'OK';
    }
    require('private/view/forgot1st.php');
}

function resetPassword2nd($userNameNsfw, $keyNsfw)
{
    $user = new user();

    $userName = htmlspecialchars($userNameNsfw);
    $key = htmlspecialchars($keyNsfw);
    if ($user->fetchInfo($userName, 'validKey') == $key && $key != '0') {
        if (isset($_POST['newPasswd']) && isset($_POST['confirmPasswd'])) {
            if ($_POST['newPasswd'] == $_POST['confirmPasswd']) {
                $passwordTmp = htmlspecialchars($_POST['newPasswd']);
                $password = password_hash($passwordTmp, PASSWORD_DEFAULT);

                $user->updateInfo($userName, 'password', $password);
                $user->updateInfo($userName, 'validKey', '0');
                $valid = "OK";
            } else {
                $valid = "KO";
            }
        }
        require('private/view/resetPassword.php');

    } else {
        require('private/view/notFoundView.php');
    }
}


//╔═════════════════════════════════════════════════════════════════╗
//║ 						Get informations 						║
//╚═════════════════════════════════════════════════════════════════╝

function userProfile($username)
{
    $user = new user();
    $views = new views();
    $info = new userInfo();
    $pictures = new pictures();
    $info->fetchAll($username);

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    $userTags = returnUserTags($user->fetchInfo($username, 'ID'));
    $info->img = $pictures->getProfilePicturePath($info->img);

    if (isBlocked($info->id, $userID)) {
        require('private/view/notFoundView.php');
    } else if (isset($info) && $info->fname) {
        if ($views->alreadyVisited($userID, $info->id)) {
            $views->reviewUser($userID, $info->id);
        } else {
            $views->viewUser($userID, $info->id);
        }
        updatePopularity($username);
        require('private/view/userProfileView.php');
    } else {
        require('private/view/notFoundView.php');
    }
}

function showProfile()
{
    if ($_SESSION['loggedIn'] = true) {
        $info = new userInfo();
    }
    require('private/view/profileView.php');
}

function adminPanel()
{
    if (verifyStatus() == 5) {
        $user = new user();

        $users = $user->listUsers();
        $roles = $user->listRoles();

        $reports = $user->listReported();
        $bans = $user->bannedUsers();

        if (isset($_POST['userID']) && isset($_POST['newRole'])) {
            $user->updateInfo($_POST['userID'], 'userStatus_ID', $_POST['newRole']);
        }
        require('private/view/adminView.php');
    } else {
        header('location: /');
    }
}

function updatePopularity($username)
{
    $views = new views();
    $likes = new likes();
    $popularity = new popularity();
    $user = new user();

    $profileID = $user->fetchInfo($username, 'ID');
    $popularity->updatePopularity(($views->getViews($profileID) + $likes->getLikesNumber($profileID) * 10), $profileID);
}

function reportUser($username)
{
    $user = new user();

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    $reportedID = $user->fetchInfo($username, 'ID');

    if (!$userID || !$reportedID) {
        return;
    }
    if ($user->alreadyReported($userID, $reportedID))
        return;
    $user->report($userID, $reportedID);
}

function blockUser($username)
{
    $user = new user();
    $likes = new likes();

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    $blockedID = $user->fetchInfo($username, 'ID');

    if (!$userID || !$blockedID) return;
    if ($user->alreadyBlocked($userID, $blockedID)) return;

    $user->block($userID, $blockedID);
    $likes->dislikeUser($userID, $blockedID);
}

function unblock_user($blocked)
{
    $user = new user();

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    $blockedID = $user->fetchInfo($blocked, 'ID');

    if (!$userID || !$blockedID) {
        return;
    }
    if ($user->alreadyBlocked($userID, $blockedID) == false) {
        return;
    }
    $user->unblockUser($userID, $blockedID);
    header('Location: /account/blocked');
}

function get_blocked()
{
    $user = new user();

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    $res = $user->getBlocked($userID);

    if (!$res)
        return;
    foreach ($res as $key => $block) {
        $res[$key]['blocked_name'] = $user->fetchUsername($block['blocked_ID']);
    }
    return $res;
}

function offline_user()
{
    $user = new user();

    $user->logoutUser($_SESSION['username']);
}

function online_user()
{
    $user = new user();

    $user->loginUser($_SESSION['username']);
}

function isBlocked($user, $target)
{
    $userManager = new user;

    return $userManager->isBlocked($user, $target);
}
