<?php

function search_page($url)
{
    $nfo = new userinfo();

	//var_dump($url);
    if (isset($url[1]) && $url[1] == 'filter') {
        if (isset($url[2]) && isset($url[3]) && is_numeric($url[3]) && isset($url[4]) && is_numeric($url[4])
            && isset($url[5]) && is_numeric($url[5]) && isset($url[6]) && is_numeric($url[6]) && isset($url[7]) && is_numeric($url[7]) && isset($url[8]) && is_numeric($url[8]) && isset($url[9])) {
            if (isset($url[10]) && is_numeric($url[10]) && $url[10] > 0) {
                $page = $url[10] - 1;
            } else {
                $page = 0;
            }
            $res = filter_search($url[2], intval($url[3]), intval($url[4]), intval($url[5]), intval($url[6]), intval($url[7]), intval($url[8]), $url[9]);
        } else {
            $res = false;
        }
	} else if (isset($url[1])) {
		if (is_numeric($url[1]) && $url[1] > 0)
		$page = $url[1] - 1;
		else
		$page = 0;
		$res = get_profiles();
	} else {
        $page = 0;
        $res = get_profiles();
    }
    $pages = sizeof($res) / 15;
    if (is_array($res)) {
        $res = array_slice($res, $page * 15, 15);
        $profiles = $nfo->addImgToProfiles($res);
    }
	require('private/view/searchView.php');
}

function filter_search($sort, $astart, $aend, $lstart, $lend, $pstart, $pend, $tags)
{
    $nfo = new userinfo();

    if ($tags != 'false')
        $tags = json_decode($tags);
    else
        $tags = false;
    $res = get_search_filter($sort, $astart, $aend, $lstart, $lend, $pstart, $pend, $tags);
    if ($res)
        $profiles = $nfo->addImgToProfiles($res);
    else
        $profiles = false;
    return $profiles;
}

function get_search_filter($sort, $astart, $aend, $lstart, $lend, $pstart, $pend, $tags)
{
    $suggestions = new suggestions();
    $user = new user();
    $userTags = new tags();

	if (isset($sort)) {
		switch ($sort) {
			case 'ageA':
				$order = ' ORDER BY `birthDate` DESC';
				break;
			case 'ageD':
				$order = ' ORDER BY `birthDate` ASC';
				break;
			case 'popA':
				$order = ' ORDER BY popularity ASC';
				break;
			case 'popD':
				$order = ' ORDER BY popularity DESC';
				break;
			case 'false':
				$order = '';
				break;
		}
	}
	if (isset($order)) {
    	$res = $suggestions->suggestedProfiles($_SESSION['username'], $user->fetchinfo($_SESSION['username'], 'genders_ID'), $user->fetchInfo($_SESSION['username'], 'orientations_ID'), $order);
	}
	else {
		$res = $suggestions->suggestedProfiles($_SESSION['username'], $user->fetchinfo($_SESSION['username'], 'genders_ID'), $user->fetchInfo($_SESSION['username'], 'orientations_ID'), '');
	}
	$pos = $user->fetchInfo($_SESSION['username'], 'position');

	if (isset($sort) && ($sort == 'locD' || $sort == 'locA')) {
 	   $res = sortByDistance($pos, $res, true);
 	   if ($sort == 'locD') {
 		   $res = array_reverse($res);
 	   }
    } else {
 	  $res = sortByDistance($pos, $res, false);
    }
    foreach ($res as $key => $profile) {
        if ($profile['birthDate'] < $astart || $profile['birthDate'] > $aend) {
            unset($res[$key]);
        } else if ($profile['distance'] < $lstart || $profile['distance'] > $lend) {
            unset($res[$key]);
        } else if ($profile['popularity'] < $pstart || $profile['popularity'] > $pend) {
            unset($res[$key]);
        } else if ($tags) {
            $suggestTags = $userTags->getUserTags($profile['ID']);
            $count = 0;

            foreach ($tags as $tag) {
                foreach ($suggestTags as $suggestTag) {
                    $realTag = $userTags->getTagName($suggestTag['tag_ID']);
                    if ($tag == $realTag)
                        $count++;
                }
            }
            if ($count != sizeof($tags)) {
                unset($res[$key]);
            }
        }
    }
    return $res;
}
