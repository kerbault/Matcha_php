<?php

function updateTags()
{
	$tags = new tags();
	$user = new user();

	$userID = $user->fetchInfo($_SESSION['username'], 'ID');
	if (isset($_POST['tags'])) {
		$list = $_POST['tags'];

		if ($list) {
			echo 'oui';
			$list = json_decode($list);
			$tags->delAllTags($userID);
			addTags($_SESSION['username'], $list);
		}
	}
	else {
		require('private/view/tagsView.php');
	}
}

function newTag($tag) {
	if (!isset($tag) || empty($tag) || sizeof($tag) < 1 || sizeof($tag) > 15 || ctype_alnum($tag) == false)
		return ;
	$tags = new tags();

	if ($tags->getTagId($tag)) {
		return ;
	}
	$tags->newTag($tag);
}

function tagsMatch($str)
{
	if (empty($str))
		return false;
	$tags = new tags();

	$result = $tags->getTags();
	$str = strtolower($str);
	$len = strlen($str);
	foreach ($result as $tag) {
		if (stristr($str, substr($tag['tag'], 0, $len))) {
			$arr[] = $tag['tag'];
		}
	}
	if (isset($arr))
		echo json_encode($arr);
	else
		return false;
}

function addTag($tag)
{
	$tags = new tags();
	$listTags = $tags->getTags();
	foreach ($listTags as $possibleTag) {
		if (strtolower($possibleTag['tag']) == strtolower($tag)) {
			$id = $possibleTag['ID'];
		}
	}
	if (isset($id)) {
		$user = new user();
		$userID = $user->fetchInfo($_SESSION['username'], 'ID');
		$res = $tags->addTagToUser($id, $userID);
		if ($res) {
			echo $tag;
		}
	}
}

function getUserTags()
{
	$user = new user();
	$tags = new tags();

	$userID = $user->fetchInfo($_SESSION['username'], 'ID');
	$tagsID = $tags->getUserTags($userID);
	if (empty($tagsID)) {
		return false;
	}
	$listTags = $tags->getTags();
	foreach ($listTags as $tag) {
		foreach ($tagsID as $id) {
			if ($tag['ID'] == $id['tag_ID']) {
				$result[] = $tag['tag'];
			}
		}
	}
	echo json_encode($result);
}

function returnUserTags($userID)
{
	$user = new user();
	$tags = new tags();

	$tagsID = $tags->getUserTags($userID);
	if (empty($tagsID)) {
		return false;
	}
	$listTags = $tags->getTags();
	foreach ($listTags as $tag) {
		foreach ($tagsID as $id) {
			if ($tag['ID'] == $id['tag_ID']) {
				$result[] = $tag['tag'];
			}
		}
	}
	return $result;
}

function deleteUserTag($querryTag)
{
	$tags = new tags();
	$user = new user();

	$userID = $user->fetchInfo($_SESSION['username'], 'ID');
	$listTags = $tags->getTags();
	foreach ($listTags as $tag) {
		if (strtolower($tag['tag']) == strtolower($querryTag)) {
			$id = $tag['ID'];
		}
	}
	if (isset($id)) {
		$tags->deleteTagFromUser($id, $userID);
	}
}
