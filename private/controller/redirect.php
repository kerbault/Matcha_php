<?php
/**
 * Created by PhpStorm.
 * User: kerbault
 * Date: 2019-03-26
 * Time: 19:36
 */

function loadIndex()
{
    require('private/view/indexView.php');
}

function notFound()
{
    require('private/view/notFoundView.php');
}

function userAccount()
{
    if ($_SESSION['loggedIn'] = true) {
        $user = new user();

        $userID = $user->fetchInfo($_SESSION['username'], 'ID');
        $userInfo = $user->fetchProfile($userID);

        if ($userInfo['profilePicture_ID'] != null) {
            $picturesManager = new pictures();
            $profilePic = $picturesManager->getProfilePicturePath($userInfo['profilePicture_ID']);
        }
    }
    require('private/view/accountView.php');
}

function showSignUp()
{
    require('private/view/signupView.php');
}

function showLogin()
{
    require('private/view/loginView.php');
}

function showAdmin()
{
    require('private/view/adminView.php');
}

function showHistory()
{
    $res = get_old_notifications();
    require('private/view/historyView.php');
}

function showBlocked()
{
    $res = get_blocked();
    require('private/view/blockedView.php');
}

function showPictures($error)
{
    $user = new user();
    $pictures = new pictures();

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    $profilePicID = $user->fetchInfo($_SESSION['username'], 'profilePicture_ID');

    if (isset($_FILES['fileToUpload'])) {
        $error = uploadPicture(uniqid(), $_FILES['fileToUpload'], false);
    }
    $pictureList = $pictures->listPicture($userID);

    require('private/view/picturesView.php');
}

function showViewed()
{
    $user = new user();

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');

    $listViewed = $user->listViewed($userID);
    $listViewedBy = $user->listViewedBy($userID);
    $matches = return_matches();
    require('private/view/viewedView.php');
}

function showLikes()
{
    $user = new user();
    $likes = new likes();

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');

    $listLiked = $likes->listLiked($userID);
    $listLikedBy = $likes->listLikedBy($userID);
    $matches = return_matches();
    require('private/view/likesView.php');
}


