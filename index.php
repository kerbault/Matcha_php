<?php
session_start();

require_once('private/config/regex.php');

//╔═════════════════════════════════════════════════════════════════╗
//║ 						Models integration						║
//╚═════════════════════════════════════════════════════════════════╝

require_once('private/model/Manager.php');
require_once('private/model/ChatManager.php');
require_once('private/model/UserManager.php');
require_once('private/model/HydrateManager.php');
require_once('private/model/SuggestionsManager.php');
require_once('private/model/TagsManager.php');
require_once('private/model/UserManager.php');
require_once('private/model/LikesManager.php');
require_once('private/model/PicturesManager.php');
require_once('private/model/ViewsManager.php');
require_once('private/model/popularityManager.php');

//╔═════════════════════════════════════════════════════════════════╗
//║ 					Controllers integration						║
//╚═════════════════════════════════════════════════════════════════╝

require_once('private/controller/chat.php');
require_once('private/controller/hydrate.php');
require_once('private/controller/redirect.php');
require_once('private/controller/suggestions.php');
require_once('private/controller/tags.php');
require_once('private/controller/user.php');
require_once('private/controller/like.php');
require_once('private/controller/notifications.php');
require_once('private/controller/pictures.php');
require_once('private/controller/search.php');

//╔═════════════════════════════════════════════════════════════════╗
//║ 						External Libraries						║
//╚═════════════════════════════════════════════════════════════════╝

require_once('public/libraries/Faker/src/autoload.php');

//╔═════════════════════════════════════════════════════════════════╗
//║ 						Database Setup							║
//╚═════════════════════════════════════════════════════════════════╝

require_once('private/config/database.php');
require_once('private/config/setup.php');

//╔═════════════════════════════════════════════════════════════════╗
//║ 							Rooter								║
//╚═════════════════════════════════════════════════════════════════╝

$url = '';

if (isset($_GET['url'])) {
    $url = explode('/', $_GET['url']);
}

if ($url == '') {
    if (verifyStatus() == 0)
        loadIndex();
    else {
	    loadSuggestions();
	}
} else {
    switch ($url[0]) {

//	Basic routes

        case 'search':
            if (verifyStatus() < 3) goto LOGIN;
            elseif (verifyStatus() == 3) goto EXTEND;
            search_page($url);
            break;
        case 'account':
            if (verifyStatus() < 3) goto LOGIN;
            elseif (verifyStatus() == 3) goto EXTEND;
            if (isset($url[1])) {
                if ($url[1] == "pictures") {
                    $error = 'nope';
                    if (isset($url[2])) {
                        if ($url[2] == "setProfile") {
                            if (isset($url[3])) {
                                $error = setProfilePicture($url[3]);
                            };
                        } elseif ($url[2] == "remove") {
                            if (isset($url[3])) {
                                $error = remPicture($url[3]);
                            };
                        }
                    }
                    showPictures($error);
                    break;
                } elseif ($url[1] == "tags") {
                    updateTags();
                    break;
                } elseif ($url[1] == "admin") {
                    adminPanel();
                    break;
                } elseif ($url[1] == "likes") {
                    showLikes();
                    break;
                } elseif ($url[1] == "blocked") {
                    showBlocked();
                    break;
                } elseif ($url[1] == "security") {
                    updateSecurity();
                    break;
                } elseif ($url[1] == 'history') {
                    showHistory();
                    break;
                } elseif ($url[1] == 'viewed') {
                    showViewed();
                    break;
                }
            }
            updateProfile();
            break;
        case 'chat':
            if (verifyStatus() < 3) goto LOGIN;
            elseif (verifyStatus() == 3) goto EXTEND;

            if (!isset($_SESSION['username'])) showLogin();
            else if (!isset($url[1])) privateChat();
            else get_all_messages($url[1]);
            break;
        case 'signup':
            if (verifyStatus() != 0) goto NOTFOUND;

            if (!isset($_POST['fname']) && !isset($_POST['lname']) && !isset($_POST['date']) && !isset($_POST['username']) && !isset($_POST['email']) && !isset($_POST['pass1']) && !isset($_POST['pass2'])) {
                showSignUp();
            } else {
                createUser($_POST['fname'], $_POST['lname'], $_POST['date'], $_POST['username'], $_POST['email'], $_POST['pass1'], $_POST['pass2']);
            }
            break;
        case 'login':
            LOGIN:
            if (verifyStatus() != 0) goto NOTFOUND;

            if (!isset($_POST['username']) || !isset($_POST['password'])) {
                showLogin();
            } else {
                loginUser($_POST['username'], $_POST['password']);
            }
            break;
        case 'extend':
            EXTEND:
            if (verifyStatus() != 3) goto NOTFOUND;

            if (!isset($_POST['inputGender']) && !isset($_POST['inputOrientation']) && !isset($_FILES['fileToUpload']) && !isset($_POST['bio'])) {
                userAccount();
            } else {
                addInfoUser($_POST['inputGender'], $_POST['inputOrientation'], $_POST['bio']);
            }
            break;
        case 'filter':
            if (verifyStatus() < 3) goto LOGIN;
            elseif (verifyStatus() == 3) goto EXTEND;
            if (!isset($_POST['sort']) || !isset($_POST['aleft']) || !isset($_POST['aright']) || !isset($_POST['locleft']) || !isset($_POST['locright']) || !isset($_POST['popleft']) || !isset($_POST['popright']) || !isset($_POST['tags_array'])) goto NOTFOUND;
            filter_suggest($_POST['sort'], $_POST['aleft'], $_POST['aright'], $_POST['locleft'], $_POST['locright'], $_POST['popleft'], $_POST['popright'], $_POST['tags_array']);
            break;
        case 'forgot1st':
            if (verifyStatus() != 0) goto NOTFOUND;
            resetPassword1st();
            break;
        case 'resetPassword':
            if (!isset($url[1]) || !isset($url[2])) goto NOTFOUND;
            resetPassword2nd($url[1], $url[2]);
            break;

//	User Actions

        case 'logout':
            if (verifyStatus() < 3) goto LOGIN;

            logoutUser();
            break;
        case 'validate_account':
            if (verifyStatus() > 2) goto NOTFOUND;
            if (!isset($url[1]) || !isset($url[2])) goto NOTFOUND;
            validateAccount($url[1], $url[2]);
            break;
        case 'user':
            if (verifyStatus() < 4) goto LOGIN;
            elseif (verifyStatus() == 3) goto EXTEND;
            if (!isset($url[1])) goto NOTFOUND;
            userProfile($url[1]);
            break;
        case 'unblock':
            if (verifyStatus() < 4) return;
            if (!isset($url[1])) goto NOTFOUND;
            unblock_user($url[1]);
            break;
        case 'offlineUser':
            if (verifyStatus() < 3) goto LOGIN;
            offline_user();
            break;

//	Utils Actions

        case 'hydrate':
            // if (verifyStatus() < 3) goto LOGIN;
            // elseif (verifyStatus() == 3) goto EXTEND;
            // elseif (verifyStatus() < 5) goto NOTFOUND;

            if (!isset($url[1])) goto NOTFOUND;
            hydrateDb($url[1]);
            break;
        case 'ajaxExtendedRegistration':
            if (verifyStatus() < 3) return;
            elseif (verifyStatus() > 3) return;
            addInfoUser();
            break;

//	Tags Actions

        case 'ajaxTags':
            if (verifyStatus() < 3) return;
            if (!isset($url[1])) return;
            tagsMatch($url[1]);
            break;
        case 'ajaxAddTag':
            if (verifyStatus() < 3) return;
            if (!isset($url[1])) return;
            addTag($url[1]);
            break;
        case 'ajaxNewTag':
            if (verifyStatus() < 3) return;
            if (!isset($url[1])) return;
            newTag($url[1]);
            break;
        case 'ajaxGetUserTags':
            if (verifyStatus() < 4) return;
            getUserTags();
            break;
        case 'ajaxDelTag':
            if (verifyStatus() < 3) return;
            if (!isset($url[1])) return;
            deleteUserTag($url[1]);
            break;

// Likes Actions

        case 'ajaxLikeUser':
            if (verifyStatus() < 4) return;
            if (!isset($url[1]) || !isset($url[2])) return;
            like_user($url[1], $url[2]);
            break;
        case 'ajaxLikeStatus':
            if (verifyStatus() < 4) return;
            if (!isset($url[1])) return;
            get_color($url[1]);
            break;

// Messages Actions

        case 'ajaxSendMessage':
            if (verifyStatus() < 4) return;
            if (!isset($_POST['msg']) || !isset($_POST['user'])) return;
            send_my_message($_POST['msg'], $_POST['user']);
            break;
        case 'ajaxCheckNewMessages':
            if (verifyStatus() < 4) return;
            if (!isset($_POST['last_id']) || !isset($_POST['user'])) return;
            get_new_messages($_POST['last_id'], $_POST['user']);
            break;

// Notifications Actions

        case 'ajaxGetNotificationsNumber':
            if (verifyStatus() < 4) return;
            get_notifications_number();
            break;
        case 'ajaxGetNotifications':
			if (verifyStatus() < 4) return;
            get_notifications();
            break;

// Localisation Actions

        case 'ajaxNewCoordinates':
            if (verifyStatus() < 3) return;
            if (!isset($url[1])) return;
            update_gps($url[1]);
            break;

// report - block Actions

        case 'ajaxReportUser':
            if (verifyStatus() < 4) return;
            if (!isset($url[1])) return;
            reportUser($url[1]);
            break;

        case 'ajaxBlockUser':
            if (verifyStatus() < 4) return;
            if (!isset($url[1])) return;
            blockUser($url[1]);
            break;

        default:
            NOTFOUND:
            notFound();
            break;
    }
}
