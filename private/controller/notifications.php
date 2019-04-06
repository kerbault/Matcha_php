<?php

function get_notifications_number()
{
    $user = new user();
    $likes = new likes();
    $views = new views();
    $chat = new chat();

		$userID = $user->fetchInfo($_SESSION['username'], 'ID');
		if (!$userID)
			return ;
		$res['likes'] = $likes->getNewLikes($userID);
		$res['views'] = $views->getNewViews($userID);
		$res['messages'] = $chat->getNewMessages($userID);

    if (!empty($res['likes']) || !empty($res['views']) || !empty($res['messages']))
        echo(count($res['likes']) + count($res['views']) + count($res['messages']));
}

function get_notifications()
{
    $user = new user();
    $likes = new likes();
    $views = new views();
    $chat = new chat();

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    if (!$userID)
        return;
    $res['likes'] = fill_ID($user, new_match($likes, $likes->getNewLikes($userID)));
    $res['views'] = fill_ID($user, $views->getNewViews($userID));
    $res['messages'] = fill_ID($user, $chat->getNewMessages($userID));

    $likes->updateNewLikes($userID);
    $views->updateNewViews($userID);
    $chat->updateNewMessages($userID);
    echo json_encode($res);
}

function fill_ID($user, $array)
{
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            $array[$key]['users_ID'] = $user->fetchUsername($value['users_ID']);
        }
        return ($array);
    } else
        return false;
}

function new_match($likes, $array)
{
    if (is_array($array)) {
        foreach ($array as &$like) {
            if ($likes->getLikeStatus($like['liked_ID'], $like['users_ID']) == 1) {
                $like['match'] = 1;
            } else
                $like['match'] = 0;
        }
    } else
        return false;
    return $array;
}

function get_old_notifications()
{
    $user = new user();
    $likes = new likes();
    $views = new views();
    $chat = new chat();

    $userID = $user->fetchInfo($_SESSION['username'], 'ID');
    if (!$userID)
        return;
    $res['likes'] = fill_ID($user, new_match($likes, $likes->getOtherLikes($userID)));
    $res['views'] = fill_ID($user, $views->getOtherViews($userID));
    $res['messages'] = fill_ID($user, $chat->getMessages($userID));

    return $res;
}
