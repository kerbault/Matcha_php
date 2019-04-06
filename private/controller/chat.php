<?php
/**
 * Created by PhpStorm.
 * User: kerbault
 * Date: 2019-03-26
 * Time: 19:25
 */

function return_matches()
{
    $user = new userinfo();
    $likes = new likes();

    $id = $user->fetchInfo($_SESSION['username'], 'ID');
    $userLiked = $likes->getLikes($id);
    $likedBy = $likes->getLikedBy($id);

    $usernames = get_matches($userLiked, $likedBy);
    if (empty($usernames)) {
        return false;
    }
    foreach ($usernames as $userID) {
        $res[] = $user->fetchProfile($userID);
    }
    return $user->addImgToProfiles($res);
}

function privateChat()
{
    $matches = return_matches();
    require('private/view/chatView.php');
}

function get_matches($userLikes, $likedBy)
{
    if (!isset($userLikes) || !isset($likedBy) || !$userLikes || !$likedBy)
        return false;
    foreach ($userLikes as $user) {
        foreach ($likedBy as $other) {
            if ($user['liked_ID'] == $other['users_ID'] && $user['users_ID'] == $other['liked_ID'] && $user['liked'] == 1 && $other['liked'] == 1) {
                $match[] = $user['liked_ID'];
            }
        }
    }
    return $match;
}

function check_match($user1, $user2)
{
    $likes = new likes();

    $status1 = $likes->getLikeStatus($user1, $user2);
    $status2 = $likes->getLikeStatus($user2, $user1);
    if ($status1 && $status1 == 1 && $status2 && $status2 = 1) return true;
    return false;
}

function send_my_message($msg, $match)
{
    $user = new user();
    $chat = new chat();

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    $receiverID = $user->fetchInfo($match, 'ID');

    if (!$userID || !$receiverID) return;
    if (isBlocked($receiverID, $userID)) return;
    if (!check_match($receiverID, $userID)) return;

    $id = $chat->get_last_id($userID, $receiverID) + 1;
    $chat->add_message($id, $userID, $receiverID, $msg);
}

function get_new_messages($clientID, $match)
{
    $chat = new chat();
    $user = new user();

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    $matchID = $user->fetchInfo($match, 'ID');

    if (!$userID || $matchID)
        return;
    $serverID = intval($chat->get_last_id($userID, $matchID));

    for ($i = intval($clientID) + 1; $i <= $serverID; $i++) {
        $res[] = $chat->get_message($i, $userID, $matchID);
    }
    if (isset($res) && !empty($res)) {
        $chat->updateNewMessages($userID);
        echo json_encode($res);
    }
}

function get_all_messages($match)
{
    $chat = new chat();
    $user = new user();

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    $matchID = $user->fetchInfo($match, 'ID');
    $matches = return_matches();

    if (!isset($matches) || empty($matches) || !isset($matchID) || empty($matchID)) {
        require('private/view/notFoundView.php');
        return;
    }
    $messages = $chat->get_messages($userID, $matchID);
    if (isset($messages) && !empty($messages)) {
        foreach ($messages as &$message) {
            if ($message['users_ID'] == $userID)
                $message['users_ID'] = "me";
        }
    }
    require('private/view/chatView.php');
}
