<?php
/**
 * Created by PhpStorm.
 * User: kerbault
 * Date: 2019-03-26
 * Time: 19:39
 */

function loadSuggestions()
{
    $nfo = new userinfo();

    if (isset($_POST['order'])) $res = get_profiles();
    else $res = calculate_score(get_profiles());

    if ($res) $profiles = $nfo->addImgToProfiles($res);

    require('private/view/suggestionView.php');
}

function filter_suggest($sort, $astart, $aend, $lstart, $lend, $pstart, $pend, $tags)
{
    $nfo = new userinfo();

    if ($tags != 'false') {
		$tags = json_decode($tags);
	}
	else {
		$tags = false;
	}
    $res = get_profiles_filter($sort, intval($astart), intval($aend), intval($lstart), intval($lend), intval($pstart), intval($pend), $tags);

    if ($res)
		$profiles = $nfo->addImgToProfiles($res);
    else
		$profiles = "empty";

    require('private/view/suggestionView.php');
}

function calculate_score($array)
{
    $user = new user();
    $tags = new tags();

    if (!is_array($array)) return false;

    $userTags = $tags->getUserTags($user->fetchInfo($_SESSION['username'], 'ID'));

    foreach ($array as &$profile) {
        $profile['score'] = get_score($profile, $user, $tags, $userTags);
    }
    usort($array, "sort_score");
    array_splice($array, 50);

    return $array;
}

function get_score($match, $user, $tags, $userTags)
{

    $matchTags = $tags->getUserTags($user->fetchInfo($match['username'], 'ID'));
    $tagsNb = common_tags($userTags, $matchTags);

    if ($match['distance'] >= 1000) $posScore = 0;
    else $posScore = (100 - (($match['distance'] * 100) / 1000));

    if ($tagsNb >= 5) $tagScore = 75;
    else  $tagScore = ($tagsNb * 75) / 5;

    if ($match['popularity'] >= 500) $popScore = 50;
    else  $popScore = $match['popularity'] / 10;

    return $posScore + $tagScore + $popScore;
}

function common_tags($user1, $user2)
{
    $nb = 0;

    foreach ($user1 as $tag1) {
        foreach ($user2 as $tag2) {
            if ($tag1['tag_ID'] == $tag2['tag_ID']) $nb++;
        }
    }
    return $nb;
}

function sortByDistance($position, $array, $sort)
{
    if (!isset($array) || empty($array))
        return "empty";
    $to = new DateTime('today');
    for ($i = 0; $i < sizeof($array); $i++) {
        $from = new DateTime($array[$i]['birthDate']);
        $array[$i]['distance'] = coordinatesToDistance(explode(",", $array[$i]['position'])[0], explode(",", $array[$i]['position'])[1], explode(",", $position)[0], explode(",", $position)[1]);
        $array[$i]['birthDate'] = intval($from->diff($to)->y);
    }
    if ($sort == true) {
        usort($array, "sort_dist");
        return $array;
    }
    return $array;
}

function sort_dist($a, $b)
{
    $al = $a['distance'];
    $bl = $b['distance'];
    if ($al == $bl) {
        return 0;
    }
    return ($al > $bl) ? +1 : -1;
}

function sort_score($a, $b)
{
    if ($a['score'] == $b['score']) return 0;

    return ($a['score'] > $b['score']) ? -1 : 1;
}

function degreesToRadians($degrees)
{
    return $degrees * pi() / 180;
}

function coordinatesToDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadiusKm = 6371;

    $dLat = degreesToRadians($lat2 - $lat1);
    $dLon = degreesToRadians($lon2 - $lon1);

    $lat1 = degreesToRadians($lat1);
    $lat2 = degreesToRadians($lat2);

    $a = sin($dLat / 2) * sin($dLat / 2) + sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return intval($earthRadiusKm * $c);
}

function get_profiles_filter($sort, $astart, $aend, $lstart, $lend, $pstart, $pend, $tags)
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
	if ((isset($order) && !empty($order)) || isset($sort) && ($sort == 'locD' || $sort == 'locA')) {
		return $res;
	}
	else {
		return calculate_score($res);
	}
}

function get_profiles()
{
    $user = new user();
    $suggestions = new suggestions();

    if (verifyStatus() <= 3) {
        return false;
    }

    if (isset($_POST['order'])) {
        switch ($_POST['order']) {
            case 'Age ↗':
                $order = ' ORDER BY birthDate DESC LIMIT 50';
                break;
            case 'Age ↘':
                $order = ' ORDER BY birthDate ASC LIMIT 50';
                break;
            case 'Popularity ↗':
                $order = ' ORDER BY popularity ASC LIMIT 50';
                break;
            case 'Popularity ↘':
                $order = ' ORDER BY popularity DESC LIMIT 50';
                break;
        }
    }
    if (isset($order)) {
        $result = $suggestions->suggestedProfiles($_SESSION['username'], $user->fetchinfo($_SESSION['username'], 'genders_ID'), $user->fetchInfo($_SESSION['username'], 'orientations_ID'), $order);
    } else {
        $result = $suggestions->suggestedProfiles($_SESSION['username'], $user->fetchinfo($_SESSION['username'], 'genders_ID'), $user->fetchInfo($_SESSION['username'], 'orientations_ID'), '');
    }
    $pos = $user->fetchInfo($_SESSION['username'], 'position');

    if (isset($_POST['order']) && ($_POST['order'] == 'Distance ↘' || $_POST['order'] == 'Distance ↗')) {
        $array = sortByDistance($pos, $result, true);
        if ($_POST['order'] == 'Distance ↘') {
            $array = array_reverse($array);
        }
        array_splice($array, 50);
        return $array;
    } else {
        return sortByDistance($pos, $result, false);
    }
}
