<?php

function like_user($username, $action)
{
    $user = new user();
    $likes = new likes();

    $likedID = $user->fetchInfo($username, 'ID');
    $userID = $user->fetchInfo($_SESSION['username'], 'ID');

    if (isBlocked($userID, $likedID))
		return;
	if (!$likedID || !$userID)
		return ;
    if ($action == 'like') {
        $exists = $likes->getLikeStatus($userID, $likedID);
        if ($exists !== false) {
            $likes->relikeUser($userID, $likedID);
        } else {
            echo $exists;
            $likes->likeUser($userID, $likedID);
        }
    } else {
        $likes->dislikeUser($userID, $likedID);
    }
    updatePopularity($username);
}

function get_color($username)
{
    $user = new user();
    $likes = new likes();

    $likedID = $user->fetchInfo($username, 'ID');
    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    $status = $likes->getLikeStatus($userID, $likedID);

    if (isset($status) && $status == 1) {
        echo 'liked';
    } else {
        echo 'notLiked';
    }
}
